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

use JetBrains\PhpStorm\Pure;
use MagmaCore\Cookie\CookieFacade;

class NativeSessionStorage extends AbstractSessionStorage
{

    /**
     * Main class constructor
     *
     * @param object $sessionEnvironment
     */
    public function __construct(Object $sessionEnvironment)
    {
        parent::__construct($sessionEnvironment);
    }

    /**
     * @inheritdoc
     *
     * @param string $key
     * @param mixed$value
     * @return void
     */
    public function setSession(string $key, mixed $value): void
    {
        $_SESSION[$key] = $value;
    }

    /**
     * @inheritdoc
     *
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function setArraySession(string $key, mixed $value): void
    {
        $_SESSION[$key][] = $value;
    }

    /**
     * @inheritdoc
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    #[Pure] public function getSession(string $key, mixed $default = null): mixed
    {
        if ($this->hasSession($key)) {
            return $_SESSION[$key];
        }
        return $default;
    }

    /**
     * @inheritdoc
     *
     * @param string $key
     * @return void
     */
    public function deleteSession(string $key): void
    {
        if ($this->hasSession($key)) {
            unset($_SESSION[$key]);
        }
    }

    /**
     * @inheritdoc
     *
     * @return void
     */
    public function invalidate(): void
    {
        $_SESSION = array();
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            if (class_exists(CookieFacade::class)) {
                $cookie = (@new CookieFacade(['name' => $this->getSessionName()]))->initialize();
                $cookie->delete();
            } else {
                setcookie($this->getSessionName(), '', time() - $params['lifetime'], $params['path'], $params['domain'], $params['secure'], $params['httponly']);
            }
        }
        session_unset();
        session_destroy();
    }

    /**
     * @inheritdoc
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function flush(string $key, mixed $default = null): mixed
    {
        if ($this->hasSession($key)) {
            $value = $_SESSION[$key];
            $this->deleteSession($key);
            return $value;
        }
        return $default;
    }

    /**
     * @inheritdoc
     *
     * @param string $key
     * @return boolean
     */
    public function hasSession(string $key): bool
    {
        return isset($_SESSION[$key]);
    }
}
