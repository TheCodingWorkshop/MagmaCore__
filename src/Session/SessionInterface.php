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

interface SessionInterface
{

    /**
     * sets a specific value to a specific key of the session
     *
     * @param string $key   The key of the item to store.
     * @param mixed  $value The value of the item to store. Must be serializable.
     * @return void
     * @throws SessionInvalidArgumentException MUST be thrown if the $key string is not a legal value.
     */
    public function set(string $key, $value) : void;

    /**
     * Sets the specific value to a specific array key of the session
     *
     * @param string $key   The key of the item to store.
     * @param mixed  $value The value of the item to store. Must be serializable.
     * @return void
     * @throws SessionInvalidArgumentException MUST be thrown if the $key string is not a legal value.
     */
    public function setArray(string $key, $value) : void;

    /**
     * gets/returns the value of a specific key of the session
     *
     * @param string $key   The key of the item to store.
     * @param mixed  $default the default value to return if the request value can't be found
     * @return mixed
     * @throws SessionInvalidArgumentException MUST be thrown if the $key string is not a legal value.
     */
    public function get(string $key, $default = null);

    /**
     * Removes the value for the specified key from the session
     *
     * @param string $key   The key of the item that will be unset.
     * @return bool
     * @throws SessionInvalidArgumentException
     */
    public function delete(string $key) : bool;

    /**
     * Destroy the session. Along with session cookies
     *
     * @return void
     */
    public function invalidate() : void;

    /**
     * Returns the requested value and remove it from the session
     *
     * @param string $key - The key to retrieve and remove the value for.
     * @param mixed $default - The default value to return if the requested value cannot be found
     * @return mixed
     */
    public function flush(string $key, $value = null);

    /**
     * Determines whether an item is present in the session.
     *
     * @param string $key The session item key.
     * @return bool
     * @throws SessionInvalidArgumentException  MUST be thrown if the $key string is not a legal value.
     */
    public function has(string $key) : bool;


}