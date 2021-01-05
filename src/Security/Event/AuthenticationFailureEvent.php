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

use MagmaCore\Auth\Model\UserModel;

class AuthentciationFailureEvent extends AuthentciationEvent
{  

    public function __construct(UserModel $userModel)
    {
        parent::__construct($userModel);
    }

}