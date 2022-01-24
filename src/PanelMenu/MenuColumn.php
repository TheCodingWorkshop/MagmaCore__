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

namespace MagmaCore\PanelMenu;

use MagmaCore\Datatable\AbstractDatatableColumn;
use MagmaCore\Datatable\DataColumnTrait;

class MenuColumn extends AbstractDatatableColumn
{

    use DataColumnTrait;

    private string $controller = 'menu';

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
                'db_row' => 'menu_name',
                'dt_row' => 'Name',
                'class' => '',
                'show_column' => true,
                'sortable' => true,
                'searchable' => true,
                'formatter' => function ($row, $tempExt) {
                    $html = '<div class="uk-clearfix">';
                    $html .= '<div class="uk-float-left uk-margin-small-right">';
                    $html .= '<span class="uk-text-teal" uk-icon="icon: info"></span>';
                    $html .= '</div>';
                    $html .= '<div class="uk-float-left">';
                    $html .= $row["menu_name"] . "<br/>";
                    $html .= '<div class="uk-text-truncate uk-width-3-4"><small>' . $row["menu_description"] . '</small></div>';
                    $html .= '</div>';
                    $html .= '</div>';

                    return $html;
                }
            ],
            [
                'db_row' => 'menu_description',
                'dt_row' => 'Description',
                'class' => '',
                'show_column' => false,
                'sortable' => false,
                'searchable' => false,
                'formatter' => ''
            ],
            [
                'db_row' => 'parent_menu',
                'dt_row' => 'Parent',
                'class' => '',
                'show_column' => true,
                'sortable' => true,
                'searchable' => true,
                'formatter' => function ($row, $tempExt) {
                    return $row['parent_menu'] ?? 'None';
                }
            ],
            [
                'db_row' => 'menu_order',
                'dt_row' => 'Order',
                'class' => '',
                'show_column' => true,
                'sortable' => true,
                'searchable' => true,
                'formatter' => function ($row, $tempExt) {
                    return $row['menu_order'] ?? 0;
                }
            ],
            [
                'db_row' => 'menu_icon',
                'dt_row' => 'Icon',
                'class' => '',
                'show_column' => true,
                'sortable' => false,
                'searchable' => false,
                'formatter' => function ($row, $tempExt) {
                    return '<ion-icon name="' . $row['menu_icon'] . '"></ion-icon>';
                }
            ],

            [
                'db_row' => 'created_at',
                'dt_row' => 'Published',
                'class' => '',
                'show_column' => true,
                'sortable' => true,
                'searchable' => false,
                'formatter' => function ($row, $tempExt) {
                    $html = $tempExt->tableDateFormat($row, "created_at");
                    $html .= '<div><small>By Admin</small></div>';
                    return $html;
                }
            ],
            [
                'db_row' => 'modified_at',
                'dt_row' => 'Modified',
                'class' => '',
                'show_column' => true,
                'sortable' => true,
                'searchable' => false,
                'formatter' => function ($row, $tempExt) {
                    $html = '';
                    if (isset($row["modified_at"]) && $row["modified_at"] != null) {
                        $html .= $tempExt->tableDateFormat($row, "modified_at");
                        $html .= '<div><small>By Admin</small></div>';
                    } else {
                        $html .= '<small>Never!</small>';
                    }
                    return $html;
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
                                        '',
                                        $row,
                                        $this->controller,
                                        ['can_view_menu']
                                    );
                                }
                            ],
                        ],
                        $row,
                        $tempExt,
                        $this->controller,
                        false,
                        'Are You Sure!',
                        "You are about to carry out an irreversable action. Are you sure you want to delete <strong class=\"uk-text-danger\">{$row['menu_name']}</strong> role."
                    );
                }
            ],

        ];
    }

        /**
     * Undocumented function
     *
     * @param array $row
     * @return array
     */
    private function itemsDropdown(array $row, string $controller): array
    {
        $items = [
            'edit' => ['name' => 'edit', 'icon' => 'create-outline'],
            'trash' => ['name' => 'trash menu', 'icon' => 'trash-bin-outline']
        ];
        return array_map(
            fn($key, $value) => array_merge(['path' => $this->adminPath($row, $controller, $key)], $value),
            array_keys($items),
            $items
        );
    }

}

