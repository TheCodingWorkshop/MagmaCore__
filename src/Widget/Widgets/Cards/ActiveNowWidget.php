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

class ActiveNowWidget extends Widget implements WidgetBuilderInterface
{   

    /* @var string the widget name */
    public const WIDGET_NAME = 'active_now_widget';

    /**
     * @inheritDoc
     */
    public static function render(?string $widgetName = null, ClientRepositoryInterface $clientRepo, BaseWidget $baseWidget, mixed $widgetData = null): string
    {
        if ($widgetName === self::WIDGET_NAME) {
            //$data = $clientRepo->get(['id' => 1270]);
            //$data = Utilities::flattenContext($data);
            return $baseWidget::card(function($base) use ($widgetData) {
                return sprintf(
                    '            
                    <div class="uk-clearfix">
                        <div class="uk-float-left">
                            <h3 class="uk-card-title uk-text-bolder uk-margin-remove">%s</h3>
                            <span class="uk-margin-remove uk-text-meta uk-text-wrap">%s</span>
        
                            <span class="statistics-number">
                                %s
                                <span class="uk-label uk-label-success">
                                    %s <span class="ion-arrow-%s"></span>
                            </span>
                            </span>
        
                        </div>
                        <div class="uk-float-right">
                            <div id="active_now_pie"></div>
                        </div>
                        
                    </div>
                    ',
                    'Active Now',
                    'Last 28 days',
                    '1.54k',
                    '8%',
                    'up-c',
                );
            },
            'default'
            );
        }        
    }

}
