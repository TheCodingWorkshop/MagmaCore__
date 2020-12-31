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

use MagmaCore\Cache\Exception\CacheInvalidArgumentException;
use MagmaCore\Cache\Exception\CacheException;
use MagmaCore\Cache\CacheInterface;

Abstract class AbstractCache implements CacheInterface
{ 

    /** @var string regular exppression - ensure cache name is of correct values */
    const PATTERN_ENTRYIDENTIFIER = '/^[a-zA-Z0-9_\.]{1,64}$/';
    /** @var Object */
    protected ?Object $storage = null;
    /** @var string */
    protected ?string $cacheIdentifier = null;
    /** @var array */
    protected array $options = [];

    /**
     * Main abstract parent class. Which pipes all the properties to their constructor 
     * arguments
     *
     * @param string $cacheIdentifier
     * @param Object $storage
     * @param array $options
     * @return void
     */
    public function __construct(?string $cacheIdentifier = null, ?Object $storage = null, array $options = [])
    {
        $this->$cacheIdentifier = $cacheIdentifier;
        if (!empty($storage) && $storage !=null) {
            $this->storage = $storage;
        }
        if ($options)
            $this->options = $options;

    }

    /**
     * Check cache identifier matches the regular expression if not throw an
     * exception. cache name can only contains letter, number, underscore and 
     * should have a minimum or 1 and a maximum of 64 characters. No special 
     * characters are allowed.
     *
     * @param string $key
     * @return boolean
     */
    protected function isCacheEntryIdentifiervalid(string $key): bool
    {
        return (preg_match(self::PATTERN_ENTRYIDENTIFIER, $key) === 1);
    }

    /**
     * throw a n cacheInvalidArgumentException is the cache key is invalid
     *
     * @param string $key
     * @return void
     * @throws CacheInvalidArgumentException
     */
    protected function ensureCacheEntryIdentifierIsvalid(string $key): void
    {
        if ($this->isCacheEntryIdentifiervalid($key) === false) {
            throw new CacheInvalidArgumentException('"' . $key . '" is not a valid cache key.', 0);
        }
    }


}
