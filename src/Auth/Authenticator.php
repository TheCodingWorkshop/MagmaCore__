<?php
/*
 * This file is part of the MagmaCore package.
 *
 * (c) Ricardo Miller <ricardomiller@lava-studio.co.uk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace MagmaCore\Auth;

use App\Model\UserModel;
use MagmaCore\Error\Error;
use MagmaCore\Utility\Sanitizer;
use MagmaCore\Utility\Validator;

class Authenticator
{

    /** @var array $errors */
    protected array $errors = [];
    /** @var bool $action */
    protected bool $action = false;
    private UserModel $repository;
    private object $user;
    private object $object;
    private ?object $validatedUser;
    private string $email;

    /**
     * Authenticate the user by their email and password and only if their account
     * status is active
     *
     * @param string $email
     * @param string $passqwordHash
     * @return object|null
     */
    public function authenticate(string $email, string $passqwordHash): ?object
    {
        $this->repository = (new UserModel());
        $this->validate(['email' => $email, 'password_hash' => $passqwordHash]);
        if (empty($this->errors)) {
            $this->user = $this->repository->getRepo()->findObjectBy(['email' => $email]);
            if ($this->user && $this->user->status == 'active') {
                $this->action = true;
                if (password_verify($passqwordHash, $this->user->password_hash)) {
                    $this->action = true;
                    if ($this->user->user_failed_logins !==0) {
                        $this->forceReset();
                    }
                    return $this->user;
                }
            }
        }
        return null;
        
    }

    /**
     * Returned the validated user object. If the credentials passed in matches
     * a database record. else will generate a error from the validate() method
     * below.
     *
     * @param object $object
     * @param string|null $email
     * @param string|null $password
     * @return object
     */
    public function getValidatedUser(object $object, string|null $email = null, string|null $password = null): object
    {
        $this->object = $object;
        $req = $object->request->handler();
        $this->validatedUser = $this->authenticate(
            ($email !== null) ? $req->get($email) : $req->get('email'),
            ($password !== null) ? $req->get($password) : $req->get('password_hash'),
            $this->object
        );
        if (!$this->validatedUser) {
            if ($object->error) {
                $object->error->addError($this->getErrors(), $object)->dispatchError();
            }
        }
        return $this->validatedUser;
    }

    public function getAuthUser(): array
    {
        return (array)$this->validatedUser;
    }

    /**
     * Get the remember_me value from the request if the checkbox was checked
     *
     * @return boolean
     */
    public function isRememberingLogin(): bool
    {
        $remember = $this->object->request->handler()->get('remember_me');
        if ($remember) {
            return $remember;
        }
    }

    /**
     * Call the authorized class and initialize the login method passing
     * the relevant arguments. This helper method is called from the 
     * loginAction from the core Base domain class
     *
     * @return void
     */
    public function getLogin(): void
    {
        Authorized::login($this->validatedUser, $this->isRememberingLogin());
    }

    public function forceDetected(string $email)
    {
        $this->repository->getRepo()
            ->getEm()
            ->getCrud()
            ->rawQuery(
                'UPDATE `users` SET user_failed_logins = user_failed_logins+1, user_last_failed_login = :user_last_failed_login WHERE email = :email',
                ['user_last_failed_login' => time(), 'email' => $email]
            );
    }

    public function forceReset()
    {
        // reset the failed login counter for that user
        $this->repository->getRepo()
            ->getEm()
            ->getCrud()
            ->rawQuery(
                'UPDATE `users` SET user_failed_logins = 0, user_last_failed_login = NULL WHERE id = :id AND user_failed_logins !=0',
                ['id' => $this->user->id]
            );
    }


    /**
     * Validate the user login credentials. Ensure the email is valid and exists
     * and checks the password validates against user stored password. We are also
     * ensuring that both password and email is not left empty. This a second defence
     * layer has HTML5 would have already validate the inputs for the correct 
     * information.
     *
     * @param array $dirtyData
     * @return array - an array of error messages
     */
    public function validate(array $dirtyData): array
    {
        $cleanData = Sanitizer::clean($dirtyData);
        if (is_array($cleanData)) {
            foreach ($cleanData as $key => $value) {
                switch ($key) {
                    case 'email':
                        if (!Validator::email($value)) {
                            $this->errors = Error::display('err_invalid_email');
                        }
                        if (!$this->repository->emailExists($value, null)) {
                            $this->errors = Error::display('err_invalid_account');
                        }
                        $this->email = $value;
                        break;
                    case 'password_hash':
                        if (empty($value)) {
                            $this->errors = Error::display('err_password_require');
                        }
                        $user = $this->repository->getRepo()->findObjectBy(['email' => $this->email], ['password_hash', 'user_failed_logins', 'user_last_failed_login']);

                        if (($user->user_failed_logins >= 3) && ($user->user_last_failed_login > (time() - 30))) {
                            $this->errors = Error::display('err_password_force');
                        } else if (!password_verify($value, $user->password_hash)) {
                            $this->forceDetected($this->email);
                            $this->errors = Error::display('err_invalid_credentials');
                        }
                        break;
                    default:
                        $this->errors = Error::display('err_invalid_user');
                        break;
                }
            }
            return $this->errors;
        }
    }

    /**
     * Returns all the user authenticated errors
     *
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * Returns true if user credentials is correct and if the password is verified 
     * else return false otherwise
     *
     * @return boolean
     */
    public function getAction(): bool
    {
        return $this->action;
    }
}
