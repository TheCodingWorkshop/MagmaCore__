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

namespace MagmaCore\UserManager\Rbac\Group\EventSubscriber;

use MagmaCore\UserManager\Rbac\Group\Event\GroupActionEvent;
use MagmaCore\UserManager\Rbac\Model\RolePermissionModel;
use MagmaCore\EventDispatcher\EventDispatcherTrait;
use MagmaCore\EventDispatcher\EventSubscriberInterface;
use MagmaCore\Utility\Yaml;
use Exception;

/**
 * Note: If we want to flash other routes then they must be declared within the ACTION_ROUTES
 * protected constant
 */
class GroupActionSubscriber implements EventSubscriberInterface
{

    use EventDispatcherTrait;

    /** @var int - we want this to execute last so it doesn't interrupt other process */
    private const FLASH_MESSAGE_PRIORITY = -1000;
    /**
     * Add other route index here in order for that route to flash properly. this array is index array
     * which means the first item starts at 0. See ACTION_ROUTES constant for correct order of how to
     * load other routes for flashing
     * @var int
     */
    protected const NEW_ACTION = 'new';
    protected const EDIT_ACTION = 'edit';
    protected const DELETE_ACTION = 'delete';

    private RolePermissionModel $rolePermission;

    /**
     * Undocumented function
     *
     * @param RolePermissionModel $rolePermission
     */
    public function __construct(RolePermissionModel $rolePermission)
    {
        $this->rolePermission = $rolePermission;
    }


    /**
     * Subscribe multiple listeners to listen for the NewActionEvent. This will fire
     * each time a new user is added to the database. Listeners can then perform
     * additional tasks on that return object.
     *
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return [
            GroupActionEvent::NAME => [
                ['flashGroupEvent', self::FLASH_MESSAGE_PRIORITY],
            ]
        ];
    }

    /**
     * Event flash allows flashing of any specified route defined with the ACTION_ROUTES constants
     * one can declare a message and a default route. if a default route isn't set then the script will
     * redirect back on it self using the onSelf() method. Delete route is automatically filtered to
     * redirect back to the index page. As this is the only logical route to redirect to. after we
     * remove the object. failure to comply with this will result in 404 error as the script will
     * try to redirect to an object that no longer exists.
     *
     * @param GroupActionEvent $event
     * @return void
     * @throws Exception
     */
    public function flashGroupEvent(GroupActionEvent $event)
    {
        $this->flashingEvent($event);
    }

}

