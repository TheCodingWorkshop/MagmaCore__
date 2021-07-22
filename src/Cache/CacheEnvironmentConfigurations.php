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

class CacheEnvironmentConfigurations
{

    /** @var string|null */
    protected ?string $cacheIdentifier;
    /** @var string|null */
    protected ?string $fileCacheBasePath;
    /** @var int */
    protected int $maximumPathLength;

    /**
     * Undocumented function
     *
     * @param string|null $cacheIdentifier
     * @param string|null $fileCacheBasePath
     * @param integer $maximumPathLength
     */
    public function __construct(?string $cacheIdentifier, ?string $fileCacheBasePath, int $maximumPathLength = PHP_MAXPATHLEN)
    {
        $this->cacheIdentifier = $cacheIdentifier;
        $this->fileCacheBasePath = $fileCacheBasePath;
        $this->maximumPathLength = $maximumPathLength;
    }

    /**
     * The maximum length of filenames (including path) supported by this build 
     * of PHP. Available since PHP
     *
     * @return integer
     */
    public function getMaximumPathLength(): int
    {
        return $this->maximumPathLength;
    }

    /**
     * Undocumented function
     *
     * @return string
     */
    public function getFileCacheBasePath(): string
    {
        return $this->fileCacheBasePath;
    }

    /**
     * Undocumented function
     *
     * @return string
     */
    public function getCacheIdentifier(): string
    {
        return $this->cacheIdentifier;
    }
}
