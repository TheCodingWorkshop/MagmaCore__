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

namespace MagmaCore\Security\Event;

use MagmaCore\EventDispatcher\Event;
use MagmaCore\Auth\Model\UserModel;

class AuthentciationEvent extends Event
{  

    private UserModel $userModel;

    public function __construct(UserModel $userModel)
    {
        $this->userModel = $userModel;
    }

    public function getUserModel()
    {
        return $this->userModel->getRepo();
    }

}