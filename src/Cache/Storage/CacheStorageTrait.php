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

use Exception;

trait CacheStorageTrait
{

    /**
     * Try to remove a file and make sure it is not locked.
     *
     * @param string $entryIdentifier
     * @return bool True if the file was removed successfully or false otherwise
     */
    protected function tryRemoveWithLock(string $entryIdentifier): bool
    {
        if (strncasecmp(PHP_OS, 'WIN', 3) == 0) {
            // On Windows, unlinking a locked/opened file will not work, so we just attempt the delete straight away.
            // In the worst case, the unlink will just fail due to concurrent access and the caller needs to deal with that.
            return unlink($entryIdentifier);
        }
        $file = fopen($entryIdentifier, 'rb');
        if ($file === false) {
            return false;
        }
        $result = false;
        if (flock($file, LOCK_EX) !== false) {
            $result = unlink($entryIdentifier);
            flock($file, LOCK_UN);
        }
        fclose($file);

        return $result;
    }

    /**
     * Reads the cache data from the given cache file, using locking.
     *
     * @param string $cacheEntryPathAndFilename
     * @param int|null $offset
     * @param int|null $maxlen
     * @return boolean|string The contents of the cache file or false on error
     */
    protected function readCacheFile(string $cacheEntryPathAndFilename, int $offset = null, int $maxlen = null): bool|string
    {
        //file_get_contents($cacheEntryPathAndFilename);
        for ($i = 0; $i < 3; $i++) {
            $data = false;
            try {
                $file = fopen($cacheEntryPathAndFilename, 'rb');
                if ($file === false) {
                    continue;
                }
                if (flock($file, LOCK_SH) !== false) {
                    if ($offset !== null) {
                        fseek($file, $offset);
                    }
                    $data = fread($file, $maxlen !== null ? $maxlen : filesize($cacheEntryPathAndFilename) - (int)$offset);
                    flock($file, LOCK_UN);
                }
                fclose($file);
            } catch (Exception) {
            }
            if ($data !== false) {
                return $data;
            }
            usleep(rand(10, 500));
        }

        return false;
    }

    /**
     * Writes the cache data into the given cache file, using locking.
     *
     * @param string $cacheEntryPathAndFilename
     * @param string $value
     * @return bool Return value of file_put_contents
     */
    protected function writeCacheFile(string $cacheEntryPathAndFilename, string $value): bool
    {
        //file_put_contents($cacheEntryPathAndFilename, $value, LOCK_EX);
        for ($i = 0; $i < 3; $i++) {
            $result = false;
            try {
                $file = fopen($cacheEntryPathAndFilename, 'wb');
                if ($file === false) {
                    continue;
                }
                if (flock($file, LOCK_EX) !== false) {
                    if (fwrite($file, $value) === false) {
                        $result = fwrite($file, $value) === false;
                    } else {
                        $result = true;
                    }
                    flock($file, LOCK_UN);
                }
                fclose($file);
            } catch (Exception) {
            }
            if ($result !== false) {
                clearstatcache(true, $cacheEntryPathAndFilename);
                return true;
            }
            usleep(rand(10, 500));
        }

        return false;
    }
}
