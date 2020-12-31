<?php

declare(strict_types=1);

namespace MagmaCore\Cookie\Store;

use MagmaCore\Cookie\Store\CookieStoreInterface;

abstract class AbstractCookieStore implements CookieStoreInterface
{

    /** @var Object */
    protected Object $cookieEnvironment;

    /**
     * Main class constructor
     *
     * @param Object $cookieEnvironment
     */
    public function __construct(Object $cookieEnvironment)
    {
        $this->cookieEnvironment = $cookieEnvironment;
    }

}