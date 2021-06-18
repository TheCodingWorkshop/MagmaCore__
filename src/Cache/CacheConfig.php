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

namespace MagmaCore\Cache;

use JetBrains\PhpStorm\ArrayShape;

class CacheConfig
{

    /** @var string */
    private const DEFAULT_DRIVER = 'native_storage';

    /**
     * Main session configuration default array settings
     * 
     * @return array
     */
    #[ArrayShape(['use_cache' => "bool", 'key' => "string", 'cache_path' => "string", 'cache_expires' => "int", 'default_storage' => "string", 'drivers' => "array[]"])] public function baseConfiguration(): array
    {
        return [
            'use_cache' => true,
            'key' => 'auto',
            'cache_path' => '/Storage/Cache/',
            'cache_expires' => 3600,
            'default_storage' => self::DEFAULT_DRIVER,
            'drivers' => [
                'native_storage' => [
                    'class' => '\MagmaCore\Cache\Storage\NativeCacheStorage',
                    'default' => true
                ],
                'array_storage' => [
                    'class' => '\MagmaCore\Cache\Storage\ArrayCacheStorage',
                    'default' => false

                ],
                'pdo_storage' => [
                    'class' => '\MagmaCore\Cache\Storage\PdoCacheStorage',
                    'default' => false

                ]
            ]
        ];
    }
}
