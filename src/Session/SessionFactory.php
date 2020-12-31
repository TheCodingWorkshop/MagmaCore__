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

use MagmaCore\Session\Exception\SessionUnexpectedValueException;
use MagmaCore\Session\Storage\SessionStorageInterface;
use MagmaCore\Session\SessionInterface;
use MagmaCore\Session\SessionEnvironment;

class SessionFactory
{

    /** @return void */
    public function __construct()
    { }

    /**
     * Session factory which create the session object and instantiate the choosen
     * session storage which defaults to nativeSessionStorage. This storage object accepts
     * the session environment object as the only argument.
     * 
     * @param string $sessionIdentifier
     * @param string $storage
     * @param SessionEnvironment $SessionEnvironment
     * @return SessionInterface
     * @throws BaseUnexpectedValueException
     */
    public function create(
        string $sessionIdentifier, 
        string $storage, 
        SessionEnvironment $sessionEnvironment
    ) : SessionInterface
    {
        $storageObject = new $storage($sessionEnvironment);
        if (!$storageObject instanceof SessionStorageInterface) {
            throw new SessionUnexpectedValueException(
                $storage . ' is not a valid session storage object.'
            );
        }

        return new Session($sessionIdentifier, $storageObject);
    }

}