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

namespace MagmaCore\UserManager\Rbac\Permission\EventSubscriber;

use MagmaCore\UserManager\Rbac\Permission\Event\PermissionActionEvent;
use MagmaCore\UserManager\Rbac\Model\RolePermissionModel;
use JetBrains\PhpStorm\ArrayShape;
use MagmaCore\EventDispatcher\EventDispatcherTrait;
use MagmaCore\EventDispatcher\EventSubscriberInterface;
use MagmaCore\Utility\Yaml;
use Exception;

/**
 * Note: If we want to flash other routes then they must be declared within the ACTION_ROUTES
 * protected constant
 */
class PermissionActionSubscriber implements EventSubscriberInterface
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
    #[ArrayShape([PermissionActionEvent::NAME => "array"])] public static function getSubscribedEvents(): array
    {
        return [
            PermissionActionEvent::NAME => [
                ['assignedToSuperRole'],
                ['flashPermissionEvent', self::FLASH_MESSAGE_PRIORITY],
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
     * @param PermissionActionEvent $event
     * @return void
     * @throws Exception
     */
    public function flashPermissionEvent(PermissionActionEvent $event)
    {
        $this->flashingEvent($event);
    }

    /**
     * Automatically assign any newly created permission to the super admin role
     *
     * @param PermissionActionEvent $event
     * @return bool
     * @throws Exception
     */
    public function assignedToSuperRole(PermissionActionEvent $event): bool
    {
        if ($this->onRoute($event, self::NEW_ACTION)) {
            $permission = $this->flattenContext($event->getContext());
            $superRole = Yaml::file('app')['system']['super_role'];
            if ($permission) {
                $permID = $permission['last_id'];
                $fields = ['role_id' => $superRole['props']['id'], 'permission_id' => $permID];
                $this->rolePermission
                    ->getRepo()
                    ->getEm()
                    ->getCrud()
                    ->create($fields);

                if (is_bool($push) && $push === true) {
                    return true;
                }
            }
        }
        return false;
    }
}
