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

use MagmaCore\IconLibrary;
use MagmaCore\Datatable\DataColumnTrait;
use MagmaCore\PanelMenu\Form\MenuQuickEditForm;
use MagmaCore\Datatable\AbstractDatatableColumn;

class MenuColumn extends AbstractDatatableColumn
{

    use DataColumnTrait;

    private string $controller = 'menu';

    private MenuQuickEditForm $menuQuickEditForm;

    public function __construct(MenuQuickEditForm $menuQuickEditForm)
    {
        $this->menuQuickEditForm = $menuQuickEditForm;
    }

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
                'formatter' => function ($row, $tempExt) use ($callingController) {
                    $html = '<div class="uk-clearfix">';
                    $html .= '<div class="uk-float-left uk-margin-small-right">';
                    $html .= '<a data-turbo="true" href="/admin/menu/' . $row['id'] . '/' . (isset($row['parent_menu']) && $row['parent_menu'] === 0 ? 'untoggle' : 'toggle') . '">';
                    if ($row['parent_menu'] === 0) {
                        $html .= '<span uk-tooltip="Bookmark item to side navigation" class="uk-text-teal" uk-icon="icon: bookmark; ratio: 0.7"></span>';
                    } else {
                        $html .= '<span uk-tooltip="Remove bookmark from navigation" class="uk-text-teal" uk-icon="icon: minus-circle; ratio: 0.7"></span>';
                    }
                    $html .= '</a>';
                    $html .= $tempExt->action(
                        [
                            'edit_modal' => [
                                'icon' => 'pencil',
                                'tooltip' => 'Quick Edit',
                                'toggle_modal_edit' => true,
                                'callback' => function($row, $tempExt) use ($callingController) {
                                    return $tempExt->getModal(
                                        [
                                            'toggle_id' => 'edit-modal-menu-' . $row['id'],
                                            'modal_title' => 'Quick Edit',
                                            'modal_content' => $this->quickEditForm('', $row, $callingController)
                                        ]
                                    );
                                }
                            ]
                            ],
                            $row,
                            $tempExt,
                            $this->controller,
                            false
                    );
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
                                'icon' => 'more',
                                'callback' => function ($row, $tempExt) {
                                    return $tempExt->getDropdown(
                                        $this->columnActions($row, $this->controller),
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
     * @inheritDoc
     *
     * @param array $row
     * @param string|null $controller
     * @param object|null $tempExt
     * @return array
     */
    public function columnActions(array $row = [], ?string $controller = null, ?object $tempExt = null): array
    {
        return $this->filterColumnActions(
            $row, 
            $this->columnBasicLinks($this), /* can merge additional links here to this column */
            $controller
        );
    }

    private function quickEditForm(string $action = null, mixed $row = null, object $controller = null)
    {
         $html = '<form method="post" id="menuQuickEdit" action="/admin/menu/' . $row['id'] . '/quickEdit" class="uk-form-stacked">';
            $html .= '<legend>' . sprintf('Quickly edit this item without entering the full edit route. <code>You are editing [%s] menu item</code>', $row['menu_name']) . '</legend>';
            $html .= '
            <div class="uk-margin">
                <label class="uk-form-label" for="menu_order">Menu Order</label>
                <div class="uk-form-controls">
                    <input class="uk-input uk-form-width-small" id="menu_order" name="menu_order" type="number" value="' . $row['menu_order']. '">
                </div>
                <span class="uk-text-meta">The order in which the menu item is displayed in the sidebr navigation. Higher the number the higher up the item will be placed, upto <code>100 maximum</code></span>

            </div> 
            ';

            $html .= '
            <div class="uk-margin">
                <label class="uk-form-label" for="menu_icon">Menu Icon</label>
                <div class="uk-form-controls">
                    <input class="uk-input uk-form-width-medium" id="menu_icon" name="menu_icon" type="text" value="' . $row['menu_icon'] . '">
                    ' . IconLibrary::getIcon($row['menu_icon']) . '
                </div>
                <span class="uk-text-meta">This is the small icon which sits beside your navigation menu item.</span>
            </div> 
            ';
            $html .= '
            <div class="uk-margin">
                <div class="uk-form-controls">
                    <input class="uk-button uk-button-secondary uk-button-small" id="index_quick_save" name="quickEdit-menu" value="Quick Save" type="submit">
                    <a class="uk-button uk-button-small uk-button-text uk-text-secondary" href="/admin/menu/' . $row['id'] . '/edit">Full Edit</a>
                </div>
                <div id="quickSaveMessage"></div>
            </div> 
            ';
        $html .= '</form>';

        return $html;
    }

}

