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

namespace MagmaCore\Base;

use MagmaCore\Utility\Yaml;
use MagmaCore\Ash\Error\LoaderError;
use MagmaCore\Ash\TemplateEnvironment;
use MagmaCore\Ash\Exception\FileNotFoundException;

class Access
{

    public const CAN_SHOW = 'can_show';
    public const CAN_ADD = 'can_add';
    public const CAN_EDIT = 'can_show';
    public const CAN_DELETE = 'can_show';
    public const CAN_HARD_DELETE = 'can_show';
    public const CAN_CLONE = 'can_show';
    public const CAN_LOCK = 'can_show';
    public const CAN_UNLOCK = 'can_show';
    public const CAN_TRASH = 'can_show';
    public const CAN_RESTORE_TRASH = 'can_show';
    public const CAN_CHANGE_STATUS = 'can_show';
    public const CAN_EDIT_PREFERENCES = 'can_show';
    public const CAN_EDIT_PRIVILEGE = 'can_show';
    public const CAN_SET_PRIVILEGE_EXPIRATION = 'can_show';
    public const CAN_LOG = 'can_show';
    public const CAN_VIEW = 'can_show';
    public const HAVE_BASIC_ACCESS = 'can_show';
    public const HAVE_ADMIN_ACCESS = 'can_show';
    public const CAN_MANAGE_GROUP = 'can_show';


}
