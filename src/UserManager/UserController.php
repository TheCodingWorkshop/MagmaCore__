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

namespace MagmaCore\UserManager;

use App\Controller\Admin\AdminController;
use MagmaCore\UserManager\Event\UserActionEvent;
use MagmaCore\UserManager\Event\UserRoleActionEvent;
use App\Forms\Admin\User\SettingsForm;
use MagmaCore\UserManager\Forms\Admin\UserForm;
use MagmaCore\UserManager\Forms\Admin\UserPreferencesForm;
use MagmaCore\UserManager\Forms\Admin\UserPrivilegeForm;
use MagmaCore\UserManager\Model\UserMetaDataModel;
use MagmaCore\UserManager\Model\UserLogModel;
use MagmaCore\UserManager\Model\UserRoleModel;
use MagmaCore\UserManager\Model\UserPreferenceModel;
use MagmaCore\UserManager\Entity\UserRoleEntity;
use MagmaCore\UserManager\Schema\UserLogSchema;
use MagmaCore\UserManager\Repository\UserRoleRepository;
use MagmaCore\UserManager\DataColumn\UserLogColumn;
use MagmaCore\UserManager\UserRelationship;
use MagmaCore\UserManager\Rbac\Entity\TemporaryRoleEntity;
use MagmaCore\UserManager\Rbac\Model\RolePermissionModel;
use MagmaCore\UserManager\Rbac\Role\RoleModel;
use MagmaCore\UserManager\Rbac\Model\TemporaryRoleModel;
use MagmaCore\Base\BaseProtectedRoutes;
use MagmaCore\Base\Exception\BaseInvalidArgumentException;
use MagmaCore\DataObjectLayer\DataLayerTrait;
use MagmaCore\Utility\Yaml;
use MagmaCore\Base\Access;
use JetBrains\PhpStorm\NoReturn;
use Exception;

#[BaseProtectedRoutes]
class UserController extends AdminController
{

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
    #[NoReturn] public function __construct(array $routeParams)
    {
        parent::__construct($routeParams);
        /**
         * Dependencies are defined within a associative array like example below
         * [ userModel => \App\Model\UserModel::class ]. Where the key becomes the
         * property for the userModel object like so $this->userModel->getRepo();
         */
        $this->addDefinitions(
            [
                'repository' => UserModel::class,
                'commander' => UserCommander::class,
                'rolePermission' => RolePermissionModel::class,
                'roles' => RoleModel::class,
                'userMeta' => UserMetaDataModel::class,
                'entity' => UserEntity::class,
                'column' => UserColumn::class,
                'formUser' => UserForm::class,
                'userPrivilege' => UserPrivilegeForm::class,
                'userPreferenceRepo' => UserPreferenceModel::class,
                'userPreferencesForm' => UserPreferencesForm::class,
                'formSettings' => SettingsForm::class,
                'userRole' => UserRoleModel::class,
                'userRoleRepo' => UserRoleRepository::class,
                'tempRole' => TemporaryRoleModel::class,
                'userLogRepo' => UserLogModel::class,
                'userFillable' => UserFillable::class,
                'userRelationship' => UserRelationship::class,

            ]
        );

        /** Initialize database with table settings */
    }

    /**
     * Returns a 404 error page if the data is not present within the database
     * else return the requested object
     *
     * @return mixed
     */
    public function findOr404(): mixed
    {
        if (isset($this)) {
            return $this->repository->getRepo()
                ->findAndReturn($this->thisRouteID())
                ->or404();
        }
    }

    /**
     * Entry method which is hit on request. This method should be implemented within
     * all sub controller class as a default landing point when a request is
     * made.
     */
    #[BaseProtectedRoutes('test', 42)]
    protected function indexAction()
    {

        $trashCount = $this->repository->getRepo()->count(['status' => 'trash']);
        $activeCount = $this->repository->getRepo()->count(['status' => 'active']);
        $pendingCount = $this->repository->getRepo()->count(['status' => 'pending']);
        $lockCount = $this->repository->getRepo()->count(['status' => 'lock']);
        $logCount = $this->userLogRepo->getRepo()->count();
        $logCriticalCount = $this->userLogRepo->getRepo()->count(['level' => 500]);

        $this->indexAction
            ?->setAccess($this, Access::CAN_VIEW)
            ?->execute($this, NULL, NULL, UserSchema::class, __METHOD__)
            ?->render()
            ?->with(
                [
                    'table_tabs' => [
                        'primary' => ['tab' => 'Primary', 'icon' => 'person', 'value' => $activeCount, 'data' => "{$pendingCount} New", 'meta' => "{$activeCount} active user"],
                        'logs' => ['tab' => 'Logs', 'icon' => 'reader', 'value' => $logCount, 'data' => '', 'meta' => "{$logCount} Logged {$logCriticalCount} critical"],
                        'pending' => ['tab' => 'Pending', 'icon' => 'warning', 'value' => $pendingCount, 'data' => '', 'meta' => "{$pendingCount} awaiting."],
                        'trash' => ['tab' => 'Trash', 'icon' => 'trash', 'value' => $trashCount, 'data' => '', 'meta' => "{$trashCount} item in trash"],
                        'lock' => ['tab' => 'Lock', 'icon' => 'lock-closed', 'value' => $lockCount, 'data' => '', 'meta' => "{$lockCount} account locked"],

                    ],
                    'lists' => $this->repository
                        ->getRepo()
                        ->findBy(
                            ['firstname', 'lastname', 'id', 'deleted_at_datetime'],
                            ['status' => 'trash', 'deleted_at' => 1]
                        ),
                    'lock' => $this->repository
                        ->getRepo()
                        ->findBy(
                            ['firstname', 'lastname', 'email', 'id', 'created_at', 'status'],
                            ['status' => 'lock']
                        ),
                    'pendings' => $this->repository
                        ->getRepo()
                        ->findBy(
                            ['firstname', 'lastname', 'email', 'id', 'created_at', 'status'],
                            ['status' => 'pending']
                        ),

                    'logs' => $this->userLogRepo
                        ->getRepo()
                        ->findAll(),
                    'count_active' => $activeCount,
                    'count_pending' => $pendingCount,
                    'status' => $this->request->handler()->query->get('status')

                ]
            )
            ->table()
            ->end();
    }

    /**
     * The show action request displays singular information about a user. This is a
     * read only request. Information here cannot be edited.
     * @throws Exception
     */
    protected function showAction()
    {
        $this->showAction
            ->setAccess($this, Access::CAN_SHOW)
            ->execute($this, NULL, NULL, NULL, __METHOD__)
            ->render()
            ->with(
                [
                    'user_log' => $this->userMeta->unserializeData(
                        ['user_id' => $this->thisRouteID()],
                        [
                            'login', /* array index 0 */
                            'logout', /* array index 1 */
                            'brute_force', /* index 2 */
                            'user_browser' /* index 3 */
                        ]
                    )
                ]
            )
            ->singular()
            ->end();
    }

    /**
     * The new action request. is responsible for creating a new user. By sending
     * post data to the relevant model. Which is turns sanitize and validate the the
     * incoming data. An event will be dispatched when a new user is created.
     * @throws Exception
     */
    protected function newAction()
    {
        $this->newAction
            ->setAccess($this, Access::CAN_ADD)
            ->execute($this, UserEntity::class, UserActionEvent::class, NULL, __METHOD__)
            ->render()
            ->with(['userYml' => Yaml::file('user')])
            ->form($this->formUser)
            ->end();
    }

    /**
     * The edit action request. is responsible for updating a user record within
     * the database. User data will be sanitized and validated before upon re
     * submitting new data. An event will be dispatched on this action
     */
    protected function editAction()
    {
        $this->editAction
            ->setAccess($this, Access::CAN_EDIT)
            ->execute($this, UserEntity::class, UserActionEvent::class, NULL, __METHOD__, [], ['user_id' => $this->thisRouteID()])
            ->render()
            ->with(['user' => $this->toArray($this->findOr404())])
            ->form($this->formUser)
            ->end();
    }

    /**
     * The delete action request. is responsible for deleting a single record from
     * the database. This method is not a submittable method hence why this check has
     * been omitted. This a simple click based action. which is triggered within the
     * datatable. An event will be dispatch by this action
     */
    protected function deleteAction()
    {
        $this->deleteAction
            ->setAccess($this, Access::CAN_DELETE)
            ->execute($this, NULL, UserActionEvent::class, NULL, __METHOD__)
            ->endAfterExecution();

    }

    protected function hardDeleteAction()
    {
        $this->showAction
            ->setAccess($this, Access::CAN_HARD_DELETE)
            ->execute($this, NULL, NULL, NULL, __METHOD__)
            ->render()
            ->with()
            ->singular()
            ->end();

    }

    /**
     * The bulk delete action request. is responsible for deleting multiple record from
     * the database. This method is not a submittable method hence why this check has
     * been omitted. This a simple click based action. which is triggered within the
     * datatable. An event will be dispatch by this action
     */
    protected function bulkAction()
    {
//        if (array_key_exists('id', $this->request->handler()->request->get('bulk-trash'))) {
//            var_dump(true);
//            die;
//        }
            //var_dump($this->request->handler()->request->get('id'));
//        $this->bulkDeleteAction
//            ->setAccess($this, 'can_bulk_delete')
//            ->execute($this, NULL, UserActionEvent::class, NULL, __METHOD__);
    }

    /**
     * Clone a user account and append a unique index to prevent email unique key
     * collision
     */
    protected function cloneAction()
    {
        $this->newAction
            ->setAccess($this, Access::CAN_CLONE)
            ->execute($this, UserEntity::class, UserActionEvent::class, NULL, __METHOD__)
            ->render()
            ->with()
            ->singular()
            ->end();
    }

    /**
     * Change a user status to lock
     */
    protected function lockAction()
    {
        $this->changeStatusAction
            ->setAccess($this, Access::CAN_LOCK)
            ->execute($this, UserEntity::class, UserActionEvent::class, NULL, __METHOD__,[], [],
                ['status' => 'lock'])
            ->endAfterExecution();
    }

    /**
     * Change a user status to lock
     */
    protected function unlockAction()
    {
        $this->changeStatusAction
            ->setAccess($this, Access::CAN_UNLOCK)
            ->execute($this, UserEntity::class, UserActionEvent::class, NULL, __METHOD__, [], [],
                ['status' => 'active'])
            ->endAfterExecution();
    }

    /**
     * change a user status to trash and populate the deleted_at field to remove the trash
     * user from the main table listing
     */
    protected function trashAction()
    {
        $this->changeStatusAction
            ->setAccess($this, Access::CAN_TRASH)
            ->execute($this, UserEntity::class, UserActionEvent::class, NULL, __METHOD__, [], [],
                ['status' => 'trash', 'deleted_at' => 1, 'deleted_at_datetime' => date('Y-m-d H:i:s')])
            ->endAfterExecution();
    }

    /**
     * Change a user status from trash to active and null the deleted_at field for the user
     * to show in the main table listing
     */
    protected function trashRestoreAction()
    {
        $this->changeStatusAction
            ->setAccess($this, Access::CAN_RESTORE_TRASH)
            ->execute($this, UserEntity::class, UserActionEvent::class, NULL, __METHOD__, [], [],
            ['status' => 'active', 'deleted_at' => NULL, 'deleted_at_datetime' => NULL])
            ->endAfterExecution();
    }

    /**
     * Change a user status to active
     */
    protected function activeAction()
    {
        $this->changeStatusAction
            ->setAccess($this, Access::CAN_CHANGE_STATUS)
            ->execute($this, UserEntity::class, UserActionEvent::class, NULL, __METHOD__, [], [],
            ['status' => 'active'])
            ->endAfterExecution();

    }

    /**
     * Render the user preferences view
     */
    protected function preferencesAction()
    {
        $this->newAction
            ->setAccess($this, Access::CAN_EDIT_PREFERENCES)
            ->execute($this, UserEntity::class, UserActionEvent::class, NULL, __METHOD__)
            ->render()
            ->with(
                [
                    'user_preference' => $this->userPreferenceRepo->getRepo()->findObjectBy(['user_id' => $this->thisRouteID()])
                ]
            )
            ->form($this->userPreferencesForm)
            ->end();
    }

    /**
     * @return mixed
     */
    private function getUserRoleID(): mixed
    {
        return $this->flattenArray(
            $this->userRole
                ->getRepo()
                ->findBy(['role_id'], ['user_id' => $this->thisRouteID()]));
    }

    /**
     * Render the user privilege view
     */
    protected function privilegeAction()
    {
        $userRoleID = $this->flattenArray($this->userRole->getRepo()->findBy(['role_id'], ['user_id' => $this->thisRouteID()]));
        /* additional data we are dispatching on this route to our event dispatcher */
        $eventDispatchData = ['user_id' => $this->thisRouteID(), 'prev_role_id' => $userRoleID[0]];
        $this->simpleUpdateAction
            ->setAccess($this, Access::CAN_EDIT_PRIVILEGE)
            ->execute($this, UserRoleEntity::class, UserRoleActionEvent::class, NULL, __METHOD__, [], $eventDispatchData)
            ->render()
            ->with(
                [
                    'roles' => $this->roles->getRepo()->findAll(),
                    'user_role' => $userRoleID,
                    'row' => $this->toArray($this->findOr404()),
                    'temp_role' => $this->tempRole->getRepo()->findBy(['*'], ['user_id' => $this->thisRouteID()])
                ]
            )
            ->form($this->userPrivilege)
            ->end();
    }

    protected function privilegeExpirationAction()
    {
        $userRoleID = $this->flattenArray($this->userRole->getRepo()->findBy(['role_id'], ['user_id' => $this->thisRouteID()]));
        $eventDispatcherArr = ['user_id' => $this->thisRouteID(), 'role_id' => $userRoleID[0]];
        $this->blankAction
            ->setAccess($this, Access::CAN_SET_PRIVILEGE_EXPIRATION)
            ->execute($this, UserRoleEntity::class, UserRoleActionEvent::class, NULL, __METHOD__, [], $eventDispatcherArr)
            ->endWithoutRender();
    }

    protected function logAction()
    {
        $this->indexAction
            ->setAccess($this, Access::CAN_LOG)
            ->execute($this, NULL, NULL, UserSchema::class, __METHOD__)
            ->render()
            ->with()
            ->table()
            ->end();
    }


}
