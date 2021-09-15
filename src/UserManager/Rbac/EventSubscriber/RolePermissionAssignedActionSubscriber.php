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

namespace MagmaCore\UserManager\Rbac\EventSubscriber;

use MagmaCore\UserManager\Rbac\Event\RolePermissionAssignedActionEvent;
use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\NoReturn;
use MagmaCore\EventDispatcher\EventDispatcherTrait;
use MagmaCore\EventDispatcher\EventSubscriberInterface;
use MagmaCore\UserManager\Rbac\Model\RolePermissionModel;
use Exception;

/**
 * Note: If we want to flash other routes then they must be declared within the ACTION_ROUTES
 * protected constant
 */
class RolePermissionAssignedActionSubscriber implements EventSubscriberInterface
{

    use EventDispatcherTrait;

    /** @var int - we want this to execute last so it doesn't interrupt other process */
    private const FLASH_MESSAGE_PRIORITY = -1000;
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
     * @return array
     */

    #[ArrayShape([RolePermissionAssignedActionEvent::NAME => "array"])] public static function getSubscribedEvents(): array
    {
        return [
            RolePermissionAssignedActionEvent::NAME => [
                ['flashUserEvent', self::FLASH_MESSAGE_PRIORITY],
                ['assignedRolePermission'],
            ]
        ];
    }

    /**
     * Event flash allows flashing of any specified route defined with the ACTION_ROUTES constants
     * one can declare a message and a default route. if a default route isn't
     * set then the script will
     *
     * redirect back on it self using the onSelf() method. Delete route is automatically filtered to
     * redirect back to the index page. As this is the only logical route to redirect to. after we
     * remove the object. failure to comply with this will result in 404 error as the script will
     * try to redirect to an object that no longer exists.
     *
     * @param RolePermissionAssignedActionEvent $event
     * @return void
     * @throws Exception
     */
    public function flashUserEvent(RolePermissionAssignedActionEvent $event)
    {
        $this->flashingEvent($event);
    }

    #[NoReturn] public function assignedRolePermission(RolePermissionAssignedActionEvent $event)
    {
        /* ensure permission isn't already assigned before assigned to avoid duplicate entry error */
        $context = $this->flattenContext($event->getContext());
        $roleID = $context['role_id'];
        $permissionIDs = $context['permission_id'];
        if (!empty($roleID)) {
            if (is_array($permissionIDs) && count($permissionIDs) > 0) {
                foreach ($permissionIDs as $permissionID) {
                    $this->rolePermission->getRepo()
                        ->getEm()
                        ->getCrud()
                        ->create(['role_id' => $roleID, 'permission_id' => $permissionID]);
                }
            }
        }
    }

}
