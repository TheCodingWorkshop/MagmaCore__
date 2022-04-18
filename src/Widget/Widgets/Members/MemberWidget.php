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

use MagmaCore\IconLibrary;
use MagmaCore\Widget\Widget;
use MagmaCore\Widget\Widgets\BaseWidget;
use MagmaCore\Widget\Widgets\WidgetBuilderInterface;
use MagmaCore\DataObjectLayer\ClientRepository\ClientRepositoryInterface;
use MagmaCore\Numbers\Number;
use MagmaCore\Utility\Utilities;

class MemberWidget extends Widget implements WidgetBuilderInterface
{   

    /* @var string the widget name */
    public const WIDGET_NAME = 'member_widget';

    /**
     * Render the widget
     *
     * @param string|null $widgetName
     * @param ClientRepositoryInterface $clientRepo
     * @param BaseWidget $baseWidget
     * @return string
     */
    public static function render(?string $widgetName = null, ClientRepositoryInterface $clientRepo, BaseWidget $baseWidget, mixed $widgetData = null): string
    {
        if ($widgetName === self::WIDGET_NAME) {
            return $baseWidget::card(function($base) use ($clientRepo, $widgetData) {

                $total = self::totalUsers($clientRepo->getClientCrud());
                return sprintf(
                    '   
                    <div class="uk-card-header">
                        <div class="uk-grid-small uk-flex-middle" uk-grid>
                            <div class="uk-width-auto">
                                %s
                            </div>
                            <div class="uk-width-expand">
                                <h3 class="uk-card-title uk-text-bolder uk-margin-remove-bottom">%s Users</h3>
                                <p class="uk-text-meta uk-margin-remove-top">
                                    <span>You gained +%s new users in the last month</span>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="uk-card-body uk-text-white">
                        <p>%s Users still pending</p>
                        <ul class="uk-list uk-list-collapse uk-list-divider">
                        %s
                        </ul>
                    </div>
                    ',
                    IconLibrary::getIcon('user', 3.5),
                    $total, /* all tickets */
                    27, /* gained users */
                    self::totalPendingUsers($clientRepo->getClientCrud()) ?? 0,
                    self::resolver($clientRepo)
                );
            },
            'secondary'
            );
        }        
    }

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



    private static function resolver(object $clientRepo)
    {
        list($active, $pending, $lock, $trash) = self::userPercentage($clientRepo);
        return sprintf(
            '
            <li>%s Active</li>
            <li>%s Pending <small class="uk-text-danger">(critical)</small></li>
            <li>%s Locked</li>
            <li>%s Trashed</li>    
            ',
            ceil((float)$active) . '%',
            ceil((float)$pending) . '%',
            ceil((float)$lock) . '%',
            ceil((float)$trash) . '%'
        );
    }

}
