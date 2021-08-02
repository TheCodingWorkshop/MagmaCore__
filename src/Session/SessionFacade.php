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

use MagmaCore\Session\Exception\SessionException;
use MagmaCore\Session\GlobalManager\GlobalManager;

final class SessionFacade
{

    /** @var string|null - a string which identifies the current session */
    protected ?string $sessionIdentifier;

    /** @var string|null - the namespace reference to the session storage type */
    protected ?string $storage;

    /** @var Object - the session environment object */
    protected Object $sessionEnvironment;

    /**
     * Main session facade class which pipes the properties to the method arguments.
     *
     * @param array|null $sessionEnvironment - expecting a session.yaml configuration file
     * @param string|null $sessionIdentifier
     * @param null|string $storage - optional defaults to nativeSessionStorage
     */
    public function __construct(
        array $sessionEnvironment = null,
        string|null $sessionIdentifier = null,
        string|null $storage = null
    ) {
        /** Defaults are set from the BaseApplication class */
        $this->sessionEnvironment = new SessionEnvironment($sessionEnvironment);
        $this->sessionIdentifier = $sessionIdentifier;
        $this->storage = $storage;
    }

    /**
     * Initialize the session component and return the session object. Also stored the
     * session object within the global manager. So session can be fetch throughout
     * the application by using the GlobalManager::get('session_global') to get
     * the session object
     *
     * @return object
     * @throws GlobalManager\GlobalManagerException
     */
    public function setSession(): Object
    {
        try {
            return (new SessionFactory())->create($this->sessionIdentifier, $this->storage, $this->sessionEnvironment);
        } catch(SessionException $e) {
            //throw new SessionException($e->getMessage());
        }
    }
}
