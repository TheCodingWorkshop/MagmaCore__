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

namespace MagmaCore\UserManager\Rbac\Role;

use MagmaCore\Base\Access;
use MagmaCore\UserManager\UserModel;
use MagmaCore\Auth\Roles\PrivilegedUser;
use MagmaCore\DataObjectLayer\DataLayerTrait;
use MagmaCore\UserManager\Rbac\Role\RoleForm;
use MagmaCore\UserManager\Model\UserRoleModel;
use MagmaCore\UserManager\Rbac\Role\RoleEntity;
use MagmaCore\UserManager\Rbac\Role\RoleSchema;
use MagmaCore\Base\Traits\ControllerCommonTrait;
use MagmaCore\UserManager\Rbac\Form\RoleAssignedForm;
use MagmaCore\UserManager\Rbac\Role\RoleRelationship;
use MagmaCore\UserManager\Rbac\Model\RolePermissionModel;
use MagmaCore\Base\Exception\BaseInvalidArgumentException;
use MagmaCore\UserManager\Rbac\Permission\PermissionModel;
use MagmaCore\UserManager\Rbac\Role\Event\RoleActionEvent;
use MagmaCore\UserManager\Rbac\Entity\RolePermissionEntity;
use MagmaCore\Administrator\Model\ControllerSessionBackupModel;
use MagmaCore\UserManager\Rbac\Event\RolePermissionAssignedActionEvent;

class RoleController extends \MagmaCore\Administrator\Controller\AdminController
{

    use ControllerCommonTrait;
    use DataLayerTrait;

    /**
     * Extends the base constructor method. Which gives us access to all the base
     * methods implemented within the base controller class.
     * Class dependency can be loaded within the constructor by calling the
     * container method and passing in an associative array of dependency to use within
     * the class
     *
     * @param array $routeParams
     * @return void
     * @throws BaseInvalidArgumentException
     */
    public function __construct(array $routeParams)
    {
        parent::__construct($routeParams);
        /**
         * Dependencies are defined within a associative array like example below
         * [ roleModel => \App\Model\RoleModel::class ]. Where the key becomes the
         * property for the RoleModel object like so $this->roleModel->getRepo();
         */
        $this->addDefinitions(
            [
                'repository' => RoleModel::class,
                'userRepository' => UserModel::class,
                'commander' => RoleCommander::class,
                'entity' => RoleEntity::class,
                'column' => RoleColumn::class,
                'formRole' => RoleForm::class,
                'formRoleAssigned' => RoleAssignedForm::class,
                'permission' => PermissionModel::class,
                'rolePerm' => RolePermissionModel::class,
                'userRole' => UserRoleModel::class,
                'relationship' => RoleRelationship::class,
                'privilegeUser' => PrivilegedUser::class,
                'permission' => PermissionModel::class,
            ]
        );
    }

    /**
     * Returns a 404 error page if the data is not present within the database
     * else return the requested object
     *
     * @return mixed
     */
    // public function findOr404(): mixed
    // {
    //     return $this->repository->getRepo()
    //         ->findAndReturn($this->thisRouteID())
    //         ->or404();
    // }

    /**
     * Return the schema as a string
     *
     * @return string
     */
    public function schemaAsString(): string
    {
        return RoleSchema::class;
    }

    /**
     * Entry method which is hit on request. This method should be implement within
     * all sub controller class as a default landing point when a request is
     * made.
     */
    protected function indexAction()
    {
        $this->indexAction
            ->setAccess($this, Access::CAN_VIEW)
            ->execute($this, NULL, NULL, RoleSchema::class, __METHOD__)
            ->render()
            ->with()
            ->table()
            ->end();
    }

    /**
     * The new action request. is responsible for creating a new role. By sending
     * post data to the relevant model. Which is turns sanitize and validate the the
     * incoming data. An event will be dispatched when a new role is created.
     */
    protected function newAction(): void
    {
        $this->newAction
            ->setAccess($this, Access::CAN_ADD)
            ->execute($this, RoleEntity::class, RoleActionEvent::class, NULL, __METHOD__)
            ->render()
            ->with()
            ->form($this->formRole)
            ->end();
    }

    /**
     * The edit action request. is responsible for updating a user record within
     * the database. User data will be sanitized and validated before upon re
     * submitting new data. An event will be dispatched on this action
     */
    protected function editAction(): void
    {
        $this->editAction
            ->setAccess($this, Access::CAN_EDIT)
            ->execute($this, RoleEntity::class, RoleActionEvent::class, NULL, __METHOD__)
            ->render()
            ->with(
                [
                    'role' => $this->toArray($this->findOr404())
                ]
            )
            ->form($this->formRole)
            ->end();
    }

    protected function trashAction()
    {
        $this->ifCanTrashAction
            ->setAccess($this, Access::CAN_TRASH)
            ->execute($this, NULL, RoleActionEvent::class, NULL, __METHOD__, [], [], RoleSchema::class)
            ->endAfterExecution();
    }

    /**
     * As trashing an item changes the deleted_at column to 1 we can reset that to 0
     * for individual items.
     *
     * @return void
     */
    protected function untrashAction()
    {
        $this->changeStatusAction
        ->setAccess($this, Access::CAN_UNTRASH)
        ->execute($this, RoleEntity::class, RoleActionEvent::class, NULL, __METHOD__,[], [],['deleted_at' => 0])
        ->endAfterExecution();

    }

    /**
     * The delete action request. is responsible for deleting a single record from
     * the database. This method is not a submittable method hence why this check has
     * been omitted. This a simple click based action. which is triggered within the
     * datatable. An event will be dispatch by this action
     *
     * @return void
     */
    protected function hardDeleteAction(): void
    {
        $this->deleteAction
            ->setAccess($this, Access::CAN_DELETE)
            ->execute($this, NULL, RoleActionEvent::class, NULL, __METHOD__);
    }

    /**
     * Bulk action route
     *
     * @return void
     */
    public function bulkAction()
    {
        $this->chooseBulkAction($this, RoleActionEvent::class);
    }

    /**
     * Assigned role route
     *
     * @return void
     */
    protected function assignedAction()
    {

        $this->blankAction
            ->setAccess($this, Access::CAN_ASSIGN)
            ->execute($this, RolePermissionEntity::class, RolePermissionAssignedActionEvent::class, NULL, __METHOD__)
            ->render()
            ->with(
                [
                    'roles' => $this->repository->getRepo()->findBy(['id', 'role_name']),
                    'this_perm' => Access::CAN_ASSIGN,
                    'current_role_id' => $this->thisRouteID(),
                    'role' => $this->toArray($this->findOr404()),
                    'permissions' => $this->permission->getRepo()->findAll(),
                    'privi_user' => $this->privilegeUser->getPermissionByRoleID($this->thisRouteID()),
                    'role_perms' => $this->flattenArray($this->rolePerm->getRepo()->findBy(['permission_id'], ['role_id' => $this->thisRouteID()])),
                    'user_role_id' => $this->userRole->getRepo()->findBy(['user_id'], ['role_id' => $this->thisRouteID()])
                ]
            )
            ->form($this->formRoleAssigned)
            ->end();
    }

    protected function logAction()
    {
        $this->logIndexAction
            ->setAccess($this, Access::CAN_LOG)
            ->execute($this, NULL, NULL, RoleSchema::class, __METHOD__)
            ->render()
            ->with()
            ->table()
            ->end();
    }

    /**
     * Unassigned one or more permission from an associative role. This only affects
     * the relationship between the role and the permission and neither the role or
     * permissions are deleted. Just the relationship between them. From the
     * role_permissions table.
     *
     * @return bool
     */
    protected function unassignPermissionAction(): bool
    {
        /* Get the current role ID cast as an integre */
        $queriedRoleID = (int)$_GET['role_id'] ?? null;
        /* Get all the permission assigned to the $queriedRoleID and flatten the array */
        $permissionIDs = $this->flattenArray($this->rolePerm->getRepo()->findBy(['*'], ['role_id' => $queriedRoleID]));
        /* The queried permission to remove from the relationship */
        $queriedPermissionID = $this->thisRouteID();
        /* Ensure our queried permission is within the list of permissions assigned to the queried role */
        if (in_array($queriedPermissionID, $permissionIDs)) {
            foreach ($permissionIDs as $permID) {
                /* We have an exact match */
                if ($permID === $queriedPermissionID) {
                    /* We are adding exact condition to ensure the correct permission is deleted from the queried role */
                    $delete = $this->rolePerm->getRepo()->getEm()->getCrud()->delete(['permission_id' => $queriedPermissionID, 'role_id' => $queriedRoleID]);
                    ($delete === true) ? $this->flashMessage('Permission remove') : $this->flashMessage('Permission failed to unassigned.');
                    return $this->redirect('/admin/role/' . $queriedRoleID . '/assigned');

                }
            }
        }
        return false;
    }

    protected function settingsAction()
    {
        $sessionData = $this->getSession()->get($this->thisRouteController() . '_settings');
        $this->sessionUpdateAction
            ->setAccess($this, Access::CAN_MANANGE_SETTINGS)
            ->execute($this, NULL, RoleActionEvent::class, NULL, __METHOD__, [], [], ControllerSessionBackupModel::class)
            ->render()
            ->with(
                [
                    'session_data' => $sessionData,
                    'page_title' => 'Role Settings',
                    'last_updated' => $this->controllerSessionBackupModel
                        ->getRepo()
                        ->findObjectBy(['controller' => $this->thisRouteController() . '_settings'], ['created_at'])->created_at
                ]
            )
            ->form($this->controllerSettingsForm, null, $this->toObject($sessionData))
            ->end();
    }


}
