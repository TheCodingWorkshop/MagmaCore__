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

use Exception;
use MagmaCore\Base\Access;
use MagmaCore\Utility\Yaml;
use App\Resource\UserResource;
use MagmaCore\UserManager\UserRelationship;
use MagmaCore\DataObjectLayer\DataLayerTrait;
use MagmaCore\UserManager\Model\UserLogModel;
use MagmaCore\UserManager\Model\UserNoteModel;
use MagmaCore\UserManager\Model\UserRoleModel;
use MagmaCore\UserManager\Rbac\Role\RoleModel;
use MagmaCore\UserManager\Forms\Admin\UserForm;
use MagmaCore\Base\Traits\ControllerCommonTrait;
use MagmaCore\UserManager\Entity\UserNoteEntity;
use MagmaCore\UserManager\Entity\UserRoleEntity;
use MagmaCore\UserManager\Event\UserActionEvent;
use MagmaCore\UserManager\Model\UserMetaDataModel;
use MagmaCore\UserManager\Event\UserRoleActionEvent;
use MagmaCore\UserManager\Forms\Admin\UserNotesForm;
use MagmaCore\UserManager\Model\UserPreferenceModel;
use MagmaCore\UserManager\Forms\Admin\UserPrivilegeForm;
use MagmaCore\UserManager\Rbac\Model\TemporaryRoleModel;
use MagmaCore\UserManager\Rbac\Model\RolePermissionModel;
use MagmaCore\Base\Exception\BaseInvalidArgumentException;
use MagmaCore\UserManager\Forms\Admin\UserPreferencesForm;
use MagmaCore\Administrator\Model\ControllerSessionBackupModel;

class UserController extends \MagmaCore\Administrator\Controller\AdminController
{

    use DataLayerTrait,
        ControllerCommonTrait;
        
    private const NO_USER_NOTE = 'The Queried ID is missing the starter notes. You can update the account, to generate a starter note.';

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
         * [ userModel => \App\Model\UserModel::class ]. Where the key becomes the
         * property for the userModel object like so $this->userModel->getRepo();
         */
        $this->addDefinitions(
            [
                'repository' => UserModel::class,
                'commander' => UserCommander::class,
                'rolePermission' => RolePermissionModel::class,
                'roles' => RoleModel::class,
                'apiResource' => UserResource::class,
                'userMeta' => UserMetaDataModel::class,
                'entity' => UserEntity::class,
                'column' => UserColumn::class,
                'formUser' => UserForm::class,
                'userPrivilege' => UserPrivilegeForm::class,
                'userPreferenceRepo' => UserPreferenceModel::class,
                'userPreferencesForm' => UserPreferencesForm::class,
                'userRole' => UserRoleModel::class,
                'tempRole' => TemporaryRoleModel::class,
                'userLogRepo' => UserLogModel::class,
                'userFillable' => UserFillable::class,
                'userRelationship' => UserRelationship::class,
                'userNotesForm' => UserNotesForm::class,
                'userNoteModel' => UserNoteModel::class,
                'userMetaData' => UserMetaDataModel::class,
                'userNoteEntity' => UserNoteEntity::class,
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
    // public function findOr404(): mixed
    // {
    //     if (isset($this)) {
    //         return $this->repository->getRepo()
    //             ->findAndReturn($this->thisRouteID())
    //             ->or404();
    //     }
    // }

    public function schemaAsString(): string
    {
        return UserSchema::class;
    }    

    protected function getAction()
    {
        $data = [];
        $queryID = $_GET['id'] ?? null;
        $limit = $_GET['limit'] ?? null;
        $orderby = $_GET['orderby'] ?? null;
        $order = $_GET['order'] ?? null;

        if ($limit !==null) {
            $data = $this->repository->getRepo()->findBy([],[],['limit' => $limit, 'offset' => 1]);
        } elseif ($orderby !==null) {
            $data = $this->repository->getRepo()->findBy([],[],[],['orderby' => $orderby . ' ' . $order]);
        } elseif ($limit !==null && $orderby !==null && $order !==null) {
            $data = $this->repository->getRepo()->findBy([],[],['limit' => $limit, 'offset' => 1],['orderby' => $orderby . ' ' . $order]);
        } elseif ($queryID === null) {
            $data = $this->repository->getRepo()->findAll();
        } elseif (isset($queryID)) {
            $queryID = (int)$queryID;
            $data = $this->repository->getRepo()->findOneBy(['id' => $queryID]);
            $meta['extended_data'] = [
                'preferences' => $this->userPreferenceRepo->getRepo()->findObjectBy(['user_id' => $queryID]) ?? null,
                'notes' => $this->userNoteModel->getRepo()->findBy([], ['user_id' => $queryID]) ?? null,
                'metadata' => $this->userMetaData->getRepo()->findObjectBy(['user_id' => $queryID]) ?? null
            ];
            array_push($data, $meta);
            $privi['privilege'] = [
                'role' => $this->repository->getUserRole($this->roles, $this->repository->getUserRoleID($this->userRole, $queryID)) ?? null,
                'permissions' => [$this->repository->getUserRolePermissions($this->repository->getUserRoleID($this->userRole, $queryID))] ?? null
            ];
            array_push($data, $privi);
        }

        $response = $this->restful->response($data);

        echo $response;
        die;

    }

    protected function testAction()
    {
        $relationship = $this->repository->relationship(function($baseModel){
            return $baseModel
                ->addParent($this->repository)
                ->addRelation(UserMetadataModel::class, fn($baseModel, $model) => $baseModel->leftJoin($model::FOREIGNKEY, 'u2'))
                ->addRelation(UserNoteModel::class, fn($baseModel, $model) => $baseModel->leftJoin($model::FOREIGNKEY, 'u3'))
                ->addRelation(UserPreferenceModel::class, fn($baseModel, $model) => $baseModel->leftJoin($model::FOREIGNKEY, 'u4'))
                ->addRelation(UserRoleModel::class, fn($baseModel, $model) => $baseModel->leftJoin($model::FOREIGNKEY, 'u5'))
                ->limit(1) /* optional use where() when a single item is required. argument required in item ID */
                ->getRelations(); /* must return this method at the end */
        });

        // $this->dump($relationship);
        echo $this->restful->response($relationship);
        die;

    }

    /**
     * Entry method which is hit on request. This method should be implemented within
     * all sub controller class as a default landing point when a request is
     * made.
     */
    protected function indexAction()
    {
        $activeCount = $this->repository->getRepo()->count(['status' => 'active']);
        $pendingCount = $this->repository->getRepo()->count(['status' => 'pending']);
        $lockCount = $this->repository->getRepo()->count(['status' => 'lock']);
        
        $this->indexAction
            ?->setAccess($this, Access::CAN_VIEW)
            ?->execute($this, NULL, NULL, UserSchema::class, __METHOD__)
            ?->render()
            ?->with(
                [
                    'table_tabs' => [
                        'primary' => ['tab' => 'Primary', 'icon' => 'user', 'value' => $activeCount, 'data' => "{$pendingCount} New", 'meta' => "{$activeCount} active user"],

                        // 'logs' => ['tab' => 'Logs', 'icon' => 'file-text', 'value' => $logCount,
                        // 'data' => '', 'meta' =>"{$logCount} Logged {$logCriticalCount} critical"],

                        'pending' => ['tab' => 'Pending', 'icon' => 'warning', 'value' => $pendingCount, 'data' => '', 'meta' => "{$pendingCount} awaiting."],

                        // 'trash' => ['tab' => 'Trash', 'icon' => 'trash', 'value' => $trashCount, 'data' => '', 'meta' => "{$trashCount} item in trash"],

                        'lock' => ['tab' => 'Lock', 'icon' => 'lock', 'value' => $lockCount, 'data' => '', 'meta' => "{$lockCount} account locked"],

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

    protected function overviewAction()
    {
        $this->view('/admin/user/users_overview.html', []);
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
            ->execute($this, NULL, UserActionEvent::class, NULL, __METHOD__)
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
            ->with(
                [
                    'userYml' => Yaml::file('user'),
                    'last_5_users' => $this->repository->getRepo()->findBy(['firstname', 'lastname', 'id', 'created_at'],['status' => 'active'], ['limit' => 5, 'offset' => 0], ['orderby' => 'id DESC'])
                ]
            )
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
            ->setOwnerAccess($this)
            ->execute($this, UserEntity::class, UserActionEvent::class, NULL, __METHOD__, [], ['user_id' => $this->thisRouteID()])
            ->render()
            ->with(
                [
                    'user' => $this->toArray($this->findOr404()),
                    'check_icon' => '<li><ion-icon name="checkmark-outline"></ion-icon></li>',
                    'close_icon' => '<ion-icon name="close-outline"></ion-icon>'
                ]
            )
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

    protected function cloneAction()
    {
        $this->cloneAction
            ->setAccess($this, Access::CAN_CLONE)
            ->execute($this, NULL, UserActionEvent::class, NULL, __METHOD__)
            ->endAfterExecution();
    }

    protected function hardDeleteAction()
    {
        $this->deleteAction
            ->setAccess($this, Access::CAN_HARD_DELETE)
            ->execute($this, NULL, UserActionEvent::class, NULL, __METHOD__)
            ->endAfterExecution();

    }

    /**
     * Bulk action route
     *
     * @return void
     */
    public function bulkAction()
    {
        $this->chooseBulkAction($this, UserActionEvent::class, null,  ['status' => 'active', 'deleted_at' => 0]);
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
                ['deleted_at' => 1, 'deleted_at_datetime' => date('Y-m-d H:i:s')])
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
        ->execute($this, UserEntity::class, UserActionEvent::class, NULL, __METHOD__,[], [],['deleted_at' => 0, 'deleted_at_datetime' => null])
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
            ['deleted_at' => NULL, 'deleted_at_datetime' => NULL])
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
        $this->updateOnEvent
            ->setAccess($this, Access::CAN_EDIT_PREFERENCES)
            ->execute($this, UserEntity::class, UserActionEvent::class, NULL, __METHOD__, [], [], $this->userPreferenceRepo)
            ->render()
            ->with(
                [
                    'user_preference' => $this->userPreferenceRepo->getRepo()->findObjectBy(['user_id' => $this->thisRouteID()])
                ]
            )
            ->form(
                $this->userPreferencesForm,
                null,
                $this->userPreferenceRepo->getRepo()->findObjectBy(['user_id' => $this->thisRouteID()])
            )
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
     * Render the user privilege view.
     * Note that this routes is being handled by event dispatching the record gets updated when the
     * UserRoleActionEvent gets fired which is on this route. See that \MagmaCore\UserManager\EventSubscriber\UserRoleActionSubscriber
     * for code implimentation
     */
    protected function privilegeAction()
    {
        $userRoleID = $this->flattenArray($this->userRole->getRepo()->findBy(['role_id'], ['user_id' => $this->thisRouteID()]));
        /* additional data we are dispatching on this route to our event dispatcher */
        $eventDispatchData = ['user_id' => $this->thisRouteID(), 'prev_role_id' => $userRoleID[0]];

        $this->simpleUpdateAction
            ->setAccess($this, Access::CAN_EDIT_PRIVILEGE)
            ->execute($this, UserRoleEntity::class, UserRoleActionEvent::class, NULL, __METHOD__, [], $eventDispatchData, $this->userRole)
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
            ->with([])
            ->table()
            ->end();
    }

    protected function notesAction()
    {
        $this->updateOnEvent
            ->setAccess($this, Access::CAN_NOTE)
            ->exists($this, $this->userNoteModel, 'user_id', self::NO_USER_NOTE, ['user_id'])
            ->execute($this, UserEntity::class, UserActionEvent::class, NULL, __METHOD__, [], [], $this->userNoteModel)
            ->render()
            ->with(
                [
                    'row' => $this->toArray($this->findOr404()),
                    'notes' => $this->toArray($this->userNoteModel->getRepo()->findBy(['notes', 'created_at', 'user_id', 'id'], ['user_id' => $this->thisRouteID()]))
                ]
            )
            ->form(
                $this->userNotesForm,
                null,
                $this->userNoteModel->getRepo()->findObjectBy(['user_id' => $this->thisRouteID()])
            )
            ->end();
    }

    protected function personalAction()
    {
        $this->showAction
            ->setAccess($this, Access::CAN_SHOW)
            ->execute($this, NULL, NULL, NULL, __METHOD__)
            ->render()
            ->with(
                [
                ]
            )
            ->singular()
            ->end();
    }

    protected function settingsAction()
    {
        $this->sessionUpdateAction
            ->setAccess($this, Access::CAN_MANANGE_SETTINGS)
            ->execute($this, NULL, UserActionEvent::class, NULL, __METHOD__, [], [], ControllerSessionBackupModel::class)
            ->render()
            ->with(
                [
                    'session_data' => $this->controllerSessionData($this),
                    'page_title' => 'User Settings',
                    'session_model' => $this->sessionModel($this),
                    'database_session_context' => $this->sessionModelContext($this),
                ]
            )
            ->form($this->controllerSettingsForm)
            ->end();
    }


}
