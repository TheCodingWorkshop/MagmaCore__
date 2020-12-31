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

namespace MagmaCore\Session;

class SessionConfig
{

    /**
     * Main session configuration default array settings
     * 
     * @return array
     */
    public function baseConfiguration() : array
    {
        return [
            'session_name' => '__magmacore_session__',
            'lifetime' => 1800, /* 30min */
            'path' => '/',
            'domain' => NULL,
            'secure' => false,
            'httponly' => true,
            'gc_maxlifetime' => '1800',
            'gc_divisor' => '100',
            'gc_probability' => '1000',
            'use_cookies' => '1',
            'storage' => [
                'native' => [
                    'class' => \MagmaSession\Session\Storage\NativeSessionStorage::class,
                    'alias' => 'nativeSessionStorage',
                    'default' => true
                ]
            ]
        ];
    }

}