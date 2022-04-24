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

class MoneyCardWidget extends Widget implements WidgetBuilderInterface
{   

    /* @var string the widget name */
    public const WIDGET_NAME = 'money_card_widget';
    private const LABEL = '';


    /**
     * @inheritDoc
     */
    public static function render(?string $widgetName = null, ClientRepositoryInterface $clientRepo, BaseWidget $baseWidget, mixed $widgetData = null): string
    {
        if ($widgetName === self::WIDGET_NAME) {
            return $baseWidget::gridWrapper(function($base) use ($widgetData, $clientRepo) {
                if (array($widgetData) && count($widgetData) > 0) {
                    $output = '';
                    foreach ($widgetData as $key => $value) {
                        $output .= sprintf(
                            '
                            <div class="%s %s">
                                <span class="uk-text-small"><span data-uk-icon="icon:%s" class="uk-margin-small-right uk-text-secondary"></span>%s</span>
                                <h1 class="uk-heading-secondary uk-margin-remove  uk-text-secondary uk-text-bolder">%s</h1>
                                <div class="uk-text-small">
                                    <span class="uk-text-%s" data-uk-icon="icon: triangle-%s">%s</span> %s.
                                </div>
                            </div>
                            ',

                            $key,
                            $value['hidden'] ? $value['hidden'] : '',
                            $value['icon'],
                            $value['title'],
                            $value['value'],
                            $value['percentage_label'],
                            $value['percentage_postition'],
                            $value['percentage_value'],
                            $value['percentage_string'],
                        );
                    }

                    return $output;
                }
            }, null);
        }        
    }

}
