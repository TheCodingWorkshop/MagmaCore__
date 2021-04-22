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

use MagmaCore\Cookie\CookieFactory;
use MagmaCore\Cookie\CookieEnvironment;
use MagmaCore\Cookie\Store\NativeCookieStore;
use MagmaCore\Cookie\CookieConfig;

class CookieFacade
{

    /** @var string - the namespace reference to the cookie store type */
    protected string $store;
    /** @var object - the cookie environment object */
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
        $this->cookieEnvironment = new CookieEnvironment($cookieArray);
        $this->store = ($store != null) ? $store : NativeCookieStore::class;
    }

    /**
     * Create an instance of the cookie factory and inject all the required
     * dependencies ie. the cookie store object and the cookie environment 
     * configuration.
     *
     * @return object
     */
    public function initialize(): Object
    {
        return (new CookieFactory())->create($this->store, $this->cookieEnvironment);
    }
}
