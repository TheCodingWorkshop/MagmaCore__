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

namespace MagmaCore\Widget\Widgets\Cards;

use MagmaCore\Widget\Widget;
use MagmaCore\Widget\Widgets\BaseWidget;
use MagmaCore\Widget\Widgets\WidgetBuilderInterface;
use MagmaCore\DataObjectLayer\ClientRepository\ClientRepositoryInterface;
use MagmaCore\Widget\Widgets\Members\MembersWidgetTrait;

class UsersWidget extends Widget implements WidgetBuilderInterface
{   

    use MembersWidgetTrait;

    /* @var string the widget name */
    public const WIDGET_NAME = 'users_widget';
    private const LABEL = 'Today\'s Gained';

    /**
     * @inheritDoc
     */
    public static function render(?string $widgetName = null, ClientRepositoryInterface $clientRepo, BaseWidget $baseWidget, mixed $widgetData = null): string
    {
        if ($widgetName === self::WIDGET_NAME) {            
            return $baseWidget::card(function($base) use ($widgetData, $clientRepo) {
                $total = self::totalUsers($clientRepo->getClientCrud());
                return sprintf(
                    '            
                    <div class="uk-clearfix">
                        <div class="uk-float-left">
                            <h3 class="uk-card-title uk-text-bolder uk-margin-remove">%s</h3>
                            <span class="uk-margin-remove uk-text-meta uk-text-wrap">%s</span>
        
                            <span class="uk-text-small">
                                %s
                                <span class="uk-text-warning uk-margin-left">
                                    %s <span uk-icon="icon: triangle-%s"></span>
                            </span>
                            </span>
        
                        </div>
                        <div class="uk-float-right">
                            <div id="users_widget"></div>
                        </div>
                        
                    </div>
                    %s
                    ',
                    'Members 12.7k',
                    self::LABEL,
                    '+263',
                    '8%',
                    'down',
                    self::script($clientRepo)
                );
            },
            'default'
            );
        }        
    }

    /**
     * Returns the sparklines graph for this card
     *
     * @param object $clientRepo
     * @return string
     */
    private static function script(object $clientRepo): string
    {
        $pending = self::totalPendingUsers($clientRepo->getClientCrud()) ?? 0;
        $active = self::totalActiveUsers($clientRepo->getClientCrud()) ?? 0;
        $trash = self::totalTrashUsers($clientRepo->getClientCrud()) ?? 0;
        $total = self::totalUsers($clientRepo->getClientCrud());
        $locked = self::totalLockedUsers($clientRepo->getClientCrud());

        $output = '
        <script>
        $("#users_widget").sparkline([' . $total . ', ' . $active . ', ' . $pending . ', -' . $trash . ', -' . $locked . '], {
            type: "bar",
            height: "60",
            barWidth: "10",
            barColor: "#222222"
        });
    
        </script>
        ';

        return $output;
    }

}
