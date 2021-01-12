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

    public function findByUser(string $email) : self;
    public function sendUserResetPassword() : self;
    public function resetPassword(int $userID) : ?array;
    public function findByPasswordResetToken(string $tokenHash = null) : ?Object;
    public function reset() : bool;

}