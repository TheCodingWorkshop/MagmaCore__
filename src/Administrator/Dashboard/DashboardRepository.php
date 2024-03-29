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

use MagmaCore\UserManager\UserModel;
use MagmaCore\Numbers\Number;
use MagmaCore\Ticket\TicketModel;

class DashboardRepository
{

    private UserModel $user;
    private Number $number;
    private TicketModel $ticketModel;

    public function __construct(UserModel $user, Number $number, TicketModel $ticketModel)
    {
        $this->user = $user;
        $this->number = $number;
        $this->ticketModel = $ticketModel;
        $this->number->addNumber($this->user->getRepo()->count());
    }

    public function countlastMonthUsers(): int|false
    {
        $query = $this->user->getRepo()->getEm()->getCrud();
        $sql = "SELECT COUNT(*) as created_at 
        FROM {$this->user->getSchema()}
        WHERE created_at BETWEEN DATE_SUB(DATE_SUB(CURRENT_DATE(), INTERVAL DAY(CURRENT_DATE())-1 DAY), INTERVAL 1 MONTH)
        AND DATE_SUB(CURRENT_DATE(), INTERVAL DAY(CURRENT_DATE()) DAY)";

        //$result = $query->rawQuery($sql, [], 'column');
        return false;

    }

    public function countCurrentWeekUsers(): string|false
    {
        $query = $this->user->getRepo()->getEm()->getCrud();
        $sql = "SELECT COUNT(*) as created_at 
        FROM {$this->user->getSchema()}
        WHERE WHERE created_at BETWEEN SUBDATE(CURRENT_DATE(), INTERVAL WEEKDAY(CURRENT_DATE()) DAY)
  AND CURRENT_DATE()";

        $result = $query->rawQuery($sql, [], 'column');
        if ($result !==null) {
            return $result;
        }
        return false;

    }


    public function getQuickLinks(): array
    {
        return [
            'privilege' => ['name' => 'Create new privileges', 'path' => '/admin/role/new'],
            'static_pages' => ['name' => 'Add some static pages', 'path' => '/admin/page/new'],
            'privileges' => ['name' => 'View your site', 'path' => '/'],
            'extension' => ['name' => 'Configure extensions', 'path' => '']
        ];
    }

    public function getStatistics(): array
    {
        return [
            'user' => ['icon' => 'user', 'counter' => 1.2, 'percentage' => 8],
            'page' => ['icon' => 'file-text', 'counter' => 1.3, 'percentage' => 13],
            'attachment' => ['icon' => 'cloud-upload', 'counter' => 1.5, 'percentage' => 2.5],
            'unread_message' => ['icon' => 'mail', 'counter' => 1.0, 'percentage' => 5.3]
        ];
    }

    public function getGithubStats(): array
    {
        return [
            'branch' => ['icon' => 'git-branch', 'counter' => 1.2, 'percentage' => 8],
            'pull' => ['icon' => 'pull', 'counter' => 1.3, 'percentage' => 13],
            'commit' => ['icon' => 'push', 'counter' => 1.5, 'percentage' => 189],
            'merge' => ['icon' => 'git-merge', 'counter' => 1.0, 'percentage' => 5.3]
        ];
    }

    /**
     * Return the total records from the users database table
     *
     * @return integer
     */
    public function totalUsers(): int
    {
        return $this->user->getRepo()->count();
    }

    /**
     * Get the total number of pending users from the database table
     *
     * @return integer|false
     */
    public function totalPendingUsers(): int|false
    {
        $count = $this->user->getRepo()->count(['status' => 'pending'], $this->user->getSchema());
        if ($count) {
            return $count;
        }
        return false;
    }

    /**
     * Gte the total number of active users from the database table
     *
     * @return int|false
     */
    public function totalActiveUsers(): int|false
    {
        $count = $this->user->getRepo()->count(['status' => 'active'], $this->user->getSchema());
        if ($count) {
            return $count;
        }
        return false;
    }

    /**
     * Gte the total number of lock users from the database table
     *
     * @return int|false
     */
    public function totalLockedUsers(): int|false
    {
        $count = $this->user->getRepo()->count(['status' => 'lock'], $this->user->getSchema());
        if ($count) {
            return $count;
        }
        return false;
    }

    /**
     * Gte the total number of trash users from the database table
     *
     * @return int|false
     */
    public function totalTrashUsers(): int|false
    {
        $count = $this->user->getRepo()->count(['status' => 'trash'], $this->user->getSchema());
        if ($count) {
            return $count;
        }
        return false;
    }


    /**
     * Return an percentage array of the pending and active users against the total
     * records of users account
     *
     * @return array
     */
    public function userPercentage(): array
    {
        $this->number->addNumber($this->totalUsers());
        $activeUsers = $this->number->percentage($this->totalActiveUsers());
        $pendingUsers = $this->number->percentage($this->totalPendingUsers());
        $lockedUsers = $this->number->percentage($this->totalLockedUsers());
        $trashUsers = $this->number->percentage($this->totalTrashUsers());
        return [
            'active' => ['percentage' => $this->number->format($activeUsers)],
            'pending' => ['percentage' => $this->number->format($pendingUsers)],
            'lock' => ['percentage' => $this->number->format($lockedUsers)],
            'trash' => ['percentage' => $this->number->format($trashUsers)]
        ];
    }

    public function mainCards(): array
    {
        return [
            'Tickets' => [
                'icon' => 'tag',
                'path' => '/admin/user/team',
                //'label' => 'danger',
                'desc' => [
                    '15+ new tickets assigned to you. By Admin 1d ago.',
                ]
            ],
            'Tasks' => [
                'icon' => 'push',
                'path' => '/admin/task/index',
                //'label' => 'danger',
                'desc' => [
                    'You have 12 incomplete tasks and 3 completed tasks.'
                ]
            ],
            'Events' => [
                'icon' => 'calendar',
                'path' => '/admin/event/index',
                //'label' => 'danger',
                'desc' => [
                    'You have 2 events coming up this week'
                ]
            ]
        ];
    }

    /**
     * Return an percentage array of the pending and active users against the total
     * records of users account
     *
     * @return array
     */
    public function userSession(): array
    {
        return [
            'tv' => ['count' => 2.6, 'name' => 'Total Visits'],
            'on' => ['count' => 9.6, 'name' => 'Online'],
        ];
    }

    public function getSessionUniqueVisits(): float
    {
        return 1.4;
    }


    public function getNavSwitcher(): array
    {
        return [
            'activities' => ['icon' => 'rss', 'include' => 'block_just_now'],
            // 'members' => ['icon' => 'user', 'include' => 'block_links'],
            // 'ticket' => ['icon' => 'tag', 'include' => 'block_ticket'],
            // 'session' => ['icon' => 'history', 'include' => 'block_statistics'],
            // 'comments' => ['icon' => 'comments', 'include' => 'block_threaded_comments'],
            // 'project' => ['icon' => 'git-branch', 'include' => 'block_project'],
            //'lifesaver' => ['icon' => 'lifesaver', 'include' => 'block_health_status'],

        ];
    }

    public function getBlockActivities(): array
    {
        return [
            'Security' => [
                'icon' => 'lock',
                'path' => '/admin/security/index',
                'desc' => ['Ensure your application is protected by completing these steps.']
            ],
            'Report' => [
                'icon' => 'file-edit',
                'path' => '/admin/task/index',
                'desc' => ['System reports gathers information about your application environment.']
            ],
            'Settings' => [
                'icon' => 'settings',
                'path' => '/admin/event/index',
                'desc' => ['Settings page allows customization of your application.']
            ]
        ];
    }

    public function ticketCounter()
    {
        $count = $this->ticketModel->getRepo();
        return [
            'tickets_today' => $count->getEm()->getCrud()->rawQuery('SELECT count(*) FROM `tickets` WHERE DATE(created_at) = :created_at', ['created_at' => 'CURDATE()']),
            'open' => $count->count(['status' => 'open']),
            'closed' => $count->count(['status' => 'closed']),
            'resolved' => $count->count(['status' => 'resolved']),
            'all_tickets' => $count->count()
        ];
    }

    /**
     * Porgress bar. An  additional key can be submitted (max) key. This is set to 100 by default within the widget
     * so unless the array before wants to specify a different max value. We can omit this key from the array
     *
     * @return array
     */
    public function getProgressBarData(): array
    {
        return [
            'page_views' => [
                'title' => 'Page Views',
                'quantity' => '+50',
                'progress' => '',
                'value' => 50,
            ],
            'total_active' => [
                'title' => 'Total Active',
                'quantity' => '+78',
                'progress' => 'success',
                'value' => 78,
            ],
            'active_session' => [
                'title' => 'Active Session',
                'quantity' => '12',
                'progress' => 'warning',
                'value' => 12,
            ]


        ];
    }

    public function getMoneyCardData(): array
    {
        return [
            'registered_users' => [
                'title' => 'Registered Users',
                'icon' => 'users',
                'value' => '1.4k',
                'percentage_position' => 'up',
                'percentage_value' => '15%',
                'percentage_string' => 'more than last week',
                'percentage_label' => 'success'
            ],
            'social_media' => [
                'title' => 'Social Media',
                'icon' => 'social',
                'value' => '8.490',
                'percentage_position' => 'down',
                'percentage_value' => '-15%',
                'percentage_string' => 'more than last week',
                'percentage_label' => 'warning'
            ],
            'traffic_hours' => [
                'title' => 'Traffic Hours',
                'icon' => 'clock',
                'value' => '12:00',
                'percentage_position' => 'up',
                'percentage_value' => '19%',
                'percentage_string' => 'more than last week',
                'percentage_label' => 'success'
            ],
            'week_search' => [
                'title' => 'Week Search',
                'icon' => 'search',
                'value' => '9.432',
                'percentage_position' => 'down',
                'percentage_value' => '-23%',
                'percentage_string' => 'less than last week',
                'percentage_label' => 'danger'
            ],
            'monthly_report' => [
                'title' => 'Monthly Report',
                'icon' => 'file',
                'value' => '9.432',
                'percentage_position' => 'up',
                'percentage_value' => '39%',
                'percentage_string' => 'more than last week',
                'percentage_label' => 'success',
                'hidden' => 'uk-visible@xl'
            ]

        ];
    }

}
