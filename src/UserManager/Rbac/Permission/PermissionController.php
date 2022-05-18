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

namespace MagmaCore\UserManager\Rbac\Permission;

use MagmaCore\Base\Access;
use MagmaCore\Base\Traits\ControllerCommonTrait;
use MagmaCore\UserManager\Rbac\Model\RolePermissionModel;
use MagmaCore\UserManager\Rbac\Permission\PermissionForm;
use MagmaCore\Base\Exception\BaseInvalidArgumentException;
use MagmaCore\UserManager\Rbac\Permission\PermissionEntity;
use MagmaCore\UserManager\Rbac\Permission\PermissionSchema;
use MagmaCore\Administrator\Model\ControllerSessionBackupModel;
use MagmaCore\UserManager\Rbac\Permission\Event\PermissionActionEvent;

class PermissionController extends \MagmaCore\Administrator\Controller\AdminController
{

    use ControllerCommonTrait;

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
         * [ PermissionModel => \App\Model\PermissionModel::class ]. Where the key becomes the
         * property for the PermissionModel object like so $this->PermissionModel->getRepo();
         */
        $this->addDefinitions(
            [
                'repository' => PermissionModel::class,
                'entity' => PermissionEntity::class,
                'column' => PermissionColumn::class,
                'commander' => PermissionCommander::class,
                'formPermission' => PermissionForm::class,
                'rolePerm' => RolePermissionModel::class,
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
        return PermissionSchema::class;
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
            ->execute($this, NULL, NULL, PermissionSchema::class, __METHOD__)
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
            ->execute($this, PermissionEntity::class, PermissionActionEvent::class, NULL, __METHOD__)
            ->render()
            ->with()
            ->form($this->formPermission)
            ->end();
    }

    /**
     * The edit action request. is responsible for updating a user record within
     * the database. User data will be sanitized and validated before upon re
     * submitting new data. An event will be dispatched on this action
     *
     * @return void
     */
    protected function editAction(): void
    {
        $this->editAction
            ->setAccess($this, Access::CAN_EDIT)
            ->execute($this, PermissionEntity::class, PermissionActionEvent::class, NULL, __METHOD__)
            ->render()
            ->with(
                [
                    'permission' => $this->toArray($this->findOr404()),
                    'role_perm' => $this->rolePerm->getRepo()->findBy(['role_id'], ['permission_id' => $this->thisRouteID()])
                ]
            )
            ->form($this->formPermission)
            ->end();
    }

    protected function trashAction()
    {
        $this->ifCanTrashAction
            ->setAccess($this, Access::CAN_TRASH)
            ->execute($this, NULL, PermissionActionEvent::class, NULL, __METHOD__, [], [], PermissionSchema::class)
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
        ->execute($this, PermissionEntity::class, PermissionActionEvent::class, NULL, __METHOD__,[], [],['deleted_at' => 0])
        ->endAfterExecution();

    }

    /**
     * The delete action request. is responsible for deleting a single record from
     * the database. This method is not a submittable method hence why this check has
     * been omitted. This a simple click based action. which is triggered within the
     * datatable. An event will be dispatch by this action
     */
    protected function hardDeleteAction()
    {
        $this->deleteAction
            ->setAccess($this, Access::CAN_DELETE)
            ->execute($this, NULL, PermissionActionEvent::class, NULL, __METHOD__);
    }

    /**
     * Bulk action route
     *
     * @return void
     */
    public function bulkAction()
    {
        $this->chooseBulkAction($this, PermissionActionEvent::class);
    }

    protected function searchAction()
    {
        die('working');
    }

    protected function settingsAction()
    {
        $sessionData = $this->getSession()->get($this->thisRouteController() . '_settings');
        $this->sessionUpdateAction
            ->setAccess($this, Access::CAN_MANANGE_SETTINGS)
            ->execute($this, NULL, PermissionActionEvent::class, NULL, __METHOD__, [], [], ControllerSessionBackupModel::class)
            ->render()
            ->with(
                [
                    'session_data' => $sessionData,
                    'page_title' => 'Permission Settings',
                    'last_updated' => $this->controllerSessionBackupModel
                        ->getRepo()
                        ->findObjectBy(['controller' => $this->thisRouteController() . '_settings'], ['created_at'])->created_at
                ]
            )
            ->form($this->controllerSettingsForm, null, $this->toObject($sessionData))
            ->end();
    }


}
