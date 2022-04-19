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

namespace MagmaCore\Widget\Widgets\Tickets;

use MagmaCore\IconLibrary;
use MagmaCore\Widget\Widget;
use MagmaCore\Widget\Widgets\BaseWidget;
use MagmaCore\Widget\Widgets\WidgetBuilderInterface;
use MagmaCore\DataObjectLayer\ClientRepository\ClientRepositoryInterface;
use MagmaCore\Utility\DateFormatter;
use MagmaCore\Utility\Utilities;

class TicketWidget extends Widget implements WidgetBuilderInterface
{   

    /* @var string the widget name */
    public const WIDGET_NAME = 'ticket_widget';

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

                $total = $clientRepo->getClientCrud()->countRecords();
                //$lastTicketID = $clientRepo->getClientCrud()->lastID();
                /*
                @todo - add the last ticket ID to the session through event dispatching 
                then retrive it here
                */
                $data = $clientRepo->get(['id' => 1]);
                $data = Utilities::flattenContext($data);
                return sprintf(
                    '   
                    <div class="uk-card-header">
                        <div class="uk-grid-small uk-flex-middle" uk-grid>
                            <div class="uk-width-auto">
                                %s
                            </div>
                            <div class="uk-width-expand">
                                <h3 class="uk-card-title uk-text-bolder uk-margin-remove-bottom">%s Tickets</h3>
                                <p class="uk-text-meta uk-margin-remove-top">
                                    <time datetime="%s">Last ticket %s</time>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="uk-card-body">
                        <p>Theres currently %s tickets.</p>
                        %s
                    </div>
                    ',
                    IconLibrary::getIcon('tag', 3.5),
                    $total, /* all tickets */
                    $data['created_at'],
                    DateFormatter::timeFormat($data['created_at']),
                    $total,
                    self::resolveTickets($clientRepo)
                );
            },
            'secondary'
            );
        }        
    }

    /**
     * Access the tickets database table to retrive the ticket count for each status
     * and priority
     *
     * @param object $clientRepo
     * @return void
     */
    private static function resolveTickets(object $clientRepo)
    {
        $crud = $clientRepo->getClientCrud();
        $critical = $crud->countRecords(['priority' => 'critical', 'status' => 'open']);
        return sprintf(
            '<ul class="uk-list uk-list-collapse uk-list-divider">
                <li>today\'s %s</li>
                <li>%s Open <small class="uk-text-danger">(%s critical)</small></li>
                <li>%s Closed</li>
                <li>%s Resolved</li>
            </ul>',
            $crud->rawQuery('SELECT count(*) FROM `tickets` WHERE DATE(created_at) = :created_at', ['created_at' => 'CURDATE()']),
            $crud->countRecords(['status' => 'open']),
            $critical,
            $crud->countRecords(['status' => 'closed']),
            $crud->countRecords(['status' => 'resolved'])

        );
    }

}
