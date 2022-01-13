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

interface UserPasswordRecoveryInterface
{ 

    /**
     * Find and return the user object via the supplied email
     *
     * @param string $email
     * @return self
     */
    public function findByUser(string $email) : self;

    /**
     * Send a reset password when the required event is fired
     *
     * @param object $controller
     * @return boolean
     */
    public function sendUserResetPassword(object $controller) : bool;

    /**
     * Initiate the password reset within the database
     *
     * @param integer $userID
     * @return array|null
     */
    public function resetPassword(int $userID) : ?array;

    /**
     * Find the user object by the password reset token sent to the user when 
     * requesting a new password
     *
     * @param string|null $tokenHash
     * @return Object|null
     */
    public function findByPasswordResetToken(string $tokenHash = null) : ?Object;
    public function reset() : bool;

}