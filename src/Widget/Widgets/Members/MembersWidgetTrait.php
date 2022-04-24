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

namespace MagmaCore\Widget\Widgets\Members;

use MagmaCore\Numbers\Number;

trait MembersWidgetTrait
{

    /**
     * Return the total records from the users database table
     *
     * @return integer
     */
    private static function totalUsers($clientRepo): int
    {
        return $clientRepo->countRecords();
    }


    /**
     * Get the total number of pending users from the database table
     *
     * @return integer|false
     */
    private static function totalPendingUsers($clientRepo): int|false
    {
        $count = $clientRepo->countRecords(['status' => 'pending']);
        if ($count) {
            return $count;
        }
        return 0;
    }


    /**
     * Gte the total number of lock users from the database table
     *
     * @return int|false
     */
    private static function totalLockedUsers($clientRepo): int|false
    {
        $count = $clientRepo->countRecords(['status' => 'lock']);
        if ($count) {
            return $count;
        }
        return 0;
    }

    /**
     * Gte the total number of active users from the database table
     *
     * @return int|false
     */
    private static function totalActiveUsers($clientRepo): int|false
    {
        $count = $clientRepo->countRecords(['status' => 'active']);
        if ($count) {
            return $count;
        }
        return 0;
    }


    /**
     * Gte the total number of trash users from the database table
     *
     * @return int|false
     */
    private static function totalTrashUsers($clientRepo): int|false
    {
        $count = $clientRepo->countRecords(['status' => 'trash']);
        if ($count) {
            return $count;
        }
        return 0;
    }

    /**
     * Return an percentage array of the pending and active users against the total
     * records of users account
     *
     * @return array
     */
    private static function userPercentage($clientRepo): array
    {
        $number = new Number();
        $number->addNumber(self::totalUsers($clientRepo->getClientCrud()));
        $activeUsers = $number->percentage(self::totalActiveUsers($clientRepo->getClientCrud()));
        $pendingUsers =$number->percentage(self::totalPendingUsers($clientRepo->getClientCrud()));
        $lockedUsers = $number->percentage(self::totalLockedUsers($clientRepo->getClientCrud()));
        $trashUsers = $number->percentage(self::totalTrashUsers($clientRepo->getClientCrud()));
        return [
            $number->format($activeUsers),
            $number->format($pendingUsers),
            $number->format($lockedUsers),
            $number->format($trashUsers)
        ];
    }

}