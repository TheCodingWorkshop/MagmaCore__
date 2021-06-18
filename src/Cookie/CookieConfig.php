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

namespace MagmaCore\Cookie;

use JetBrains\PhpStorm\ArrayShape;

class CookieConfig
{

    /** @return void */
    public function __construct()
    {
    }

    /**
     * Main cookie configuration default array settings
     * 
     * @return array
     */
    #[ArrayShape(['name' => "string", 'expires' => "int", 'path' => "string", 'domain' => "string", 'secure' => "false", 'httponly' => "bool"])] public function baseConfig(): array
    {
        return [

            'name' => '__magmacore_cookie__',
            'expires' => 3600,
            'path' => '/',
            'domain' => 'localhost',
            'secure' => false,
            'httponly' => true

        ];
    }
}
