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

class BruteForce
{

    private array $errors = [];

    /**
     * Undocumented function
     *
     * @param object $user
     * @param object $repository
     * @param string $passwordHash
     * @return void
     */
    public function isForceable(object $user, object $repository, string $passwordHash, string $email): object
    {
        if (!isset($user->id)) {
            $this->errors = Error::display('err_invalid_credentials');
        } else if (($user->user_failed_logins >= 3) && ($user->user_last_failed_login > (time() - 30))) {
            $this->errors = 'You have enetered your password wrong more than 3 times';
        } else if (!password_verify($passwordHash, $user->password_hash)) {
            /* increment the fail login counter for that user */
            $repository->getRepo()
                ->getEm()
                ->getCrud()
                ->rawQuery(
                    'UPDATE `users` SET user_failed_logins = :user_failed_logins, user_last_failed_login = :user_last_failed_login WHERE email = :email',
                    ['user_failed_logins' => +1, 'user_last_failed_login' => time(), 'email' => $email]
                );
            $this->errors = Error::display('err_invalid_credentials');
        } else if ($user->status != 'active') {
            $this->errors = Error::display('err_invalid_credentials');
        } else {
            // reset the failed login counter for that user
            $repository->getRepo()
                ->getEm()
                ->getCrud()
                ->rawQuery(
                    'UPDATE `users` SET user_failed_logins = 0, user_last_failed_login = NULL WHERE id = :id AND user_failed_logins !=0',
                    ['id' => $user->id]
                );

            return $user;
        }
    }

    

    public function getForceableErrors(): array
    {
        return $this->errors;
    }
}
