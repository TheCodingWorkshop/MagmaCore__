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

use MagmaCore\Cookie\Exception\CookieUnexpectedValueException;
use MagmaCore\Cookie\Store\CookieStoreInterface;

class CookieFactory
{

    /** @return void */
    public function __construct()
    {
    }

    /**
     * Cookie factory which create the cookie object and instantiate the chosen
     * cookie store object which defaults to nativeCookieStore. This store object accepts
     * the cookie environment object as the only argument.
     *
     * @param string|null $cookieStore
     * @param CookieEnvironment $cookieEnvironment
     * @return CookieInterface
     */
    public function create(?string $cookieStore, CookieEnvironment $cookieEnvironment): CookieInterface
    {
        $cookieStoreObject = new $cookieStore($cookieEnvironment);
        if (!$cookieStoreObject instanceof CookieStoreInterface) {
            throw new CookieUnexpectedValueException($cookieStore . 'is not a valid cookie store object.');
        }

        return new Cookie($cookieStoreObject);
    }
}
