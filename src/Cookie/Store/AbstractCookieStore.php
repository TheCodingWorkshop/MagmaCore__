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

abstract class AbstractCookieStore implements CookieStoreInterface
{

    /** @var object */
    protected Object $cookieEnvironment;

    /**
     * Main class constructor
     *
     * @param object $cookieEnvironment
     */
    public function __construct(Object $cookieEnvironment)
    {
        $this->cookieEnvironment = $cookieEnvironment;
    }
}
