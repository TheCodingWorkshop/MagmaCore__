<?php

declare(strict_types=1);

namespace MagmaCore\Cookie\Store;

interface CookieStoreInterface
{

    /**
     * @inheritdoc
     * 
     * @return bool
     */
    public function hasCookie() : bool;

    /**
     * @inheritdoc
     * 
     * @param mixed $value
     * @return void
     */
    public function setCookie($value) : void;

    /**
     * @inheritdoc
     * 
     * @param null|string $cookieName
     * @return void
     */
    public function deleteCookie(?string $cookieName = null) : void;

}