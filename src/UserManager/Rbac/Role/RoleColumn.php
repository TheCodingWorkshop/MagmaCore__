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

namespace MagmaCore\UserManager\Rbac\Role;

use MagmaCore\Datatable\AbstractDatatableColumn;
use MagmaCore\UserManager\Rbac\Model\RolePermissionModel;
use MagmaCore\UserManager\Rbac\Model\TemporaryRoleModel;

class RoleColumn extends AbstractDatatableColumn
{

    private TemporaryRoleModel $tempRole;

    public function __construct()
    {
        $this->tempRole = new TemporaryRoleModel();
    }

    /**
     * @param array $conditions
     * @return object|null
     */
    private function roleOnExpiration(array $conditions): ?object
    {
        $selectors = ['*'];
        return $this->tempRole->getRepo()->findObjectBy($conditions, $selectors);

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
                'searchable' => false,
                'formatter' => function ($row) {
                    return '<input type="checkbox" class="uk-checkbox" id="roles-' . $row['id'] . '" name="id[]" value="' . $row['id'] . '">';
                }
            ],
            [
                'db_row' => 'role_name',
                'dt_row' => 'Name',
                'class' => '',
                'show_column' => true,
                'sortable' => true,
                'searchable' => true,
                'formatter' => function ($row, $tempExt) {
                    $html = '<div class="uk-clearfix">';
                    $html .= '<div class="uk-float-left uk-margin-small-right">';
                    $html .= '<div>';
                    $expiration = $this->roleOnExpiration(['current_role_id' => $row['id']]);
                    if (isset($expiration->current_role_id) && $expiration->current_role_id !==null) {
                        $html .= '<span uk-tooltip="Expiration Set"><ion-icon name="time-outline"></ion-icon></span>';
                    }

                    $html .= '</div>';
                    $html .= '<div>';
                    $html .= '<span class="uk-text-primary"><ion-icon name="information-circle-outline"></ion-icon></span>';
                    $html .= '</div>';
                    $html .= '</div>';
                    $html .= '<div class="uk-float-left">';
                    $html .= $row["role_name"] . "<br/>";
                    $html .= '<div class="uk-text-truncate uk-width-3-4"><small>' . $row["role_description"] . '</small></div>';
                    $html .= '</div>';
                    $html .= '</div>';

                    return $html;
                }
            ],
            [
                'db_row' => 'role_description',
                'dt_row' => 'Description',
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
                    $html = $tempExt->tableDateFormat($row, "created_at");
                    $html .= '<div><small>By Admin</small></div>';
                    return $html;
                }
            ],
            [
                'db_row' => 'modified_at',
                'dt_row' => 'Last Updated',
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
                            'has_permission' => $this->hasPermission($row),
                            'file-edit' => ['tooltip' => 'Edit', 'icon' => 'ion-compose'],
                            'trash' => ['tooltip' => 'Trash', 'icon' => 'ion-ios-trash']
                        ],
                        $row,
                        $tempExt,
                        'role',
                        false,
                        'Are You Sure!',
                        "You are about to carry out an irreversable action. Are you sure you want to delete <strong class=\"uk-text-danger\">{$row['role_name']}</strong> role."
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
    private function hasPermission(array $row): array
    {
        $rolePerm = (new RolePermissionModel())
            ->getRepo()
            ->findOneBy(['role_id' => $row['id']]);
        if ($rolePerm != null) {
            $array = ['icon' => 'ion-locked', 'tooltip' => 'Role Lock', 'path' => "/admin/role/{$row['id']}/assigned", 'color' => 'uk-text-success'];
        } else {
            $array = ['icon' => 'ion-unlocked', 'tooltip' => 'Role Unlock', 'path' => "/admin/role/{$row['id']}/assigned", 'color' => 'uk-text-warning'];
        }
        return $array;
    }
}
