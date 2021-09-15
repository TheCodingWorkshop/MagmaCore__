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

namespace MagmaCore\UserManager\Security\Event;

use MagmaCore\Base\BaseActionEvent;

class LoginActionEvent extends BaseActionEvent
{

    /** @var string - name of the event */
    public const NAME = 'magmacore.usermanager.security.event.login_action_event';
}
