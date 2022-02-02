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

namespace MagmaCore\Ticket;

use MagmaCore\Datatable\AbstractDatatableColumn;
use MagmaCore\Datatable\DataColumnTrait;
use MagmaCore\UserManager\UserModel;

class TicketColumn extends AbstractDatatableColumn
{

    use DataColumnTrait;

    private string $controller = 'ticket';
    private UserModel $userModel;

    public function __construct(UserModel $userModel)
    {
        $this->userModel = $userModel;
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
                'db_row' => 'category',
                'dt_row' => 'Name',
                'class' => 'uk-table-expand',
                'show_column' => true,
                'sortable' => false,
                'searchable' => true,
                'formatter' => function ($row, $tempExt) use ($callingController) {
                    $user = $this->userModel->getUser($row['assigned_to']);

                    $html = '<div class="uk-clearfix">';

                        $html .= '<div class="uk-float-left">';
                            $html .= '<img uk-tooltip="Assigned To ' . $user->firstname . ' ' . $user->lastname . '" src="' . $user->gravatar . '" width="40" class="uk-border-circle">';
                        $html .= '</div>';

                        $html .= '<div class="uk-float-left">';
                            $html .= '<div><a class="uk-link-reset" href="/admin/ticket/' . $row['id'] . '/edit">' . $row['ticket_desc'] . '</a></div>';
                            $html .= PHP_EOL;
                            $html .= '<small>';
                                $html .= sprintf('%s|%s|%s|Created By %s|Comments %s',
                                    $this->getCategory($row, $tempExt),
                                    $this->getPriority($row, $tempExt),
                                    $this->getStatus($row, $tempExt),
                                    $this->userModel->getUser($row['created_byid'])->email,
                                '(2)'
                                );
                            $html .= '</small>';

                        $html .= '</div>';
                        $html .= '</div>';
                    return $html;
                }
            ],
            [
                'db_row' => 'ticket_desc',
                'dt_row' => 'Description',
                'class' => '',
                'show_column' => false,
                'sortable' => false,
                'searchable' => true,
                'formatter' => ''
            ],
            [
                'db_row' => 'attachment',
                'dt_row' => 'Attachment',
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
                'formatter' =>''
            ],
            [
                'db_row' => 'priority',
                'dt_row' => 'Priority',
                'class' => '',
                'show_column' => false,
                'sortable' => false,
                'searchable' => false,
                'formatter' => ''
            ],
            [
                'db_row' => 'assigned_to',
                'dt_row' => 'Assigned To',
                'class' => '',
                'show_column' => false,
                'sortable' => false,
                'searchable' => false,
                'formatter' => ''
            ],
            [
                'db_row' => 'reassigned_to',
                'dt_row' => 'Re-assigned',
                'class' => '',
                'show_column' => false,
                'sortable' => false,
                'searchable' => false,
                'formatter' => ''
            ],
            [
                'db_row' => 'created_byid',
                'dt_row' => 'Author',
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
                'show_column' => false,
                'sortable' => false,
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
                                        $this->controller,
                                        ['basic_access']
                                    );
                                }
                            ],
                        ],
                        $row,
                        $tempExt,
                        $this->controller,
                        false,
                        'Are You Sure!',
                        "You are about to carry out an irreversable action. Are you sure you want to delete <strong class=\"uk-text-danger\">{$row['category']}</strong> account.",
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
            'comment' => ['name' => 'comments - (2)', 'icon' => 'chatbox-outline'],
            'trash' => ['name' => 'trash account', 'icon' => 'trash-bin-outline']
        ];
        return array_map(
            fn($key, $value) => array_merge(['path' => $this->adminPath($row, $controller, $key)], $value),
            array_keys($items),
            $items
        );
    }

    private function getStatus($row, $tempExt): string
    {
        if (!in_array($row['status'], ['open', 'closed', 'resolved'])) {
            return '<span>Unknown</span>';
        }
//        $query = $_GET['status'] ?? '';
//        if (isset($_GET['status']) && $_GET['status'] === $row['status']) {}
        return match($row['status']) {
            'open' => '<span class="uk-text-warning">Open</span>',
            'closed' => '<span class="uk-text-danger">Closed</span>',
            'resolved' => '<span class="uk-text-success">Resolved</span>',
            default => 'Unknown'
        };
    }

    private function getPriority($row, $tempExt): string
    {
        if (!in_array($row['priority'], ['low', 'medium', 'high', 'critical'])) {
            return '<span>Unknown</span>';
        }

        return match($row['priority']) {
            'low' => '<span class="uk-text-success">Low</span>',
            'medium' => '<span class="uk-text-warning">Medium</span>',
            'high' => '<span class="uk-text-danger">High</span>',
            default => 'Unknown'
        };

    }

    private function getCategory($row, $tempExt): string
    {

        if (!in_array($row['category'], ['technical', 'information', 'general'])) {
            return '<span>Unknown</span>';
        }
        return '<span class="uk-text-primary">' . ucwords($row['category']) . '</span>';

    }

}

