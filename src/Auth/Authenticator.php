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
use MagmaCore\Utility\Validator;
use MagmaCore\Utility\Sanitizer;
use MagmaCore\DataObjectLayer\DataLayerTrait;

class Authenticator
{

    use DataLayerTrait;
    protected array $errors = [];
    protected bool $action = false;

    /**
     * Authenticate the user by their email and password and only if their account status is active
     * 
     * @param string $email
     * @param string $password
     * @return mixed
     */
    public function authenticate(string $email, string $passqwordHash, Null|Object $currentObject = null)
    {
        $this->repository = (new UserModel());
        $this->validate(['email' => $email, 'password_hash' => $passqwordHash]);
        if (empty($this->errors)) {
            $this->user = $this->repository->getRepo()->findObjectBy(['email' => $email]);
            if ($this->user && $this->user->status == 'active') {
                $this->action = true;
                if (password_verify($passqwordHash, $this->user->password_hash)) {
                    $this->action = true;
                    return $this->user;
                }
            }
        }
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
    private function validate(array $dirtyData) : array
    {
        $cleanData = Sanitizer::clean($dirtyData);
        if (is_array($cleanData)) {
            foreach ($cleanData as $key => $value) {
                switch ($key) {
                    case 'email' :
                        if (!Validator::email($value)) {
                            $this->errors[] = 'Please enter a valid email address.';
                        }
                        if (!$this->repository->emailExists($value, null)) {
                            $this->errors[] = 'User Account does not exists';
                        }
                        $this->email = $value;
                        break;
                    case 'password_hash' :
                        if (empty($value)) {
                            $this->errors[] = 'Please enter your password';
                        }       
                        $user = $this->repository->getRepo()->findObjectBy(['email' => $this->email], ['password_hash']);
                        if (!password_verify($value, $user->password_hash)) {
                            $this->errors[] = 'Unmatched credentials. Please try again';
                        }
                        break;
                    default :
                        $this->errors[] = 'Invalid user credentials';
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
    public function getErrors() : array
    {
        return $this->errors;
    }
    
    /**
     * Returns true if user credentials is correct and if the password is verified 
     * else return false otherwise
     *
     * @return boolean
     */
    public function getAction() : bool
    {
        return $this->action;
    }
}
