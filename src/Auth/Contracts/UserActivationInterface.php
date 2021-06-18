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

    public function findByActivationToken(string $token) : ?Object;
    public function sendUserActivationEmail(string $hash) : self;
    public function validateActivation(?object $repository) : self;
    public function activate() : bool;

}