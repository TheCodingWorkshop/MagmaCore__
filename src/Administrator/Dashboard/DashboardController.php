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

namespace MagmaCore\Administrator\Dashboard;

use MagmaCore\Base\Access;
use MagmaCore\Base\Domain\Actions\DashboardAction;
use MagmaCore\Administrator\Dashboard\DashboardSchema;
use MagmaCore\Administrator\Dashboard\DashboardRepository;
use MagmaCore\Base\Exception\BaseInvalidArgumentException;
use MagmaCore\Administrator\Dashboard\DashboardSettingsForm;
use MagmaCore\Asministrator\Dashboard\Event\DashboardActionEvent;

class DashboardController extends \MagmaCore\Administrator\Controller\AdminController
{

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
                'repository' => DashboardModel::class,
                'repo' => DashboardRepository::class, 
                'schema' => DashboardSchema::class,
                'dashboardAction' => DashboardAction::class,
                'dashboardSettingsForm' => DashboardSettingsForm::class
            ]
        );

    }

    public function schemaAsString(): string
    {
        return DashboardSchema::class;
    }

    /**
     * Entry method which is hit on request. This method should be implement within
     * all sub controller class as a default landing point when a request is
     * made.
     */
    protected function indexAction()
    {
        $this->render(
            'admin/dashboard/index.html',
            [
                // "links" => $this->repository->getQuickLinks(),
                // 'statistics' => $this->repository->getStatistics(),
                // 'user_percentage' => $this->repository->userPercentage(),
                // 'user_session' => $this->repository->userSession(),
                // 'user_gained' => /*$this->repository->countlastMonthUsers()*/ 0,
                // 'total_records' => $this->repository->totalUsers(),
                // 'pending_users' => $this->repository->totalPendingUsers(),
                // 'github' => $this->repository->getGithubStats(),
                'nav_switcher' => $this->repo->getNavSwitcher(),
                'main_cards' => $this->repo->mainCards(),
                // 'unique_visits' => $this->repository->getSessionUniqueVisits(),
                // 'block_activities' => $this->repository->getBlockActivities(),
                // 'ticket_count' => $this->repository->ticketCounter(),

                'todays_datetime' => date("F j, Y, g:i a")
            ]
        );
    }

    protected function datetimeAction()
    {
        $msg = date("F j, Y, g:i a");
        echo $msg;
    }

    protected function settingsAction()
    {
        $this->dashboardAction
            ->setAccess($this, Access::CAN_MANANGE_SETTINGS)
            ->execute($this, NULL, DashboardActionEvent::class, NULL, __METHOD__)
            ->render()
            ->with(
                [
                    'page_title' => 'Dashboard Settings'
                ]
            )
            ->form($this->dashboardSettingsForm)
            ->end();
    }

    protected function healthAction()
    {
        $this->dashboardAction
            ->setAccess($this, Access::CAN_MANANGE_SETTINGS)
            ->execute($this, NULL, DashboardActionEvent::class, NULL, __METHOD__)
            ->render()
            ->with(
                [
                    'page_title' => 'System Health'
                ]
            )
            ->info()
            ->end();
    }


}