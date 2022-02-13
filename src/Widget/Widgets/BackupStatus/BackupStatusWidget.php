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

namespace MagmaCore\Widget\Widgets\BackupStatus;

use MagmaCore\IconLibrary;
use MagmaCore\Widget\Widget;
use MagmaCore\Widget\Widgets\BaseWidget;
use MagmaCore\Widget\Widgets\WidgetBuilderInterface;
use MagmaCore\DataObjectLayer\ClientRepository\ClientRepositoryInterface;
use MagmaCore\Utility\Utilities;

class BackupStatusWidget extends Widget implements WidgetBuilderInterface
{   

    /* @var string the widget name */
    public const WIDGET_NAME = 'backup_status_widget';

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
        /* We are only double checking we are on the correct widget */
        if ($widgetName === self::WIDGET_NAME) {
            return $baseWidget::card(function($base) use ($clientRepo) {

                $updates = $clientRepo->get(['old_version' => '1.3']);
                $updates = Utilities::flattenContext($updates);

                return sprintf(
                    '   
                    <div class="uk-card-header">
                        <div class="uk-grid-small uk-flex-middle" uk-grid>
                            <div class="uk-width-auto">
                                %s
                            </div>
                            <div class="uk-width-expand">
                                <h3 class="uk-card-title uk-text-bolder uk-margin-remove-bottom">%s</h3>
                                <p class="uk-text-meta uk-margin-remove-top">
                                    <time datetime="%s">Last backup %s</time>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="uk-card-body">
                        <p>%s</p>
                        <div class="uk-grid-small" uk-grid>
                            <div class="uk-width-expand" uk-leader>Version</div>
                            <div>%s</div>
                        </div>
    
                    </div>
                    ',
                    IconLibrary::getIcon('cloud-download', 3.5),
                    'Update & Backup',
                    '2016-04-01T19:00',
                    '2 days ago',
                    'Your\'re using the most upto date version of MagmaCore framework',
                    $updates['new_version'],
                );
            },
            ''
            );
        }        
    }

}
