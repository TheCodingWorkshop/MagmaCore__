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

namespace MagmaCore\FileSystem;

use file_exists,
    file_put_contents,
    file_get_contents,
    file,
    is_file,
    is_dir,
    array_filter,
    is_readable,
    is_executable,
    is_writable,
    glob,
    copy;

class FileSystem
{

    /**
     * Main class constructor - accepts the file
     * 
     * Using constructor property promotion
     * @param mixed $file
     * @return void
     */
    public function __construct(private mixed $file)
    {
    }

    /**
     * Return the raw file
     *
     * @return string
     */
    public function file(): string
    {
        if (is_string($this->file))
            return $this->file;
    }

    /**
     * Reads entire file into an array
     *
     * @return static
     */
    public function fileArray(): static
    {
        return new static(file($this->file));
    }

    /**
     * Undocumented function
     *
     * @param Callable $callback
     * @return void
     */
    public function filter(callable $callback = null)
    {
        if ($callback) {
            return new static();
        }
        return new static(array_filter($this->file));
    }

    /**
     * Checks whether a file or directory exists
     *
     * @return boolean
     */
    public function has(): bool
    {
        return file_exists($this->file) ? true : false;
    }

    /**
     * Tells whether a file exists and is readable
     *
     * @return void
     */
    public function readable()
    {
        if ($this->has($this->file))
            return is_readable($this->file) ? true : false;
    }

    /**
     * Tells whether the filename is executable
     *
     * @return boolean
     */
    public function executable(): bool
    {
        if ($this->has($this->file))
            return is_executable($this->file) ? true : false;
    }

    /**
     * Tells whether the filename is writable
     *
     * @return bool
     */
    public function writable(): bool
    {
        if ($this->has($this->file))
            return is_writable($this->file) ? true : false;
    }

    /**
     * Tells whether the filename is a regular file
     *
     * @return boolean
     */
    public function isFile(): bool
    {
        if ($this->has($this->file))
            return is_file($this->file) ? true : false;
    }

    /**
     * Tells whether the filename is a directory
     *
     * @return boolean
     */
    public function isDir(): bool
    {
        if ($this->has($this->file))
            return is_dir($this->file) ? true : false;
    }

    /**
     * Write data to a file.
     * If filename does not exist, the file is created. Otherwise, the existing file 
     * is overwritten, unless the FILE_APPEND flag is set.
     *
     * @param mixed $data - The data to write. Can be either a string, an array or a stream resource.
     * @param integer $flag
     * @return void
     */
    public function put(mixed $data, int $flag = 0): void
    {
        if ($this->has($this->file)) {
            file_put_contents($this->file, $data, $flag);
        }
    }

    /**
     * Read an entire file into a string
     *
     * @return void
     */
    public function get(bool $useIncludePath = false, int $offset = 0, int|null $maxlength = null): void
    {
        if ($this->has($this->file)) {
            file_get_contents($this->file, $useIncludePath, NULL, $offset, $maxlength);
        }
    }

    /**
     * Find pathnames matching a pattern
     *
     * @param string $pattern
     * @param integer $flag
     * @return static
     */
    public function match(string $pattern, int $flag = 0): static
    {
        return new static(glob($pattern, $flag));
    }

    /**
     * take a copy of the file and place it within another destination. Destination
     * is the one and only required argument.
     *
     * @param string $destination
     * @return boolean
     */
    public function take(string $destination): bool
    {
        if ($this->has($this->file))
            return copy($this->file, $destination);
    }

    public function make()
    {
        // if (!$this->isDir($this->file)) {
        //     mkdir($this->file);
        // }
        // rmdir($this->file);

    }

    /**
     * Returns information about a file path
     *
     * @param integer $flag
     * @return string|array
     */
    public function info(int $flag): string|array
    {
        if ($this->has($this->file))
            return pathinfo($this->file, $flag);
    }

    /**
     * Delete/unlink a file from the system
     *
     * @return void
     */
    public function delete(): void
    {
        if ($this->has($this->file))
            unlink($this->file);
    }

    /**
     * Get the size of the file. returns static which mean we can chain on
     *
     * @return static
     */
    public function size(): static
    {
        if ($this->has($this->file))
            return new static(filesize($this->file));
    }

    public function formatSize(): int|false
    {
        return 0;
    }

    /**
     * Get and returns the current file type
     *
     * @return string|false
     */
    public function type(): string|false
    {
        if ($this->has($this->file))
            return filetype($this->file);
    }

    /**
     * Gets the last access time of the current file
     *
     * @return static
     */
    public function timeAccess(): static
    {
        if ($this->has($this->file)) {
            return new static(fileatime($this->file));
        }
    }

    /**
     * Gets the file modification time
     *
     * @return static
     */
    public function timeModified(): static
    {
        if ($this->has($this->file)) {
            return new static(filemtime($this->file));
        }
    }

    /**
     * Returns a human readable date format. Use in conjunction with the 
     * timeAccess and timeModified methods above
     *
     * @param string $formatOverride
     * @return string|false
     */
    public function humanDate(string $formatOverride = "F d Y H:i:s."): string|false
    {
        return date($formatOverride);
    }
}
