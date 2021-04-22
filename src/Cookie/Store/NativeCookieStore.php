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

namespace MagmaCore\Cookie\Store;

class NativeCookieStore extends AbstractCookieStore
{

    /**
     * Main class constructor
     *
     * @param Object $cookieEnvironment
     */
    public function __construct(Object $cookieEnvironment)
    {
        parent::__construct($cookieEnvironment);
    }

    /**
     * @inheritdoc
     * 
     * @param mixed $value
     * @param null|array $attributes
     * @return self
     */
    public function hasCookie(): bool
    {
        return isset($_COOKIE[$this->cookieEnvironment->getCookieName()]);
    }

    /**
     * @inheritdoc
     * @param mixed $value
     * @return self
     */
    public function setCookie(mixed $value): void
    {
        setcookie($this->cookieEnvironment->getCookieName(), $value, $this->cookieEnvironment->getExpiration(), $this->cookieEnvironment->getPath(), $this->cookieEnvironment->getDomain(), $this->cookieEnvironment->isSecure(), $this->cookieEnvironment->isHttpOnly());
    }

    /**
     * @inheritdoc
     * @return self
     */
    public function deleteCookie(string|null $cookieName = null): void
    {
        setcookie(($cookieName != null) ? $cookieName : $this->cookieEnvironment->getCookieName(), '', (time() - 3600), $this->cookieEnvironment->getPath(), $this->cookieEnvironment->getDomain(), $this->cookieEnvironment->isSecure(), $this->cookieEnvironment->isHttpOnly());
    }
}
