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

namespace MagmaCore\Notification;

use MagmaCore\Base\Access;
use MagmaCore\DataObjectLayer\DataLayerTrait;
use MagmaCore\Base\Traits\ControllerCommonTrait;
use MagmaCore\Base\Exception\BaseInvalidArgumentException;
use MagmaCore\Notification\Event\NotificationActionEvent;
use MagmaCore\PanelMenu\EventSubscriber\MenuActionSubscriber;

class NotificationController extends \MagmaCore\Administrator\Controller\AdminController
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
                'repository' => NotificationModel::class,
                'commander' => NotificationCommander::class,
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
        return NotificationSchema::class;
    }

    protected function indexAction()
    {
        $this->render('admin/notification/index.html', []);
    }

    protected function showAction()
    {
        $this->showAction
            ->execute($this, NULL, NotificationActionEvent::class, NULL, __METHOD__)
            ->render()
            ->with()
            ->singular()
            ->end();
    }

    protected function settingsAction()
    {
        $this->render('admin/notification/settings.html', []);
//        $sessionData = $this->getSession()->get($this->thisRouteController() . '_settings');
//        $this->sessionUpdateAction
//            ->setAccess($this, Access::CAN_MANANGE_SETTINGS)
//            ->execute($this, NULL, MenuActionEvent::class, NULL, __METHOD__, [], [], ControllerSessionBackupModel::class)
//            ->render()
//            ->with(
//                [
//                    'session_data' => $sessionData,
//                    'page_title' => 'Menu Settings',
//                    'last_updated' => $this->controllerSessionBackupModel
//                        ->getRepo()
//                        ->findObjectBy(['controller' => $this->thisRouteController() . '_settings'], ['created_at'])->created_at
//                ]
//            )
//            ->form($this->controllerSettingsForm, null, $this->toObject($sessionData))
//            ->end();

    }


}

