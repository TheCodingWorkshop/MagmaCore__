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

namespace MagmaCore\UserManager\Rbac\Group;

use MagmaCore\Base\Access;
use MagmaCore\Auth\Roles\PrivilegedUser;
use MagmaCore\UserManager\Rbac\Role\RoleModel;
use MagmaCore\Base\Traits\ControllerCommonTrait;
use MagmaCore\UserManager\Rbac\Group\Model\GroupRoleModel;
use MagmaCore\UserManager\Rbac\Group\Model\UserGroupModel;
use MagmaCore\UserManager\Rbac\Group\Entity\GroupRoleEntity;
use MagmaCore\UserManager\Rbac\Group\Event\GroupActionEvent;
use MagmaCore\UserManager\Rbac\Group\Form\GroupAssignedForm;
use MagmaCore\Administrator\Model\ControllerSessionBackupModel;
use MagmaCore\UserManager\Rbac\Group\Event\GroupRoleAssignedActionEvent;


class GroupController extends \MagmaCore\Administrator\Controller\AdminController
{

    use ControllerCommonTrait;


    public function __construct(array $routeParams)
    {
        parent::__construct($routeParams);
        $this->addDefinitions(
            [
                'repository' => GroupModel::class,
                'entity' => GroupEntity::class,
                'column' => GroupColumn::class,
                'commander' => GroupCommander::class,
                'formGroup' => GroupForm::class,
                'groupAssignedForm' => GroupAssignedForm::class,
                'groupRole' => GroupRoleModel::class,
                'userGroup' => UserGroupModel::class,
                'roles' => RoleModel::class,
                'privilegeUser' => PrivilegedUser::class
            ]
        );

    }

    /**
     * Returns a 404 error page if the data is not present within the database
     * else return the requested object
     *
     * @return mixed
     */
    public function findOr404(): mixed
    {
        return $this->repository->getRepo()
            ->findAndReturn($this->thisRouteID())
            ->or404();
    }

    public function schemaAsString(): string
    {
        return GroupSchema::class;
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
            ->execute($this, NULL, NULL, GroupSchema::class, __METHOD__)
            ->render()
            ->with()
            ->table()
            ->end();
    }

    /**
     * The new action request. is responsible for creating a new permission. By sending
     * post data to the relevant model. Which is turns sanitize and validate the the
     * incoming data. An event will be dispatched when a new permission is created.
     */
    protected function newAction(): void
    {
        $this->newAction
            ->setAccess($this, Access::CAN_ADD)
            ->execute($this, GroupEntity::class, GroupActionEvent::class, NULL, __METHOD__)
            ->render()
            ->with()
            ->form($this->formGroup)
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
            ->execute($this, GroupEntity::class, GroupActionEvent::class, NULL, __METHOD__)
            ->render()
            ->with(
                [
                    'group' => $this->toArray($this->findOr404())
                ]
            )
            ->form($this->formGroup)
            ->end();
    }

        /**
     * Route which puts the item within the trash. This is only for supported models
     * and is effectively changing the deleted_at column to 1. All datatable queries 
     * deleted_at column should be set to 0. this will prevent any trash items 
     * from showing up in the main table
     *
     * @return void
     */
    protected function trashAction()
    {
        $this->ifCanTrashAction
            ->setAccess($this, Access::CAN_TRASH)
            ->execute($this, NULL, GroupActionEvent::class, NULL, __METHOD__, [], [], GroupSchema::class)
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
        ->execute($this, GroupEntity::class, GroupActionEvent::class, NULL, __METHOD__,[], [],['deleted_at' => 0])
        ->endAfterExecution();

    }

    protected function hardDeleteAction()
    {
        $this->deleteAction
            ->setAccess($this, Access::CAN_DELETE)
            ->execute($this, NULL, GroupActionEvent::class, NULL, __METHOD__)
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
    protected function deleteAction(): void
    {
        $this->deleteAction
            ->setAccess($this, Access::CAN_DELETE)
            ->execute($this, NULL, GroupActionEvent::class, NULL, __METHOD__);
    }

    /**
     * Bulk action route
     *
     * @return void
     */
    public function bulkAction()
    {
        $this->chooseBulkAction($this, GroupActionEvent::class);
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
            ->execute($this, GroupRoleEntity::class, GroupRoleAssignedActionEvent::class, NULL, __METHOD__)
            ->render()
            ->with(
                [
                    'group' => $this->toArray($this->findOr404()),
                    'roles' => $this->roles->getRepo()->findAll(),
                    'privi_user' => $this->privilegeUser->getRoleByGroupID($this->thisRouteID()),
                    'group_role_id' => $this->groupRole->getRepo()->findBy(['role_id'], ['group_id' => $this->thisRouteID()]),
                    'user_group_id' => $this->userGroup->getRepo()->findBy(['user_id'], ['group_id' => $this->thisRouteID()])

                ]
            )
            ->form($this->groupAssignedForm)
            ->end();
    }

    protected function settingsAction()
    {
        $sessionData = $this->getSession()->get($this->thisRouteController() . '_settings');
        $this->sessionUpdateAction
            ->setAccess($this, Access::CAN_MANANGE_SETTINGS)
            ->execute($this, NULL, GroupActionEvent::class, NULL, __METHOD__, [], [], ControllerSessionBackupModel::class)
            ->render()
            ->with(
                [
                    'session_data' => $sessionData,
                    'page_title' => 'Group Settings',
                    'last_updated' => $this->controllerSessionBackupModel
                        ->getRepo()
                        ->findObjectBy(['controller' => $this->thisRouteController() . '_settings'], ['created_at'])->created_at
                ]
            )
            ->form($this->controllerSettingsForm, null, $this->toObject($sessionData))
            ->end();
    }


}