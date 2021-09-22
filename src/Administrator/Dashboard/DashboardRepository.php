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
use JetBrains\PhpStorm\ArrayShape;
use MagmaCore\Numbers\Number;

class DashboardRepository
{

    private UserModel $user;
    private Number $number;

    public function __construct(UserModel $user, Number $number)
    {
        $this->user = $user;
        $this->number = $number;
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


    #[ArrayShape(['privilege' => "string[]", 'static_pages' => "string[]", 'privileges' => "string[]", 'extension' => "string[]"])] public function getQuickLinks(): array
    {
        return [
            'privilege' => ['name' => 'Create new privileges', 'path' => '/admin/role/new'],
            'static_pages' => ['name' => 'Add some static pages', 'path' => '/admin/page/new'],
            'privileges' => ['name' => 'View your site', 'path' => '/'],
            'extension' => ['name' => 'Configure extensions', 'path' => '']
        ];
    }

    #[ArrayShape(['user' => "array", 'page' => "array", 'attachment' => "array", 'unread_message' => "array"])] public function getStatistics(): array
    {
        return [
            'user' => ['icon' => 'person', 'counter' => 1.2, 'percentage' => 8],
            'page' => ['icon' => 'document-text', 'counter' => 1.3, 'percentage' => 13],
            'attachment' => ['icon' => 'document-attach', 'counter' => 1.5, 'percentage' => 2.5],
            'unread_message' => ['icon' => 'mail-unread', 'counter' => 1.0, 'percentage' => 5.3]
        ];
    }

    #[ArrayShape(['branch' => "array", 'pull' => "array", 'commit' => "array", 'merge' => "array"])] public function getGithubStats(): array
    {
        return [
            'branch' => ['icon' => 'git-branch', 'counter' => 1.2, 'percentage' => 8],
            'pull' => ['icon' => 'git-pull-request', 'counter' => 1.3, 'percentage' => 13],
            'commit' => ['icon' => 'git-commit', 'counter' => 1.5, 'percentage' => 189],
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
    #[ArrayShape(['active' => "array", 'pending' => "array", 'lock' => "array", 'trash' => "array"])] public function userPercentage(): array
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

    #[ArrayShape(['Team' => "array", 'Tasks' => "array", 'Events' => "array"])] public function mainCards(): array
    {
        return [
            'Team' => [
                'icon' => 'people-outline',
                'path' => '/admin/team/index',
                'desc' => [
                    'Found 2 superadmin account and 1 contributor account.',
                ]
            ],
            'Tasks' => [
                'icon' => 'clipboard-outline',
                'path' => '/admin.task/index',
                'desc' => [
                    'There are 12 incomplete tasks and 3 completed tasks.'
                ]
            ],
            'Events' => [
                'icon' => 'calendar-outline',
                'path' => '/admin/event/index',
                'desc' => [
                    '2 events coming up next week'
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
    #[ArrayShape(['tv' => "array", 'on' => "array"])] public function userSession(): array
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


    #[ArrayShape(['home' => "string[]", 'session' => "string[]", 'github' => "string[]", 'health' => "string[]", 'activities' => "string[]", 'project' => "string[]", 'notifications' => "string[]"])] public function getNavSwitcher(): array
    {
        return [
            'home' => ['icon' => 'home-outline', 'include' => 'block_links'],
            'session' => ['icon' => 'stats-chart-outline', 'include' => 'block_statistics'],
            'github' => ['icon' => 'logo-github', 'include' => 'block_github'],
            'health' => ['icon' => 'pulse-outline', 'include' => 'block_health_status'],
            'activities' => ['icon' => 'receipt-outline', 'include' => 'block_activities'],
            'project' => ['icon' => 'git-branch-outline', 'include' => 'block_project'],
            'notifications' => ['icon' => 'notifications-outline', 'include' => 'block_notifications'],
        ];
    }

    #[ArrayShape(['Security' => "string[]", 'Report' => "string[]", 'Settings' => "string[]"])] public function getBlockActivities(): array
    {
        return [
            'Security' => [
                'icon' => 'lock-closed-outline',
                'path' => '/admin/security/index',
                'desc' => 'Ensure your application is protected by completing these steps.'
            ],
            'Report' => [
                'icon' => 'hardware-chip-outline',
                'path' => '/admin.task/index',
                'desc' => 'System reports gathers information about your application environment.'
            ],
            'Settings' => [
                'icon' => 'settings-outline',
                'path' => '/admin/event/index',
                'desc' => 'Settings page allows customization of your application.'
            ]
        ];
    }
}
