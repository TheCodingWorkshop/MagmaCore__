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
use MagmaCore\Cache\AbstractCache;
use MagmaCore\Cache\Storage\CacheStorageInterface;
use Throwable;

class Cache extends AbstractCache
{ 
    
    /**
     * Main class constructor
     *
     * @param string|null $cacheIdentifier
     * @param CacheStorageInterface|null $storage
     * @param array $options
     */
    public function __construct(?string $cacheIdentifier = null, CacheStorageInterface $storage, array $options = [])
    {
        parent::__construct($cacheIdentifier, $storage, $options);
    }

    /**
     * @inheritdoc
     *
     * @param string $key
     * @param mixed $value
     * @param [type] $ttl
     * @return void
     */
    public function set(string $key, $value, $ttl= null)
    {
        $this->ensureCacheEntryIdentifierIsvalid($key);
        try {
            $this->storage->setCache($key, serialize($value), $ttl);
        } catch(Throwable $throwable) {
            throw new CacheException('An exception was thrown in retrieving the key from the cache repository.', 0, $throwable);
        }

        return true;
    }

    public function get($key, $default= null)
    {
        $this->ensureCacheEntryIdentifierIsvalid($key);
        try {
            $data = $this->storage->getCache($key);
        } catch(Throwable $throwable) {
            throw new CacheException('An exception was thrown in retrieving the key from the cache backend.', 0, $throwable);
        }
        if ($data === false) {
            return $default;
        }
        return unserialize((string)$data);
    }

    public function delete($key) : bool
    {
        $this->ensureCacheEntryIdentifierIsvalid($key);
        try {
            $this->storage->removeCache($key);
        } catch(Throwable $throwable) {
            throw new CacheException('An exception was thrown in retrieving the key from the cache backend.', 0, $throwable);
        }
        return true;

    }

    public function clear() : bool
    {
        $this->storage->flush();
        return true;
    }

    public function getMultiple($keys, $default = null)
    {
        $result = [];
        foreach ($keys as $key) {
            $result[$key] = $this->get($key, $default);
        }

        return $result;
    }

    public function setMultiple($values, $ttl = null)
    {
        $all = true;
        foreach ($values as $key => $value) {
            $all = $this->set($key, $value, $ttl) && $all;
        }

        return $all;
    }

    public function deleteMultiple($keys) : bool
    {
        foreach ($keys as $key) {
            $this->delete($key);
        }

        return true;
    }

    public function has($key) : bool
    {
        $this->ensureCacheEntryIdentifierIsvalid($key);
        return $this->storage->hasCache($key);
    }

}
