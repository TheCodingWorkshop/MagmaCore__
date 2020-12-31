<?php

declare(strict_types=1);

namespace MagmaCore\Session\Storage;

interface SessionStorageInterface
{
    /**
     * session_name wrapper with explicit arguppment to set a session_name
     *
     * @param string $sessionName
     * @return void
     */
    public function setSessionName(string $sessionName) : void;

    /**
     * session_name wrapper returns the name of the session
     *
     * @return string
     */
    public function getSessionName() : string;

    /**
     * session_id wrapper with explicit argument to set a session_id
     *
     * @param $SessionID
     * @return void
     */
    public function setSessionID(string $sessionID) : void;

    /**
     * session_id wrapper which returns the current session id
     *
     * @return string
     */
    public function getSessionID();

    /**
     * sets a specific value to a specific key of the session
     *
     * @param string $key   The key of the item to store.
     * @param mixed  $value The value of the item to store. Must be serializable.
     * @return void
     * @throws BaseIna MUST be thrown if the $key string is not a legal value.
     */
    public function setSession(string $key, $value) : void;

    /**
     * Sets the specific value to a specific array key of the session
     *
     * @param string $key   The key of the item to store.
     * @param mixed  $value The value of the item to store. Must be serializable.
     * @return void
     * @throws BaseInvalidArgumentException MUST be thrown if the $key string is not a legal value.
     */
    public function setArraySession(string $key, $value) : void;

    /**
     * gets/returns the value of a specific key of the session
     *
     * @param string $key   The key of the item to store.
     * @param mixed  $default the default value to return if the request value can't be found
     * @return mixed
     * @throws BaseInvalidArgumentException MUST be thrown if the $key string is not a legal value.
     */
    public function getSession(string $key, $default = null);

    /**
     * Removes the value for the specified key from the session
     *
     * @param string $key   The key of the item that will be unset.
     * @return void
     * @throws BaseInvalidArgumentException
     */
    public function deleteSession(string $key) : void;

    /**
     * Destroy the session. Along with session cookies
     *
     * @return void
     */
    public function invalidate() : void;

    /**
     * Returns the requested value and remove the key from the session
     *
     * @param string $key - The key to retrieve and remove the value for.
     * @param mixed $default - The default value to return if the requested value cannot be found
     * @return mixed
     */
    public function flush(string $key, $default = null);

    /**
     * Determines whether an item is present in the session.
     *
     * @param string $key The session item key.
     * @return bool
     * @throws BaseInvalidArgumentException  MUST be thrown if the $key string is not a legal value.
     */
    public function hasSession(string $key) : bool;

}