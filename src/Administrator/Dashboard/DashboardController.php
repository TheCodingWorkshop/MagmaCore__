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

use MagmaCore\Administrator\Dashboard\DashboardRepository;
use MagmaCore\Base\Exception\BaseInvalidArgumentException;

class DashboardController extends \MagmaCore\Administrator\Controller\AdminController
{
    public DashboardRepository $repository;

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
                'repository' => DashboardRepository::class,
            ]
        );

    }

    /**
     * Entry method which is hit on request. This method should be implement within
     * all sub controller class as a default landing point when a request is
     * made.
     */
    protected function indexAction()
    {

        //$this->setAccess($this, 'can_view');
        $this->render(
            'admin/dashboard/index.html',
            [
                "links" => $this->repository->getQuickLinks(),
                'statistics' => $this->repository->getStatistics(),
                'user_percentage' => $this->repository->userPercentage(),
                'user_session' => $this->repository->userSession(),
                'user_gained' => /*$this->repository->countlastMonthUsers()*/ 0,
                'total_records' => $this->repository->totalUsers(),
                'pending_users' => $this->repository->totalPendingUsers(),
                'github' => $this->repository->getGithubStats(),
                'nav_switcher' => $this->repository->getNavSwitcher(),
                'main_cards' => $this->repository->mainCards(),
                'unique_visits' => $this->repository->getSessionUniqueVisits(),
                'block_activities' => $this->repository->getBlockActivities(),
            ]
        );
    }



}