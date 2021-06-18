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

namespace MagmaCore\Session\Storage;

class PdoSessionStorage extends AbstractSessionStorage
{

    /**
     * Main class constructor
     *
     * @param Object $sessionEnvironment
     */
    public function __construct(Object $sessionEnvironment)
    {
        parent::__construct($sessionEnvironment);
    }

    public function setSession(string $key, mixed $value): void
    {
        // TODO: Implement setSession() method.
    }

    public function setArraySession(string $key, mixed $value): void
    {
        // TODO: Implement setArraySession() method.
    }

    public function getSession(string $key, mixed $default = null)
    {
        // TODO: Implement getSession() method.
    }

    public function deleteSession(string $key): void
    {
        // TODO: Implement deleteSession() method.
    }

    public function invalidate(): void
    {
        // TODO: Implement invalidate() method.
    }

    public function flush(string $key, mixed $default = null)
    {
        // TODO: Implement flush() method.
    }

    public function hasSession(string $key): bool
    {
        // TODO: Implement hasSession() method.
    }
}