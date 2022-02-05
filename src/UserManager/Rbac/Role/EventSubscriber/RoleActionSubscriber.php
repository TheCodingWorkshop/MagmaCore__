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

namespace MagmaCore\UserManager\Rbac\Role\EventSubscriber;

use Exception;
use MagmaCore\EventDispatcher\EventDispatcherTrait;
use MagmaCore\EventDispatcher\EventSubscriberInterface;
use MagmaCore\UserManager\Rbac\Model\RolePermissionModel;
use MagmaCore\UserManager\Rbac\Permission\PermissionModel;
use MagmaCore\UserManager\Rbac\Role\Event\RoleActionEvent;

/**
 * Note: If we want to flash other routes then they must be declared within the ACTION_ROUTES
 * protected constant
 */
class RoleActionSubscriber implements EventSubscriberInterface
{

    use EventDispatcherTrait;

    /** @var int - we want this to execute last so it doesn't interrupt other process */
    private const FLASH_MESSAGE_PRIORITY = -1000;

    private const NEW_ACTION = 'new';

    private RolePermissionModel $rolePermission;
    private PermissionModel $permission;

    /**
     * @param RolePermissionModel $rolePermission
     */
    public function __construct(RolePermissionModel $rolePermission, PermissionModel $permission)
    {
        $this->rolePermission = $rolePermission;
        $this->permission = $permission;
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
            RoleActionEvent::NAME => [
                ['flashRoleEvent', self::FLASH_MESSAGE_PRIORITY],
                ['addBasicPermissonToRule']
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
     * @param RoleActionEvent $event
     * @return void
     * @throws Exception
     */
    public function flashRoleEvent(RoleActionEvent $event): void
    {
        $this->flashingEvent($event);
    }

    /**
     * Add the basic_access permission to each new role created. We only want to execute
     * the on the newAction route only.
     *
     * @param RoleActionEvent $event
     * @return void
     */
    public function addBasicPermissonToRule(RoleActionEvent $event): bool
    {
        if ($this->onRoute($event, self::NEW_ACTION)) {
            $role = $this->flattenContext($event->getContext());
            if (isset($role['last_id']) && $role['last_id'] !==0) {
                $basicAccessPermID = $this->permission
                ->getRepo()
                ->findObjectBy(['permission_name' => 'basic_access'], ['id']);
                
                /* Now we have the permission ID lets insert the record within the role_permission table */
                if ($basicAccessPermID->id) {
                    $added = $this->rolePermission
                    ->getRepo()
                    ->getEm()
                    ->getCrud()
                    ->create(['role_id' => $role['last_id'], 'permission_id' => $basicAccessPermID->id]);

                    if ($added) {
                        return true;
                    }
                }
            }
        }

        return false;
    }

}
