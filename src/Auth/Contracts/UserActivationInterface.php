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

interface UserActivationInterface
{ 

    /**
     * Find and return a user object via the token provided
     *
     * @param string $token
     * @return Object|null
     */
    public function findByActivationToken(string $token) : ?Object;

    /**
     * Send an activation email when the user registration event is fired
     *
     * @param string $hash
     * @return self
     */
    public function sendUserActivationEmail(string $hash) : self;

    /**
     * Validate the user object. Ensuring the user object doesn't returned null.
     *
     * @param object|null $repository
     * @return self
     */
    public function validateActivation(?object $repository) : self;

    /**
     * Activate the user account
     *
     * @return boolean
     */
    public function activate() : bool;

}