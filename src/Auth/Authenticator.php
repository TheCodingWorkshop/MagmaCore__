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
use Throwable;

class Authenticator
{

    protected array $errors = [];

    /**
     * Authenticate the user by their email and password and only if their account status is active
     * 
     * @param string $email
     * @param string $password
     * @return Object
     */
    public function authenticate(string $email, string $passqwordHash, ?Object $currentObject = null)
    {
        try {
            $user = (new UserModel())->getRepo()->findObjectBy(['email' => $email]);
            if ($user && $user->status == 'active') {
                if (password_verify($passqwordHash, $user->password_hash)) {
                    return $user;
                } 
            }  else {
                die('Invalid Password');
            }   

        } catch(Throwable $th) {
            $currentObject->flashMessage('Invalid user credentials. Please check your details and tru again.', $currentObject->flashWarning());
            $currentObject->redirect('/login');
        }
    }

    public function getErrors() : array
    {
        return $this->errors;
    }
}
