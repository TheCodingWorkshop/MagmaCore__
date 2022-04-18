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

namespace MagmaCore\Widget\Widgets\Notifications;

use MagmaCore\IconLibrary;
use MagmaCore\Widget\Widget;
use MagmaCore\Widget\Widgets\BaseWidget;
use MagmaCore\Widget\Widgets\WidgetBuilderInterface;
use MagmaCore\DataObjectLayer\ClientRepository\ClientRepositoryInterface;

class NotificationWidget extends Widget implements WidgetBuilderInterface
{

    /* @var string the widget name */
    public const WIDGET_NAME = 'notification_widget';

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
            $widgetID = (isset($widgetData) && is_array($widgetData) && array_key_exists('id', $widgetData)) ? $widgetData['id'] : '';
            return $baseWidget::offCanvas(
                function($base) use ($clientRepo, $widgetID, $widgetData) {
                    return sprintf('%s', self::notifier($clientRepo, $widgetData));
                },
                $widgetID
            );
        }
    }

    private static function notifier(object $clientRepo = null, mixed $widgetData)
    {
        return sprintf('
          <div>
            <div class="uk-card-header">
                <div class="uk-grid-small uk-flex-middle" uk-grid>
                    <div class="uk-width-auto">
                        %s
                    </div>
                    <div class="uk-width-expand">
                        <h3 class="uk-card-title uk-text-bolder uk-margin-remove-bottom">Notifications</h3>
                        <p class="uk-text-meta uk-margin-remove-top">
                            <time datetime="2016-04-01T19:00">Last updated 30min ago</time>
                        </p>
                    </div>
                </div>
            </div>
            <div class="uk-card-body uk-padding-small">
            %s
            </div>
            %s
        </div>',
            IconLibrary::getIcon('bell', 3.5),
            self::resolveNotifierLists($clientRepo, $widgetData),
            self::panelAction($clientRepo)
        );


    }

    private static function resolveNotifierLists(object $clientRepo = null, mixed $widgetData)
    {
        $limit = (isset($widgetData) && is_array($widgetData) && array_key_exists('limit', $widgetData)) ? $widgetData['limit'] : '';
        $orderby = (isset($widgetData) && is_array($widgetData) && array_key_exists('orderby', $widgetData)) ? $widgetData['orderby'] : '';
        $notifier = $clientRepo->getClientCrud()->read(['notify_title', 'notify_description', 'id'], ['notify_status' => 'unread'], ['limit' => $limit, 'offset' => 0], ['orderby' => $orderby]);
        $html = '';
        $html .= '<ul uk-tab>';
            $html .= '<li><a href="#">Recent</a></li>';
            $html .= '<li><a href="#">Activities</a></li>';
        $html .= '</ul>';

        $html .= '<ul class="uk-switcher uk-margin">';
            $html .= '<li>';
                if (count($notifier) > 0) {
                    $html .= '<ul class="uk-list uk-list-divider">';
                        $count = 0;
                        $num = 0;
                        foreach ($notifier as $key => $value) {
                            $num++;
                            $html .= '<li><a class="uk-link-reset" href="/admin/notification/' . $value['id'] . '/show">' . $value['notify_description'] . ' @ ' . $value['created_at'] . '</a></li>';
                            $count++;
                            if ($count === 3) {
                                break;
                            }
                        }
                    $html .= '</ul>';
                } else {
                    $html .= 'No new notifications';
                }
            $html .= '</li>';
            $html .= '<li>Ut enim ad minim veniam, quis nostrud exercitation.</li>';
        $html .= '</ul>';

        return $html;

    }

    private static function panelAction(object $clientRepo = null)
    {
        return sprintf(
            '
            <div class="uk-position-bottom uk-margin">
               <div class="uk-position-center">
                    <ul class="uk-iconnav">
                        <li><a uk-tooltip="Notification Settings" class="uk-link-reset" href="/admin/notification/settings">%s</a></li>
                        <li><a uk-tooltip="Clear All" class="uk-link-reset" href="#">%s</a></li>
                        <li><a uk-tooltip="View All" class="uk-link-reset" href="#">%s <sup class="uk-badge">%s</sup></a></li>
                    </ul>               
                </div>
            </div>
            
            ',
            IconLibrary::getIcon('cog', 1.0),
            IconLibrary::getIcon('trash', 1.0),
            IconLibrary::getIcon('bell', 1.0),
            $clientRepo->getClientCrud()->countRecords(['notify_status' => 'unread'])
        );
    }


}
