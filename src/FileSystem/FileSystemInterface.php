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

interface FileSystemInterface
{

    public function file(): string;
    public function fileArray(): array;
    public function filter(callable $callback = null);
    public function has(): bool; /* alias of file_exists */
    public function readable(): bool;
    public function executable(): bool;
    public function writable(): bool;
    public function isFile(): bool;
    public function isDir(): bool;
    public function put(): void;
    public function get(): void;
    public function match(): static; /* glob */
    public function take(): bool; /* alias of copy */
    public function relocate(); /* rename() */
    public function delete(): void; /* unlink */
    public function size(): static;
    public function formatSize(): int|false;
    public function type(): string|false; /* file type */
    public function timeAccess(): static; /* fileatime */
    public function timeModified(): static; /* filemtime */
    public function date(string $formatOverride): string|false;


}