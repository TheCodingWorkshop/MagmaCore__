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

namespace MagmaCore\Widget\Widgets\ListsWidget;

use MagmaCore\IconLibrary;
use MagmaCore\Widget\Widget;
use MagmaCore\Widget\Widgets\BaseWidget;
use MagmaCore\Widget\Widgets\WidgetBuilderInterface;
use MagmaCore\DataObjectLayer\ClientRepository\ClientRepositoryInterface;

class ListsWidget extends Widget implements WidgetBuilderInterface
{   

    /* @var string the widget name */
    public const WIDGET_NAME = 'lists_widget';

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
            return $baseWidget::card(function($base) use ($widgetData) {

                if (is_array($widgetData) && count($widgetData) > 0) {
                    foreach ($widgetData as $key => $value) {
                        return sprintf(
                            '   
                            <div class="uk-card-header">
                                <div class="uk-grid-small uk-flex-middle" uk-grid>
                                    <div class="uk-width-auto">
                                        <a data-turbo="false" href="%s" class="uk-link-reset">
                                        %s
                                        </a>
                                    </div>
                                    <div class="uk-width-expand">
                                        <h3 class="uk-card-title uk-text-bolder uk-margin-remove-bottom">%s</h3>
                                        <p class="uk-text-meta uk-margin-remove-top">
                                            %s
                                        </p>
                                    </div>
                                </div>
                            </div>
                            ',
                            $value['path'],
                            IconLibrary::getIcon($value['icon'], 2.2),
                            $key,
                            self::resolveDesc($value)
                        );
        
                    }
                }
            },
            ''
            );
        }        
    }

    private static function resolveDesc(array $value = [])
    {
        if (is_array($value['desc']) && count($value['desc']) > 0) {
            foreach ($value['desc'] as $desc) {
                return sprintf('<span>%s</span>', $desc);
            }
        }
    }

}
