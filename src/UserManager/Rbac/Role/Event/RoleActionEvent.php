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

namespace MagmaCore\UserManager\Rbac\Role\Event;

use MagmaCore\Base\BaseActionEvent;

class RoleActionEvent extends BaseActionEvent
{

    /** @var string - name of the event */
    public const NAME = 'magmacore.usermanager.rbac.role.event.role_action_event';
}
