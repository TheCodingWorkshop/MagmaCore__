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

namespace MagmaCore\System\App\DataColumns;

use MagmaCore\Datatable\AbstractDatatableColumn;
use MagmaCore\Utility\Stringify;

class SystemColumn extends AbstractDatatableColumn
{

    /**
     * @param array $dbColumns
     * @param object|null $callingController
     * @return array[]
     */
    public function columns(array $dbColumns = [], object|null $callingController = null): array
    {
        return [
            [
                'db_row' => 'id',
                'dt_row' => 'ID',
                'class' => 'uk-table-shrink',
                'show_column' => true,
                'sortable' => false,
                'searchable' => true,
                'formatter' => function ($row) {
                    return '<input type="checkbox" class="uk-checkbox" id="menus-' . $row['id'] . '" name="id[]" value="' . $row['id'] . '">';
                }
            ],
            [
                'db_row' => 'event_log_name',
                'dt_row' => 'Name',
                'class' => '',
                'show_column' => true,
                'sortable' => true,
                'searchable' => true,
                'formatter' => function ($row, $twigExt) {
                    $html = '<div class="uk-clearfix">';
                    $html .= '<div class="uk-float-left uk-margin-small-right">';
                    $html .= '<span class="uk-text-warning" uk-icon="icon: info"></span>';
                    $html .= '</div>';
                    $html .= '<div class="uk-float-left">';
                    $html .= Stringify::capitalize($row["event_type"]) . "<br/>";
                    $html .= '<div class=""><small>' . $row['event_log_name'] . ' | ' . $row["method"] . '</small></div>';
                    $html .= '</div>';
                    $html .= '</div>';

                    return $html;
                }
            ],
            [
                'db_row' => 'event_type',
                'dt_row' => 'Type',
                'class' => '',
                'show_column' => false,
                'sortable' => false,
                'searchable' => false,
                'formatter' => ''
            ],
            [
                'db_row' => 'source',
                'dt_row' => 'Source',
                'class' => '',
                'show_column' => false,
                'sortable' => false,
                'searchable' => false,
                'formatter' => function ($row, $twigExt) {
                    return $row['source'] ?? 'None';
                }
            ],
            [
                'db_row' => 'user',
                'dt_row' => 'user',
                'class' => '',
                'show_column' => false,
                'sortable' => true,
                'searchable' => true,
                'formatter' => function ($row, $twigExt) {
                    return $row['user'] ?? 'None';
                }
            ],
            [
                'db_row' => 'method',
                'dt_row' => 'Method',
                'class' => '',
                'show_column' => false,
                'sortable' => false,
                'searchable' => false,
                'formatter' => function ($row, $twigExt) {
                    return $row['method'] ?? 'None';
                }
            ],
            [
                'db_row' => 'event_context',
                'dt_row' => 'Context',
                'class' => '',
                'show_column' => false,
                'sortable' => false,
                'searchable' => false,
                'formatter' => function ($row, $twigExt) {
                    return $row['event_context'] ?? 'None';
                }
            ],
            [
                'db_row' => 'event_browser',
                'dt_row' => 'Browser',
                'class' => '',
                'show_column' => false,
                'sortable' => false,
                'searchable' => false,
                'formatter' => function ($row, $twigExt) {
                    return $row['event_browser'] ?? 'None';
                }
            ],
            [
                'db_row' => 'IP',
                'dt_row' => 'IP',
                'class' => '',
                'show_column' => false,
                'sortable' => false,
                'searchable' => false,
                'formatter' => function ($row, $twigExt) {
                    return $row['IP'] ?? 'None';
                }
            ],

            [
                'db_row' => 'created_at',
                'dt_row' => 'Logged',
                'class' => '',
                'show_column' => true,
                'sortable' => false,
                'searchable' => false,
                'formatter' => function ($row, $twigExt) {
                    //$html = $twigExt->tableDateFormat($row, "created_at");
                    //$html .= '<div><small>By Admin</small></div>';
                    return '<small>' . $row['created_at'] . '</small>';
                }
            ],
            [
                'db_row' => '',
                'dt_row' => 'Action',
                'class' => '',
                'show_column' => true,
                'sortable' => false,
                'searchable' => false,
                'formatter' => function ($row, $twigExt) {
                    return $twigExt->action(
                        [
                            'file-edit' => ['tooltip' => 'Edit', 'icon' => 'ion-compose'],
                        ],
                        $row,
                        $twigExt,
                        'event_log',
                        false,
                        'Are You Sure!',
                        "You are about to carry out an irreversable action. Are you sure you want to delete <strong class=\"uk-text-danger\">{$row['event_log_name']}</strong> role."
                    );
                }
            ],

        ];
    }
}

