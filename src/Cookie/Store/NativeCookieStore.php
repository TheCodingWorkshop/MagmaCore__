<?php

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
    public function hasCookie() : bool
    {
        return isset($_COOKIE[$this->cookieEnvironment->getCookieName()]);
    }

    /**
     * @inheritdoc
     * @param mixed $value
     * @return self
     */
    public function setCookie($value) : void
    {
        setcookie($this->cookieEnvironment->getCookieName(), $value, $this->cookieEnvironment->getExpiration(), $this->cookieEnvironment->getPath(), $this->cookieEnvironment->getDomain(), $this->cookieEnvironment->isSecure(), $this->cookieEnvironment->isHttpOnly());
    }

    /**
     * @inheritdoc
     * @return self
     */
    public function deleteCookie(?string $cookieName = null) : void
    {
        setcookie(($cookieName !=null) ? $cookieName : $this->cookieEnvironment->getCookieName(), '', (time() - 3600), $this->cookieEnvironment->getPath(), $this->cookieEnvironment->getDomain(), $this->cookieEnvironment->isSecure(), $this->cookieEnvironment->isHttpOnly());

    }

}