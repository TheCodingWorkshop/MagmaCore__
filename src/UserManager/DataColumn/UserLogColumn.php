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

namespace MagmaCore\UserManager\DataColumns;

use Exception;
use MagmaCore\Auth\Roles\PrivilegedUser;
use MagmaCore\Datatable\AbstractDatatableColumn;
use MagmaCore\Utility\Stringify;

class UserLogColumn extends AbstractDatatableColumn
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
                'db_row' => 'ID',
                'dt_row' => 'ID',
                'class' => 'uk-table-shrink',
                'show_column' => true,
                'sortable' => false,
                'searchable' => true,
                'formatter' => function ($row) {
                    return '<input type="checkbox" class="uk-checkbox" id="users-' . $row['id'] . '" name="id[]" value="' . $row['id'] . '">';
                }
            ],
            [
                'db_row' => 'level_name',
                'dt_row' => 'Type',
                'class' => 'uk-table-expand',
                'show_column' => true,
                'sortable' => true,
                'searchable' => true,
                'formatter' => ''
            ],
            [
                'db_row' => 'level',
                'dt_row' => 'Level',
                'class' => '',
                'show_column' => false,
                'sortable' => false,
                'searchable' => true,
                'formatter' => ''
            ],
            [
                'db_row' => 'message',
                'dt_row' => 'Message',
                'class' => '',
                'show_column' => false,
                'sortable' => false,
                'searchable' => true,
                'formatter' => ''
            ],
            [
                'db_row' => 'context',
                'dt_row' => 'Context',
                'class' => '',
                'show_column' => false,
                'sortable' => false,
                'searchable' => false,
                'formatter' => ''
            ],
            [
                'db_row' => 'created_at',
                'dt_row' => 'Published',
                'class' => '',
                'show_column' => true,
                'sortable' => true,
                'searchable' => false,
                'formatter' => function ($row, $twigExt) {
                    return $twigExt->tableDateFormat($row, "created_at", true);
                    //$html .= '<br/><small>' . $row['firstname'] . '</small>';
                   //return $html;
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
                        'userLog',
                        false,
                        'Are You Sure!',
                        "You are about to carry out an irreversable action. Are you sure you want to delete <strong class=\"uk-text-danger\">{$row['level_name']}</strong> role."
                    );
                }
            ],

        ];
    }


}

