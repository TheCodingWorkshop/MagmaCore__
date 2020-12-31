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

use MagmaCore\Auth\Model\UserModel;

class Authenticator
{

    /**
     * Authenticate the user by their email and password and only if their account status is active
     * 
     * @param string $email
     * @param string $password
     * @return Object
     */
    public static function authenticate(string $email, string $passqwordHash) : Object
    {
        $user = (new UserModel())->getRepo()->findObjectBy(['email' => $email]);
        if ($user && $user->status == 'active') {
            if (password_verify($passqwordHash, $user->password_hash)) {
                return $user;
            }
        }
    }

}
