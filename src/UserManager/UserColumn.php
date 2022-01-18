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

namespace MagmaCore\UserManager;

use MagmaCore\Auth\Roles\PrivilegedUser;
use MagmaCore\Datatable\AbstractDatatableColumn;
use MagmaCore\Datatable\DataColumnTrait;

class UserColumn extends AbstractDatatableColumn
{

    use DataColumnTrait;

    private string $controller = 'user';

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
                'db_row' => 'firstname',
                'dt_row' => 'Name',
                'class' => 'uk-table-expand',
                'show_column' => true,
                'sortable' => false,
                'searchable' => true,
                'formatter' => function ($row) use ($callingController) {
                    $privilege = PrivilegedUser::getUser($row['id']);
                    $html = '<div class="uk-clearfix">';
                    $html .= '<div class="uk-float-left">';
                    $html .= '<img src="' . $row["gravatar"] . '" width="40" class="uk-border-circle">';
                    $html .= '</div>';
                    $html .= '<div class="uk-float-left uk-margin-small-right">';
                    $html .= '<div>' . $this->displayStatus($callingController, $row) . '</div>';
                    $html .= '<div><a uk-tooltip="' . (!$privilege->getRole() ? 'No Role Assigned' : $privilege->getRole()) . '" href=""><ion-icon name="' . (!$privilege->getRole() ? 'alert-outline' : 'person-outline') . '"></ion-icon></a></div>';
                    $html .= '<div></div>';
                    $html .= '</div>';
                    $html .= '<div class="uk-float-left">';
                    $html .= $row["firstname"] . ' ' . $row["lastname"] . "<br/>";
                    $html .= '<div><small>' . $row["email"] . '</small></div>';
                    $html .= '</div>';
                    $html .= '</div>';
                    return $html;
                }
            ],
            [
                'db_row' => 'lastname',
                'dt_row' => 'Lastname',
                'class' => '',
                'show_column' => false,
                'sortable' => false,
                'searchable' => true,
                'formatter' => ''
            ],
            [
                'db_row' => 'email',
                'dt_row' => 'Email Address',
                'class' => '',
                'show_column' => false,
                'sortable' => false,
                'searchable' => true,
                'formatter' => ''
            ],
            [
                'db_row' => 'status',
                'dt_row' => 'Status',
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
                'formatter' => function ($row, $tempExt) {
                    $html = $tempExt->tableDateFormat($row, "created_at", true);
                    $html .= '<br/><small>' . $row['firstname'] . '</small>';
                    return $html;
                }
            ],
            [
                'db_row' => 'modified_at',
                'dt_row' => 'Last Modified',
                'class' => '',
                'show_column' => true,
                'sortable' => true,
                'searchable' => false,
                'formatter' => function ($row, $tempExt) {
                    $html = '';
                    if (isset($row["modified_at"]) && $row["modified_at"] != null) {
                        //$html .= "$tempExt->getUserById($row[$row_name]);"
                        $html .= $tempExt->tableDateFormat($row, "modified_at", true);
                        $html .= '<div><small>By Admin</small></div>';
                    } else {
                        $html .= '<small>Never!</small>';
                    }
                    return $html;
                }
            ],
            [
                'db_row' => 'gravatar',
                'dt_row' => 'Thumbnail',
                'class' => '',
                'show_column' => false,
                'sortable' => false,
                'searchable' => false,
                'formatter' => ''
            ],
            [
                'db_row' => 'ip',
                'dt_row' => 'IP',
                'class' => 'uk-table-shrink',
                'show_column' => true,
                'sortable' => false,
                'searchable' => false,
                'formatter' => function ($row, $tempExt) {
                    return '<span class="ion-location ion-24" uk-tooltip="' . $row["remote_addr"] . '"></span>';
                }
            ],
            [
                'db_row' => '',
                'dt_row' => 'Action',
                'class' => '',
                'show_column' => true,
                'sortable' => false,
                'searchable' => false,
                'formatter' => function ($row, $tempExt) {
                    return $tempExt->action(
                        [
                            'more' => [
                                'icon' => 'ion-more',
                                'callback' => function ($row, $tempExt) {
                                    return $tempExt->getDropdown(
                                        $this->itemsDropdown($row, $this->controller),
                                        $this->getDropdownStatus($row),
                                        $row,
                                        $this->controller
                                    );
                                }
                            ],
                        ],
                        $row,
                        $tempExt,
                        $this->controller,
                        false,
                        'Are You Sure!',
                        "You are about to carry out an irreversable action. Are you sure you want to delete <strong class=\"uk-text-danger\">{$row['firstname']}</strong> account."
                    );
                }
            ],

        ];
    }

    /**
     * Undocumented function
     *
     * @param array $row
     * @param string $controller
     * @return array
     */
    private function itemsDropdown(array $row, string $controller): array
    {
        $items = [
            'edit' => ['name' => 'edit', 'icon' => 'create-outline'],
            'privilege' => ['name' => 'Edit Privilege', 'icon' => 'key-outline'],
            'preferences' => ['name' => 'Edit Preferences', 'icon' => 'options-outline'],
            'show' => ['name' => 'show', 'icon' => 'eye-outline'],
            'clone' => ['name' => 'clone', 'icon' => 'copy-outline'],
            'lock' => ['name' => 'lock account', 'icon' => 'lock-closed-outline'],
            'trash' => ['name' => 'trash account', 'icon' => 'trash-bin-outline']
        ];
        return array_map(
            fn($key, $value) => array_merge(['path' => $this->adminPath($row, $controller, $key)], $value),
            array_keys($items),
            $items
        );
    }



}
