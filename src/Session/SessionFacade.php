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

namespace MagmaCore\Session;

use MagmaCore\Session\Exception\SessionInvalidArgumentException;
use MagmaCore\Session\GlobalManager\GlobalManager;
use MagmaCore\Session\SessionEnvironment;
use MagmaCore\Session\SessionFactory;

final class SessionFacade
{

    /** @var array */
    protected const SESSION_PARAMS = [
        'session_name', 
        'lifetime', 
        'path', 
        'domain', 
        'secure', 
        'httponly', 
        'gc_divisors', 
        'gc_maxlifetime', 
        'gc_probability'
    ];
    /** @var string */
    protected const __MAGMA_SESSION__ = '__magmacore_session__';

    /** @var string - a string which identifies the current session */
    protected string $sessionIdentifier;

    /** @var string - the namespace reference to the session storage type */
    protected string $storage;
    
    /** @var Object - the session environment object */
    protected Object $sessionEnvironment;

    /**
     * Singleton's constructor should not be public. However, it can't be
     * private either if we want to allow subclassing.
     * 
     * Main session facade class which pipes the properties to the method arguments. 
     * Which also defines the default session storage and session identifier.
     * 
     * @param array $sessionEnvironment - expecting a session.yaml configuration file
     * @param string $sessionIdentifier
     * @param null|string $storage - optional defaults to nativeSessionStorage
     * @return void
     */
    public function __construct(
        ?array $sessionEnvironment = null, 
        ?string $sessionIdentifier = null, 
        ?string $storage = null
    )
    {
        //$this->throwexceptionIfCookieParamsInvalid($sessionEnvironment);
        $sessionArray = array_merge((new SessionConfig())->baseConfiguration(), $sessionEnvironment);
        $this->sessionEnvironment = new SessionEnvironment($sessionArray);

        $this->sessionIdentifier = $sessionIdentifier ? $sessionIdentifier : self::__MAGMA_SESSION__;
        $this->storage = $storage ? $storage : \MagmaCore\Session\Storage\NativeSessionStorage::class;
    }

    /**
     * Ensure the cookie params matches the param we defined within this core class
     * else return invalid exception
     * 
     * @param array $sessionEnvironment
     * @return bool
     */
    private function throwExceptionIfCookieParamsInvalid(?array $sessionEnvironment = null) : bool
    {
        if ($sessionEnvironment !=null && is_array($sessionEnvironment)) {
            foreach ($sessionEnvironment as $cookieEnv) {
                if (!in_array($cookieEnv, self::SESSION_PARAMS)) {
                    throw new SessionInvalidArgumentException();
                }
            }
        }
        return false;
    }
    
    /**
     * Initialize the session component and return the session object. Also stored the
     * session object within the global manager. So session can be fetch throughout
     * the application by using the GlobalManager::get('session_global') to get
     * the session object
     * 
     * @return Object
     * @throws SessionUnexpectedValueException
     */
    public function setSession() : Object
    {
        $this->session = (new SessionFactory())->create($this->sessionIdentifier, $this->storage, $this->sessionEnvironment);
        GlobalManager::set('session_global', $this->session);
        return $this->session;

    }


}