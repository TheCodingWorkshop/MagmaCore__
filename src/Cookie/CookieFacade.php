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

use MagmaCore\Cookie\Exception\CookieInvalidArgumentException;
use MagmaCore\Cookie\CookieFactory;
use MagmaCore\Cookie\CookieEnvironment;
use MagmaCore\Cookie\Store\NativeCookieStore;
use MagmaCore\Cookie\CookieConfig;

class CookieFacade
{

    /** @var array */
    protected const COOKIE_PARAMS = ['name', 'expires', 'path', 'domain', 'secure', 'httponly'];
    /** @var string */
    protected const __MAGMA_COOKIE__ = '__magmacore_cookie__';
    /** @var string - the namespace reference to the cookie store type */
    protected string $store;
    /** @var Object - the cookie environment object */
    protected Object $cookieEnvironment;

    /**
     * Main cookie facade class which pipes the properties to the method arguments. 
     * Which also defines the default cookie store.
     * 
     * @param null|array $cookieEnvironmentArray - expecting a cookie.yaml configuration file
     * @param null|string $store - optional defaults to nativeCookieStore
     * @return void
     */
    public function __construct(?array $cookieEnvironmentArray = null, ?string $store = null)
    {
        $cookieArray = array_merge((new CookieConfig())->baseConfig(), $cookieEnvironmentArray);
        //$this->throwExceptionIfCookieParamsInvalid($cookieArray);
        $this->cookieEnvironment = new CookieEnvironment($cookieArray);
        $this->store = ($store != null) ? $store : NativeCookieStore::class;
    }

    /**
     * Ensure the cookie params matches the param we defined within this core class
     * else return invalid exception
     * 
     * @param array $cookieEnvironment#
     * @return bool
     */
    private function throwExceptionIfCookieParamsInvalid(?array $cookieEnvironment = null): bool
    {
        if ($cookieEnvironment != null && is_array($cookieEnvironment)) {
            foreach ($cookieEnvironment as $cookieEnv) {
                if (!in_array($cookieEnv, self::COOKIE_PARAMS)) {
                    throw new CookieInvalidArgumentException('Invalid cookie parameters');
                }
            }
        }
        return false;
    }

    /**
     * Create an instance of the cookie factory and inject all the required
     * dependencies ie. the cookie store object and the cookie environment 
     * configuration.
     *
     * @return Object
     */
    public function initialize(): Object
    {
        return (new CookieFactory())->create($this->store, $this->cookieEnvironment);
    }
}
