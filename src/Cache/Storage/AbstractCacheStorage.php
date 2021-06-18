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

use JetBrains\PhpStorm\Pure;
use MagmaCore\Cache\Exception\CacheInvalidArgumentException;
use MagmaCore\Cache\Exception\CacheException;
use DirectoryIterator;

abstract class AbstractCacheStorage implements IterableStorageInterface
{

    use CacheStorageTrait;

    /** @var Object */
    protected Object $envConfigurations;
    /** @var array */
    protected array $options;

    /**
     * Directory where the files are stored.
     *
     * @var string
     */
    protected string $cacheDirectory = '';

    /**
     * A file extension to use for each cache entry.
     *
     * @var string
     */
    protected string $cacheEntryFileExtension = '';

    /**
     * @var string[]
     */
    protected array $cacheEntryIdentifiers = [];

    /**
     * @var DirectoryIterator
     */
    protected DirectoryIterator $cacheFilesIterator;

    /**
     * Overrides the base directory for this cache,
     * the effective directory will be a subdirectory of this.
     * If not given this will be determined by the EnvironmentConfiguration
     *
     * @var string
     */
    protected string $baseDirectory = '';

    /**
     * Undocumented function
     *
     * @param Object $envConfigurations
     * @param array $options
     */
    public function __construct(Object $envConfigurations, array $options)
    {
        $this->envConfigurations = $envConfigurations;
        $this->options = $options;
    }

    /**
     * Sets the directory where the cache files are stored
     * @param string $cacheDirectory
     * @return void Full path of the cache directory
     */
    public function setCacheDirectory(string $cacheDirectory): void
    {
        $this->cacheDirectory = rtrim($cacheDirectory, '/') . '/';
    }

    /**
     * Returns the directory where the cache files are stored
     * @return string Full path of the cache directory
     */
    public function getCacheDirectory(): string
    {
        return $this->cacheDirectory;
    }

    /**
     * @return string
     */
    public function getBaseDirectory(): string
    {
        return $this->baseDirectory;
    }

    /**
     * @param string $baseDirectory
     */
    public function setBaseDirectory(string $baseDirectory): void
    {
        $this->baseDirectory = $baseDirectory;
    }

    /**
     * Returns the cache identifier filename in its current directory path
     * @param string $entryIdentifier
     * @return string
     */
    protected function cacheEntryPathAndFilename(string $entryIdentifier): string
    {
        return $this->cacheDirectory . $entryIdentifier . $this->cacheEntryFileExtension;
    }

    /**
     * Tries to find the cache entry for the specified Identifier.
     * @param string $entryIdentifier The cache entry Identifier
     * @return false|string[] The filenames (including path) as an array if one or more entries could be found, otherwise false
     */
    #[Pure] protected function findCacheFilesByIdentifier(string $entryIdentifier): array|bool
    {
        $cacheEntryPathAndFilename = $this->cacheEntryPathAndFilename($entryIdentifier);
        return (file_exists($cacheEntryPathAndFilename) ? [$cacheEntryPathAndFilename] : false);
    }

    /**
     * Checks if the given cache entry files are still valid or if their
     * lifetime has exceeded.
     *
     * @param string $cacheEntryPathAndFilename
     * @return boolean
     * @api
     */
    protected function isCacheFileExpired(string $cacheEntryPathAndFilename): bool
    {
        return (file_exists($cacheEntryPathAndFilename) === false);
    }

    /**
     * @param string $entryIdentifier
     * @param bool $all - whether th validate all or only basename
     * @return void
     * @throws CacheInvalidArgumentException
     */
    protected function isCacheValidated(string $entryIdentifier, bool $all = true): void
    {
        if ($entryIdentifier !== basename($entryIdentifier)) {
            throw new CacheInvalidArgumentException('The specified cache identifier must not contain a path segment.', 1334756960);
        }
        if ($all) {
            if ($entryIdentifier === '') {
                throw new CacheInvalidArgumentException('The specified cache identifier must not be empty.', 1334756961);
            }
        }
    }

    /**
     * @throws CacheException
     */
    protected function verifyCacheDirectory(): void
    {
        if (!is_dir($this->cacheDirectory) && !is_link($this->cacheDirectory)) {
            throw new CacheException('The cache directory "' . $this->cacheDirectory . '" does not exist.', 1203965199);
        }
        if (!is_writable($this->cacheDirectory)) {
            throw new CacheException('The cache directory "' . $this->cacheDirectory . '" is not writable.', 1203965200);
        }
    }

    /**
     * Returns the data of the current cache entry pointed to by the cache entry
     * iterator.
     *
     * @return bool|string
     * @api
     */
    public function current(): bool|string
    {
        if ($this->cacheFilesIterator === null) {
            $this->rewind();
        }

        $pathAndFilename = $this->cacheFilesIterator->getPathname();
        return $this->readCacheFile($pathAndFilename);
    }

    /**
     * Move forward to the next cache entry
     *
     * @return void
     * @api
     */
    public function next()
    {
        if ($this->cacheFilesIterator === null) {
            $this->rewind();
        }
        $this->cacheFilesIterator->next();
        while ($this->cacheFilesIterator->isDot() && $this->cacheFilesIterator->valid()) {
            $this->cacheFilesIterator->next();
        }
    }

    /**
     * Returns the identifier of the current cache entry pointed to by the cache
     * entry iterator.
     *
     * @return string
     * @api
     */
    public function key(): string
    {
        if ($this->cacheFilesIterator === null) {
            $this->rewind();
        }
        return $this->cacheFilesIterator->getBasename($this->cacheEntryFileExtension);
    }

    /**
     * Checks if the current position of the cache entry iterator is valid
     *
     * @return boolean true if the current position is valid, otherwise false
     * @api
     */
    public function valid(): bool
    {
        if ($this->cacheFilesIterator === null) {
            $this->rewind();
        }
        return $this->cacheFilesIterator->valid();
    }

    /**
     * Rewinds the cache entry iterator to the first element
     *
     * @return void
     * @api
     */
    public function rewind()
    {
        if ($this->cacheFilesIterator === null) {
            $this->cacheFilesIterator = new DirectoryIterator($this->cacheDirectory);
        }
        $this->cacheFilesIterator->rewind();
        while (substr($this->cacheFilesIterator->getFilename(), 0, 1) === '.' && $this->cacheFilesIterator->valid()) {
            $this->cacheFilesIterator->next();
        }
    }
}
