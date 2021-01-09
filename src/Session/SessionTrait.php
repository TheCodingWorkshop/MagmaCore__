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

trait SessionTrait
{

    /**
     * method which should prevent our session being hijacked. This will return
     * false on new sessions or when a session is loaded by a host with a different
     * IP address or browser
     *
     * @return bool - true if session is valid or false otherwise
     */
    public function preventSessionHijack() : bool
    {
        if (!isset($_SESSION['IPaddress']) || !isset($_SESSION['userAgent'])) {
            return false;
        }
        if ($_SESSION['IPaddress'] != $_SERVER['REMOTE_ADDR']) {
            return  false;
        }
        if ($_SESSION['userAgent'] != $_SERVER['HTTP_USER_AGENT']) {
            return false;
        }

        return true;
    }

    /**
     * Validate the session by checking for the obsolete flag and to see if the
     * session has expires
     *
     * @since 1.0.0
     * @return bool
     */
    protected function validateSession() : bool
    {
        if (isset($_SESSION['OBSOLETE']) && !isset($_SESSION['EXPIRES'])) {
            return false;
        }
        if (isset($_SESSION['EXPIRES']) && $_SESSION['EXPIRES'] < time()) {
            return false;
        }
        return true;
    }

    /**
     * Regenerate the session ID. We can also optionally delete the old session ID
     *
     * @param int $sessionExpiration
     * @param bool $deleteOldSession
     * @return string
     */
    public function sessionRegeneration(int $sessionExpiration = 10, bool $deleteOldSession = false)
    {
        if(isset($_SESSION['OBSOLETE']) && $_SESSION['OBSOLETE'] == true) {
            return;
        }
        // Set current session to expire in 10 seconds
        $_SESSION['OBSOLETE'] = true;
        $_SESSION['EXPIRES'] = time() + $sessionExpiration;

        // Create new session without destroying the old one argument set to false to default
        session_regenerate_id($deleteOldSession);
        // Grab current session ID and close both sessions to allow other scripts to use them
        $newSessionID = $this->getSessionID();
        session_write_close();
        $this->setSessionID($newSessionID); // Set new session ID
        $this->startSession(); // then restart session again
        // Now we unset the obsolete and expiration values for the session we want to keep
        unset($_SESSION['OBSOLETE']);
        unset($_SESSION['EXPIRES']);

    }

    /**
     * Initialize the system session at the system entry point
     *
     * @param bool $useGlobal - Whether to use the global manager to retrieve the cache object
     * @return mixed
     * @throws Exception
     */
    public static function Session(bool $useGlobal = true)
    {
        $session = (new SessionFacade())->setSession();
        if (!$session) {
            throw new SessionException('Please enable session within the session.yaml configuration in order to use this Sessions.');
        } elseif ($useGlobal === true) {
            GlobalManager::set('session_global', $session);
        } else {
            return $session;
        }
    }

    /**
     * The session global is automatically set from the session facade class We 
     * can fetch the global variable and use this trait method in any class
     * which reference this trait
     * 
     * @return Object
     * @throws LogicException
     * @throws GlobalManagerException
     */
    public static function sessionFromGlobal()
    {
        /* Get the stored session Object */
        $storedSessionObject = GlobalManager::get('session_global');
        if (!$storedSessionObject) {
            throw new SessionException("No session object found within the global manager");
        }

        return $storedSessionObject;
    }

    /**
     * Store session data upon successful login
     *
     * @param integer $userID
     * @return void
     */
    public static function registerUserSession(int $userID)
    {
        $_SESSION['user_id'] = $userID;
    }


}