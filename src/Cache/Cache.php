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
    public function __construct(string|null $cacheIdentifier = null, CacheStorageInterface $storage, array $options = [])
    {
        parent::__construct($cacheIdentifier, $storage, $options);
    }

    /**
     * @inheritdoc
     *
     * @param string $key
     * @param mixed $value
     * @param int|null $ttl
     * @return bool
     */
    public function set(string $key, mixed $value, int|null $ttl = null): bool
    {
        $this->ensureCacheEntryIdentifierIsvalid($key);
        try {
            $this->storage->setCache($key, serialize($value), $ttl);
        } catch (Throwable $throwable) {
            throw new CacheException('An exception was thrown in retrieving the key from the cache repository.', 0, $throwable);
        }

        return true;
    }

    /**
     * @inheritDoc
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function get(string $key, mixed $default = null): mixed
    {
        $this->ensureCacheEntryIdentifierIsvalid($key);
        try {
            $data = $this->storage->getCache($key);
        } catch (Throwable $throwable) {
            throw new CacheException('An exception was thrown in retrieving the key from the cache backend.', 0, $throwable);
        }
        if ($data === false) {
            return $default;
        }
        return unserialize((string)$data);
    }

    /**
     * @inheritDoc
     *
     * @param string $key
     * @return boolean
     */
    public function delete(string $key): bool
    {
        $this->ensureCacheEntryIdentifierIsvalid($key);
        try {
            $this->storage->removeCache($key);
        } catch (Throwable $throwable) {
            throw new CacheException('An exception was thrown in retrieving the key from the cache backend.', 0, $throwable);
        }
        return true;
    }

    /**
     * @inheritDoc
     *
     * @return boolean
     */
    public function clear(): bool
    {
        $this->storage->flush();
        return true;
    }

    /**
     * @inheritDoc
     *
     * @param iterable $keys
     * @param mixed $default
     * @return iterable
     */
    public function getMultiple(iterable $keys, mixed $default = null): iterable
    {
        $result = [];
        foreach ($keys as $key) {
            $result[$key] = $this->get($key, $default);
        }

        return $result;
    }

    /**
     * @inheritDoc
     *
     * @param iterable $values
     * @param integer|null $ttl
     * @return bool
     */
    public function setMultiple(iterable $values, int|null $ttl = null): bool
    {
        $all = true;
        foreach ($values as $key => $value) {
            $all = $this->set($key, $value, $ttl) && $all;
        }

        return $all;
    }

    /**
     * @inheritdoc
     * @param iterable $keys
     * @return boolean
     */
    public function deleteMultiple(iterable $keys): bool
    {
        foreach ($keys as $key) {
            $this->delete($key);
        }

        return true;
    }

    /**
     * @inheritdoc
     *
     * @param string $key
     * @return boolean
     */
    public function has(string $key): bool
    {
        $this->ensureCacheEntryIdentifierIsvalid($key);
        return $this->storage->hasCache($key);
    }
}
