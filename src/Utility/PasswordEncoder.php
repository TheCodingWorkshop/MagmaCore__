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

namespace MagmaCore\Utility;

class PasswordEncoder
{

    /**
     *
     * @param string|null $password
     * @return string|null
     */
    public static function encode(string $password = null) : ?string
    {
        static $encodedPassword = null;
        if ($encodedPassword === null) {
            if (!empty($password)) {
                $encodedPassword = password_hash($password, PASSWORD_DEFAULT);
            } else {
                $encodedPassword = '';
            }    
        }

        return $encodedPassword;

    }

}
