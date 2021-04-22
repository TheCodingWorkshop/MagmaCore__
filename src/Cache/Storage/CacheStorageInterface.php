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

namespace MagmaCore\Cache\Storage;

interface CacheStorageInterface
{

    /**
     * Saves data in the cache.
     *
     * @param string $key An identifier for this specific cache entry
     * @param string $value The data to be stored
     * @param integer $ttl Lifetime of this cache entry in seconds. 
     *                If NULL is specified, the default lifetime is used. "0" means unlimited lifetime.
     * @return void
     * @api
     */
    public function setCache(string $key, string $value, int $ttl = null): void;

    /**
     * Loads data from the cache.
     *
     * @param string $key An identifier which describes the cache entry to load
     * @return mixed The cache entry's content as a string or false if the 
     *               cache entry could not be loaded
     * @api
     */
    public function getCache(string $key): mixed;

    /**
     * Checks if a cache entry with the specified identifier exists.
     *
     * @param string $key An identifier specifying the cache entry
     * @return boolean true if such an entry exists, false if not
     * @api
     */
    public function hasCache(string $key): bool;

    /**
     * Removes all cache entries matching the specified identifier.
     * Usually this only affects one entry but if - for what reason ever -
     * old entries for the identifier still exist, they are removed as well.
     *
     * @param string $key Specifies the cache entry to remove
     * @return boolean true if (at least) an entry could be removed or false if no entry was found
     * @api
     */
    public function removeCache(string $key): bool;

    /**
     * Removes all cache entries of this cache.
     *
     * @return void
     * @api
     */
    public function flush(): void;

    /**
     * Does garbage collection
     *
     * @return void
     * @api
     */
    public function collectGarbage(): void;
}
