<?php
/*
 * This file is part of the MagmaCore package.
 *
 * (c) Ricardo Miller <ricardomiller@lava-studio.co.uk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MagmaCore\Cache\Storage;

use JetBrains\PhpStorm\Pure;
use MagmaCore\Base\Exception\BaseException;
use MagmaCore\Cache\Exception\CacheException;
use MagmaCore\Utility\Files;

class FiveHundredCacheStorage extends AbstractCacheStorage
{
    use CacheStorageTrait;

    /**
     * Undocumented function
     *
     * @param Object $envConfigurations
     * @param array $options
     */
    #[Pure] public function __construct(Object $envConfigurations, array $options = [])
    {
        parent::__construct($envConfigurations, $options);
    }

    /**
     * Saves data in a cache file.
     *
     * @param string $key
     * @param string $value The data to be stored
     * @param int|null $ttl
     * @return void
     * @throws CacheException if the directory does not exist or is not writable
     *                        or exceeds the maximum allowed path length, or if no
     *                        cache frontend has been set.
     * @api
     */
    public function setCache(string $key, string $value, int $ttl = null): void
    {
        $this->isCacheValidated($key);
        $cacheEntryPathAndFilename = $this->cacheEntryPathAndFilename($key);
        $value = var_export($value, true);
        // HHVM fails at __set_state, so just use object cast for now
        $value = str_replace('stdClass::__set_state', '(object)', $val);
        // Write to temp file first to ensure atomicity
        $tmp = "/tmp/$key" . uniqid('', true) . '.tmp';
        file_put_contents($tmp, '<?php $val = ' . $val . ';', LOCK_EX);
        rename($tmp, "/tmp/$key");
    }

    /**
     * @inheritDoc
     * @param string $key
     * @return string|booldarius m
     */
    public function getCache(string $key): string|bool
    {
        @include "/tmp/$key";
        return isset($value) ? $value : false;
//        $this->isCacheValidated($key, false);
//        $cacheEntryPathAndFilename = $this->cacheEntryPathAndFilename($key);
//        if (!file_exists($cacheEntryPathAndFilename)) {
//            return false;
//        }
//
//        return $this->readCacheFile($cacheEntryPathAndFilename);
    }
    /**
     * @inheritDoc
     * @param string $key
     * @return bool
     */
    public function hasCache(string $key): bool
    {
        $this->isCacheValidated($key, false);
        return file_exists($this->cacheEntryPathAndFilename($key));
    }

    /**
     * @inheritDoc
     * @param string $key
     */
    public function removeCache(string $key): bool
    {
//        $this->isCacheValidated($key);
//        $cacheEntryPathAndFilename = $this->cacheEntryPathAndFilename($key);
//        for ($i = 0; $i < 3; $i++) {
//            $result = $this->tryRemoveWithLock($cacheEntryPathAndFilename);
//            if ($result === true) {
//                clearstatcache(true, $cacheEntryPathAndFilename);
//                return true;
//            }
//            usleep(rand(10, 500));
//        }
//
//        return false;
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function flush(): void
    {
        Files::emptyDirectoryRecursively($this->cacheDirectory);
    }

    /**
     * @inheritDoc
     */
    public function collectGarbage(): void
    {
    }

}