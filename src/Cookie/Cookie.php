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

namespace MagmaCore\Cookie;

use MagmaCore\Cookie\Store\CookieStoreInterface;

class Cookie implements CookieInterface
{

    /** @var CookieStoreInterface */
    protected CookieStoreInterface $cookieStore;

    /**
     * Protected class constructor as this class will be a singleton
     *
     * @param CookieStoreInterface $cookieStore
     */
    public function __construct(CookieStoreInterface $cookieStore)
    {
        $this->cookieStore = $cookieStore;
    }

    /**
     * @inheritdoc
     * 
     * @return bool
     */
    public function has(): bool
    {
        return $this->cookieStore->hasCookie();
    }

    /**
     * @inheritdoc
     * 
     * @param mixed $value
     * @return self
     */
    public function set(mixed $value): void
    {
        $this->cookieStore->setCookie($value);
    }

    /**
     * @inheritdoc
     * 
     * @return void
     */
    public function delete(): void
    {
        if ($this->has()) {
            $this->cookieStore->deleteCookie();
        }
    }

    /**
     * @inheritdoc
     * 
     * @return void
     */
    public function invalidate(): void
    {
        foreach ($_COOKIE as $name => $value) {
            if ($this->has()) {
                $this->cookieStore->deleteCookie($name);
            }
        }
    }
}
