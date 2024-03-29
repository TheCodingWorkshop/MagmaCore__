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

use MagmaCore\Error\Error;
use MagmaCore\Utility\Sanitizer;
use MagmaCore\Utility\Validator;
use MagmaCore\Utility\UtilityTrait;
use MagmaCore\UserManager\UserModel;
use MagmaCore\Base\Exception\BaseException;

class Authenticator
{

    use UtilityTrait;

    /** @var array $errors */
    protected array $errors = [];
    /** @var bool $action */
    protected bool $action = false;
    protected bool $bruteForce = false;
    private $validatedUser;

    /**
     * Get the framework UserModel. We are also checking that the class exists. else it 
     * will throw an exception
     *
     * @return UserModel|null
     */
    private function getUserModel(): ?UserModel
    {
        if (!class_exists(UserModel::class)) {
            throw new BaseException(UserModel::class . ' class does not exists. This class is required for this component');
        } else {
            return (new UserModel());
        }

    }

    /**
     * Authenticate the user by their email and password and only if their account 
     * status is active
     * 
     * @param string $email
     * @param string $password
     * @param string|null $userModel
     * @return object|null
     */
    public function authenticate(string $email, string $password): ?object
    {
        $this->repository = $this->getUserModel();

        /* check for validation errors */
        $this->validate(['email' => $email, 'password_hash' => $password]);
        $this->user = $this->repository->getRepo()->findObjectBy(['email' => $email]);
        if (empty($this->errors)) {
            if ($this->user && $this->user->status === 'active') {
                if (password_verify($password, $this->user->password_hash)) {            
                    $this->action = true;
                    if ($this->user->user_failed_logins !==0) {
                        $this->forceReset();
                    }
                    return $this->user;
                } 
            }
        } else {
            $this->forceDetected($this->user->email);
        }
        return null;
        
    }

    /**
     * Returned the validatedd user object. If the credentials passed in matches
     * a database record. else will generate a erorr from the validate() method
     * below.
     *
     * @param string|null $email
     * @param string|null $password
     * @return object
     */
    public function getValidatedUser(object $object, string|null $email = null, string|null $password = null): ?object
    {
        $this->object = $object;
        $req = $object->request->handler();
        $this->validatedUser = $this->authenticate(
            ($email !== null) ? $req->get($email) : $req->get('email'),
            ($password !== null) ? $req->get($password) : $req->get('password_hash')
        );
        if (!$this->validatedUser) {
            if ($object->error) {
                $object->error->addError($this->getErrors(), $object)->dispatchError();
            }
        }
        return $this->validatedUser ?? null;
    }

    /**
     * Return the validated user object
     *
     * @return array
     */
    public function getAuthUser(): array
    {
        return (array)$this->validatedUser;
    }

    /**
     * Get the remember_me value from the request if the checkbox was checked
     *
     * @return boolean
     */
    public function isRememberingLogin()
    {
        $remember = $this->object->request->handler()->get('remember_me');
        if ($remember) {
            return $remember;
        }
        return false;
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
        return $this->repository->getRepo()
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
        return $this->repository->getRepo()
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
                            $this->errors[] = Error::display('err_invalid_email');
                        }
                        if (!$this->repository->emailExists($value, null)) {
                            $this->errors[] = Error::display('err_invalid_account');
                            $this->bruteForce = true;
                        }

                        if (!$this->repository->accountActive($value)) {
                            $this->errors[] = Error::display('err_account_not_active');
                        }
                        $this->email = $value;
                        break;
                    case 'password_hash':
                        
                        $user = $this->repository->getRepo()->findObjectBy(['email' => $this->email], ['password_hash', 'user_failed_logins', 'user_last_failed_login']);

                        if (empty($value)) {
                            $this->errors[] = Error::display('err_password_require');
                            $this->bruteForce = true;
                        }

                        if (($user->user_failed_logins >= (int)$this->security('login_attempts')) && ($user->user_last_failed_login > (time() - (int)$this->security('login_timeout')))) {
                            $this->errors[] = Error::display('err_password_force');
                            $this->bruteForce = true;
                        }
                        
                        if (isset($user->password_hash)) {
                            if (!password_verify($value, $user->password_hash)) {
                                $this->errors[] = Error::display('err_invalid_credentials');
                            }    
                        }
                        break;
                    default:
                        $this->errors[] = Error::display('err_invalid_user');
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

    public function getBruteForceAction()
    {
        return $this->bruteForce;
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
