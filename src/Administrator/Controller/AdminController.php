<?php
/*
 * This file is part of the MagmaCore package.
 *
 * (c) Ricardo Miller <ricardomiller@lava-studio.co.uk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare (strict_types=1);

namespace MagmaCore\Administrator\Controller;

use MagmaCore\Utility\Serializer;
use MagmaCore\Base\BaseController;
use MagmaCore\Datatable\Datatable;
use MagmaCore\RestFul\RestHandler;
use MagmaCore\Session\SessionTrait;
use MagmaCore\Base\Domain\Actions\NewAction;
use MagmaCore\Administrator\Forms\ExportForm;
use MagmaCore\Administrator\Forms\ImportForm;
use MagmaCore\Base\Domain\Actions\EditAction;
use MagmaCore\Base\Domain\Actions\ShowAction;
use MagmaCore\Base\Traits\TableSettingsTrait;
use MagmaCore\Base\Domain\Actions\BlankAction;
use MagmaCore\Base\Domain\Actions\CloneAction;
use MagmaCore\Base\Domain\Actions\IndexAction;
use MagmaCore\Base\Domain\Actions\DeleteAction;
use MagmaCore\Base\Domain\Actions\ExportAction;
use MagmaCore\Base\Domain\Actions\ImportAction;
use MagmaCore\Base\Domain\Actions\UpdateOnEvent;
use MagmaCore\Base\Domain\Actions\LogIndexAction;
use MagmaCore\Base\Domain\Actions\SettingsAction;
use MagmaCore\Base\Domain\Actions\ShowBulkAction;
use MagmaCore\Base\Domain\Actions\BulkCloneAction;
use MagmaCore\Administrator\ControllerSettingsForm;
use MagmaCore\Base\Domain\Actions\BulkDeleteAction;
use MagmaCore\Base\Domain\Actions\BulkUpdateAction;
use MagmaCore\Base\Domain\Actions\ChangeRowsAction;
use MagmaCore\Base\Domain\Actions\IfCanTrashAction;
use MagmaCore\Administrator\ControllerSettingsModel;
use MagmaCore\Administrator\ControllerSettingsEntity;
use MagmaCore\Base\Domain\Actions\ChangeStatusAction;
use MagmaCore\Base\Domain\Actions\SimpleCreateAction;
use MagmaCore\Base\Domain\Actions\SimpleUpdateAction;
use MagmaCore\UserManager\Forms\Admin\BulkDeleteForm;
use MagmaCore\Base\Domain\Actions\SessionUpdateAction;
use MagmaCore\Base\Exception\BaseInvalidArgumentException;
use MagmaCore\Administrator\Middleware\Before\LoginRequired;
use MagmaCore\Administrator\Middleware\Before\SessionExpires;
use MagmaCore\Administrator\Middleware\Before\AuthorizedIsNull;
use MagmaCore\Administrator\Model\ControllerSessionBackupModel;
use MagmaCore\Administrator\Event\ControllerSettingsActionEvent;
use MagmaCore\Administrator\Middleware\Before\AdminAuthentication;
use MagmaCore\Base\Traits\ControllerSessionTrait;
use MagmaCore\Base\Traits\SessionSettingsTrait;
use MagmaCore\System\Event\SystemActionEvent;

class AdminController extends BaseController
{

    use SessionTrait;
    use TableSettingsTrait;
    use SessionSettingsTrait;
    use ControllerSessionTrait;

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
        $this->diContainer(
            [
                'tableGrid' => Datatable::class,
                'blankAction' => BlankAction::class,
                'simpleUpdateAction' => SimpleUpdateAction::class,
                'simpleCreateAction' => SimpleCreateAction::class,
                'newAction' => NewAction::class,
                'editAction' => EditAction::class,
                'deleteAction' => DeleteAction::class,
                'bulkDeleteAction' => BulkDeleteAction::class,
                'bulkCloneAction' => BulkCloneAction::class,
                'bulkUpdateAction' => BulkUpdateAction::class,
                'showBulkAction' => ShowBulkAction::class,
                'indexAction' => IndexAction::class,
                'cloneAction' => CloneAction::class,
                'logIndexAction' => LogIndexAction::class,
                'showAction' => ShowAction::class,
                'updateOnEvent' => UpdateOnEvent::class,
                'changeStatusAction' => ChangeStatusAction::class,
                'settingsAction' => SettingsAction::class,
                'apiResponse' => RestHandler::class,
                'changeRowsAction' => ChangeRowsAction::class,
                'controllerSettingsForm' => ControllerSettingsForm::class,
                'controllerRepository' => ControllerSettingsModel::class,
                'bulkDeleteForm' => BulkDeleteForm::class,
                'ifCanTrashAction' => IfCanTrashAction::class,
                'sessionUpdateAction' => SessionUpdateAction::class,
                'controllerSessionBackupModel' => ControllerSessionBackupModel::class,
                'exportForm' => ExportForm::class,
                'importForm' => ImportForm::class,
                'exportAction' => ExportAction::class,
                'importAction' => ImportAction::class,

            ]
        );

    }

    /**
     * Middleware which are executed before any action methods is called
     * middlewares are return within an array as either key/value pair. Note
     * array keys should represent the name of the actual class its loading ie
     * upper camel case for array keys. alternatively array can be defined as
     * an index array omitting the key entirely
     *
     * @return array
     */
    protected function callBeforeMiddlewares(): array
    {
        return [
            'LoginRequired' => LoginRequired::class,
            'AdminAuthentication' => AdminAuthentication::class,
            'AuthorizedIsNull' => AuthorizedIsNull::class,
            'SessionExpires' => SessionExpires::class,
        ];
    }

    /**
     * After filter which is called after every controller. Can be used
     * for garbage collection
     *
     * @return array
     */
    protected function callAfterMiddlewares(): array
    {
        return [];
    }

    /**
     * Returns the method path as a string to use with the redirect method.
     * The method will generate the necessary redirect string based on the
     * current route.
     *
     * @param string $action
     * @param Object $controller
     * @return string
     */
    public function getRoute(string $action, object $controller): string
    {
        $self = '';
        if (!empty($this->thisRouteID()) && $this->thisRouteID() !== false) {
            if ($this->thisRouteID() === $this->findOr404()) {
                $route = "/{$this->thisRouteNamespace()}/{$this->thisRouteController()}/{$this->thisRouteID()}/{$this->thisRouteAction()}";
            }
        } else {
            $self = "/{$this->thisRouteNamespace()}/{$this->thisRouteController()}/{$action}";
        }

       // if ($self) {
            return $self;
        //}
    }

    /**
     * Checks whether the entity settings is being called from the correct
     * controller and return true. returns false otherwise
     *
     * @param string $autoController
     * @return boolean
     */
    private function isControllerValid(string $autoController): bool
    {
        if (is_array($this->routeParams) && in_array($autoController, $this->routeParams, true)) {
            if (isset($this->routeParams['controller']) && $this->routeParams['controller'] == $autoController) {
                return true;
            }
        }
        return false;
    }

    protected function settingsAction()
    {
        $this->editAction
            ->execute(
                $this, 
                ControllerSettingsEntity::class, 
                ControllerSettingsActionEvent::class, 
                NULL, 
                __METHOD__
            );
    }

    /**
     * Global 
     *
     * @return void
     */
    protected function changeRowAction()
    {
        $key = $this->thisRouteController() . '_settings';
        $newRecordsPerPage = (string)$this->request->handler()->query->getAlnum('rpp');
        $session = $this->getSessionData($key, $this);
        if ($session['records_per_page'] !== $newRecordsPerPage) {
            unset($session['records_per_page']);
            $updatedSession = ['records_per_page' => $newRecordsPerPage];
            $this->getSession()->delete('user_settings');
            $newArray = $session + $updatedSession;

            $this->getSession()->set($key, $newArray);
            
        }
        $this->flashMessage('updated!');
        $this->redirect($_SERVER['HTTP_REFERER']);

    }

    public function sessionModel(object $controller = null): ?object
    {
        return $this->controllerSessionBackupModel
        ->getRepo()
        ->findObjectBy(['controller' => $controller->thisRouteController() . '_settings']);

    }

    public function sessionModelContext(object $controller = null): array|bool
    {
        $context = $this->sessionModel($controller)->context;
        if ($context) {
            return Serializer::unCompress($context);
        }
        return false;
    }

    /**
     * Return the unserialized session data
     *
     * @param object|null $controller
     * @return mixed
     */
    public function controllerSessionData(object $controller = null): mixed
    {
        $serialized = $controller->getSession()->get($this->thisRouteController() . '_settings');
        if ($serialized) {
            return Serializer::unCompress($serialized);
        }

        return false;
    }


    protected function discoveryRefreshAction()
    {
      $this->pingMethods($this->thisRouteController(), sprintf('\App\Controller\Admin\%sController', ucwords($this->thisRouteController())));
      $this->redirect('/admin/discovery/discover');

    }
    protected function importAction()
    {
        $this->importAction
        ->execute($this, null, SystemActionEvent::class, $this->rawSchema, __METHOD__)
        ->render('admin/_global/_global_import.html')
        ->with(
            [
            ]
        )
        ->form($this->importForm)
        ->end();

    }

    protected function exportAction()
    {
        $this->exportAction
            ->execute($this, null, SystemActionEvent::class, $this->rawSchema, __METHOD__)
            ->render('admin/_global/_global_export.html')
            ->with(
                [
                    'export' => $this->getSessionSettings($this, 'session_export_settings')
                ]
            )
            ->form($this->exportForm)
            ->end();
    }



}
