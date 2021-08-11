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

namespace MagmaCore\Session;

use MagmaCore\Session\Exception\SessionException;
use MagmaCore\Session\Exception\SessionInvalidArgumentException;
use MagmaCore\Session\Storage\SessionStorageInterface;
use Throwable;

class Session implements SessionInterface
{

    /** @var SessionStorageInterface|null */
    protected ?SessionStorageInterface $storage;

    /** @var string */
    protected string $sessionIdentifier;

    /** @var string */
    protected const SESSION_PATTERN = '/^[a-zA-Z0-9_\.]{1,64}$/';

    /**
     * Class constructor
     *
     * @param string $sessionIdentifier
     * @param SessionStorageInterface|null $storage
     */
    public function __construct(string $sessionIdentifier, SessionStorageInterface $storage = null)
    {
        if ($this->isSessionKeyValid($sessionIdentifier) === false) {
            throw new SessionInvalidArgumentException($sessionIdentifier . ' is not a valid session name');
        }

        $this->sessionIdentifier = $sessionIdentifier;
        $this->storage = $storage;
    }

    /**
     * Return the storage object
     * @return SessionStorageInterface|null
     */
    public function getStorage(): ?SessionStorageInterface
    {
        return $this->storage;
    }

    /**
     * @param string $key
     * @param mixed $value
     * @return void
     * @throws SessionException
     */
    public function set(string $key, mixed $value): void
    {
        $this->ensureSessionKeyIsValid($key);
        try {
            $this->storage->SetSession($key, $value);
        } catch (Throwable $throwable) {
            throw new SessionException('An exception was thrown in retrieving the key from the session storage. ' . $throwable);
        }
    }

    /**
     * @param string $key
     * @param mixed $value
     * @return void
     * @throws SessionException
     */
    public function setArray(string $key, mixed $value): void
    {
        $this->ensureSessionKeyIsValid($key);
        try {
            $this->storage->setArraySession($key, $value);
        } catch (Throwable|SessionException $throwable) {
            throw new SessionException('An exception was thrown in retrieving the key from the session storage. ' . $throwable);
        }
    }

    /**
     * @param string $key
     * @param mixed|null $default
     * @return void
     * @throws SessionException|Throwable
     */
    public function get(string $key, mixed $default = null): mixed
    {
        try {
            return $this->storage->getSession($key, $default);
        } catch (Throwable $throwable) {
            throw new SessionException('An exception was thrown in retrieving the key from the session storage. ' . $throwable);
        }
    }

    /**
     * @param string $key
     * @return boolean
     * @throws SessionException|Throwable
     */
    public function delete(string $key): bool
    {
        $this->ensureSessionKeyIsValid($key);
        try {
            $this->storage->deleteSession($key);
        } catch (Throwable $throwable) {
            throw $throwable;
        }
        return true;
    }

    /**
     * @return void
     */
    public function invalidate(): void
    {
        $this->storage->invalidate();
    }

    /**
     * @param string $key
     * @param [type] $value
     * @return mixed
     * @throws SessionException|Throwable
     */
    public function flush(string $key, $value = null): mixed
    {
        $this->ensureSessionKeyIsValid($key);
        try {
            return $this->storage->flush($key, $value);
        } catch (Throwable $throwable) {
            throw $throwable;
        }
    }

    /**
     * @param string $key
     * @return boolean
     * @throws SessionInvalidArgumentException
     */
    public function has(string $key): bool
    {
        $this->ensureSessionKeyIsValid($key);
        return $this->storage->hasSession($key);
    }

    /**
     * Checks whether our session key is valid according the defined regular expression
     *
     * @param string $key
     * @return boolean
     */
    protected function isSessionKeyValid(string $key): bool
    {
        return (preg_match(self::SESSION_PATTERN, $key) === 1);
    }

    /**
     * Checks whether we have session key 
     *
     * @param string $key
     * @return void
     */
    protected function ensureSessionKeyIsvalid(string $key): void
    {
        if ($this->isSessionKeyValid($key) === false) {
            throw new SessionInvalidArgumentException($key . ' is not a valid session key');
        }
    }
}
