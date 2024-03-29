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

use MagmaCore\Cache\Exception\CacheException;

class CacheFacade
{

    /**
     * Undocumented function
     *
     * @param string|null $cacheIdentifier
     * @param string|null $storage
     * @param array $options
     * @return CacheInterface
     */
    public function create(
        ?string $cacheIdentifier = null,
        ?string $storage = null,
        array $options = []
    ): CacheInterface
    {
        try {
            return (new CacheFactory())->create($cacheIdentifier, $storage, $options);
        } catch(CatchException $e) {
            throw $e->getMessage();
        }
    }
}
