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

namespace MagmaCore\Auth\Contracts;

interface RememberedLoginInterface
{

    /**
     * Find a remembered login model by the token
     *
     * @param string $token The remembered login token
     * @return object or null
     * @throws Throwable
     */
    public function findByToken(string $token) : Object;

    /**
     * See if the remember token has expired or not, based on the current system time
     *
     * @param string $expires
     * @return boolean True if the token has expired, false otherwise
     */
    public function hasExpired(string $expires) : bool;

    /**
     * Delete this model. Simple by calling the delete method from the crud class.
     * we've already set a global key parameter at the very top of this class so all
     * we will need is the bound parameter for that key which we can pass as an
     * argument to the delete method
     *
     * @param string $tokenHash
     * @return bool
     * @throws Throwable
     */
    public function destroy(string $tokenHash) : bool;

    /**
     * Get the user model associated with this remembered login
     *
     * @param int $userID - fetched the token user
     * @return object
     */
    public function getUser(int $userID) : Object;

    /**
     * Remember the login by inserting a new unique token into the remembered_logins table
     * for this user record
     *
     * @param int $userID - the ID of the user to remember
     * @return array True if the login was remembered successfully, false otherwise
     * @throws Exception
     */
    public function rememberedLogin(int $userID) : array;


}
