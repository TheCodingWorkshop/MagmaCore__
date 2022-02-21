<?php

namespace MagmaCore\Ash;

use Exception;
use MagmaCore\IconLibrary;
use MagmaCore\Utility\Serializer;
use MagmaCore\Utility\Yaml;
use MagmaCore\UserManager\UserModel;
use MagmaCore\Utility\DateFormatter;
use MagmaCore\Auth\Roles\PrivilegedUser;
use MagmaCore\UserManager\Rbac\Role\RoleModel;
use MagmaCore\UserManager\Rbac\Permission\PermissionModel;
use MagmaCore\Ash\Exception\TemplateLocaleOutOfBoundException;
use MagmaCore\Base\Traits\BaseAnchorTrait;

trait TemplateFunctionsTrait
{

    use BaseAnchorTrait;

    // public function protectedAnchor(
    //     array $props = [], ?int $userID = null, mixed $content = null, ?string $permission = null): string|bool
    // {
    //     $privilege = PrivilegedUser::getUser();
    //     if (count($props) > 0) {
    //         foreach ($props as $key => $prop) {
    //             if (!in_array($key, ['href', 'title', 'rel', 'class', 'id', 'style', 'uk-tooltip'])) {
    //                 throw new TemplateLocaleOutOfBoundException('Invalid property set for anchor tag ' . [$key]);
    //             }

    //             if ($privilege->hasPrivilege($permission)) {
    //                 if (isset($prop[$key]) && $prop[$key] !=='') {
    //                     $element = sprintf(
    //                         '<a %s%s%s%s%s%s>%s</a>',
    //                         $prop['href'],
    //                         $prop['class'],
    //                         $prop['id'],
    //                         $prop['style'],
    //                         $prop['title'],
    //                         $prop['uk-tooltip'],
    //                         $content
    //                     );
    //                 }
    //                 return $element;
    //             } else {
    //                 return '';
    //             }
    //         }
    //     }
    //     return false;
    // }

    /**
     * Expose framework database configuration options to the template
     *
     * @param string $name
     * @return mixed
     */
    public function config(string $name): mixed
    {
        if (isset($this->controller->settings)) {
            return $this->controller->settings->get($name);
        }
        return null;
    }

    /**
     * @param string $str
     * @param int $max
     * @param int $min
     * @return string
     */
    public function truncate(string $str, int $max = 100, int $min = 80): string
    {
        if (strlen($str) > $max)
            $str = substr($str, 0, $min) . ' ...';

        return $str;
    }

    /**
     * @param string|null $str
     * @param string $type
     * @return string
     * @throws Exception
     */
    public function str(string $str = null, string $type = 'capital'): string
    {
        if (!empty($type)) {
            return match($type) {
                'upper' => strtoupper($str),
                'lower' => strtolower($str),
                'capital' => ucwords($str),
                default => throw new \Exception(sprintf('%s is required', $str))
            };
        }
    }

    /**
     * Undocumented function
     *
     * @param string $string
     * @return string
     * @throws Exception
     */
    public function locale(string $string): string
    {
        if (is_string($string)) {
            $locale = Yaml::file('locale')['en'];
            if (!in_array($string, array_keys($locale))) {
                throw new TemplateLocaleOutOfBoundException($string . ' is an invalid translation string.');
            }

            return $locale[$string];
        }
    }

        /**
     * Return a formatted human readable date format
     *
     * @param mixed $time
     * @param boolean $short
     * @return string
     */
    public function formatDate(mixed $time, bool $short = false): string
    {
        return DateFormatter::timeFormat($time, $short);
    }

    public function altDateFormat(string $format, $datetime)
    {
        return date($format, strtotime($datetime));
    }

    /**
     * Construct the user define path. Path must be specified based on the its
     * controller ie. to access the index action within the dashboard controller this
     * path must be declared as admin_dashboard_index which will simple return
     * /admin/dashboard/index. An exception will be thrown if the current path doesn't
     * match the current namespace controller and action
     *
     * @param string $path
     * @param mixed $token
     * @return string
     */
    public function path(string $path, mixed $token = null): string
    {
        $string = explode('_', $path);
        $sep = '/';

        if ($token !==null) {
            $newArray = [$string[0], $string[1], $token, $string[2]];
            return $sep . implode('/', array_replace($string, $newArray));
        } else {
            return $sep . implode('/', $string);
        }
    }

    public function getRoleFromID(int $userID)
    {
        $role = PrivilegedUser::getUser($userID);
        return $role->getRole();
    }

    /**
     * Undocumented function
     *
     * @param mixed $key
     * @param mixed $array
     * @return mixed
     */
    public function isSet(mixed $key, mixed $array): mixed
    {
        return array_key_exists($key, $array) ? (is_array($array) ? $array[$key] : $array->$key) : '';
    }

    public function getItemNameByID(int $id): object
    {
        if (!empty($id)) {
            $id = (int)$id;
            $this->itemName = $this->controller->repository->getRepo()->findObjectBy(['id' => $id]);
            return $this->itemName;
        }
    }


    /**
     * Return the permission ID based on the permission name
     * @param string $permissionName
     * @return int
     */
    public function getPermissionIDFromName(string $permissionName): int
    {
        $permission = (new PermissionModel())->getRepo()->findObjectBy(['permission_name' => $permissionName], ['id']);
        return $permission->id;
    }

    /**
     * @param array $userRoleIDs
     * @return array
     */
    public function getUsersFromRoleID(array $userRoleIDs): array
    {
        $results = array_filter($userRoleIDs, fn($userRoleID) => $userRoleID['user_id'], ARRAY_FILTER_USE_BOTH);
        $users = array_map( fn($id) => (new UserModel())->getRepo()->findBy(['firstname', 'lastname', 'status', 'id', 'gravatar'], ['id' => $id['user_id']]), $results);
        return $this->flattenArray($users);

    }

    /**
     * @param array $permissionID
     * @return array
     */
    public function getRolesFromPermissionID(array $permissionID): array
    {
        $results = array_filter($permissionID, fn($rolePermID) => $rolePermID['role_id'], ARRAY_FILTER_USE_BOTH);
        $roles = array_map( fn($id) => (new RoleModel())->getRepo()->findBy(['id', 'role_name'], ['id' => $id['role_id']]), $results);
        return $this->flattenArray($roles);

    }

    /**
     * @param array $permissionID
     * @return string
     */
    public function getRolesOrderedListFromPermissionID(array $permissionID): string
    {
        $roles = $this->getRolesFromPermissionID($permissionID);
        if (is_array($roles) && count($roles) > 0) {
            $html = '<ul class="uk-panel uk-panel-scrollable">';
            foreach ($roles as $role) {
                $html .= '<li>';
                $html .= '<div class="uk-grid-small" uk-grid>';
                $html .= '<div class="uk-width-expand" uk-leader>' . $role['role_name'] . '</div>';
                $html .= '<div>';
                $html .= '<a href="/admin/role/' . $role['id'] . '/edit" class="uk-reset-link" uk-tooltip="Edit ' . $role['role_name'] . '"><ion-icon name="create-outline"></ion-icon></a>';
                $html .= '<a href="/admin/role/' . $role['id'] . '/assigned" class="uk-reset-link" uk-tooltip="Edit Permissions"><ion-icon name="key-outline"></ion-icon></a>';
                $html .= '</div>';
                $html .= '</div>';
                $html .= '</li>';
            }
            $html .= '</ul>';

            return $html;

        } else {
            return '<ion-icon name="help-outline"></ion-icon>';
        }
    }

    /**
     * @param array $userRoleIDs
     * @return string
     */
    public function getUserOrderedListsFromRoles(array $userRoleIDs = [], bool $thumbnavView = false, ?int $breakpoint = 3): string
    {
        $users = $this->getUsersFromRoleID($userRoleIDs);
        if (is_array($users) && count($users) > 0) {
            $html = '<ul class="' . ($thumbnavView === false ? 'uk-panel uk-panel-scrollable' : 'uk-thumbnav') . '">';
            $i = 0;
            $total = count($users);
            foreach ($users as $user) {
                if ($thumbnavView === false) {
                    $html .= '<li>';
                    $html .= '<div class="uk-grid-small" uk-grid>';
                    $html .= '<div class="uk-width-expand" uk-leader>' . $user['firstname'] . ' ' . $user['lastname'] . '</div>';
                    $html .= '<div>';
                    $html .= '<a href="/admin/user/' . $user['id'] . '/edit" class="uk-reset-link" uk-tooltip="Edit ' . $user['firstname'] . '"><ion-icon name="create-outline"></ion-icon></a>';
                    $html .= '<a href="/admin/user/' . $user['id'] . '/privilege" class="uk-reset-link" uk-tooltip="Edit Privilege"><ion-icon name="key-outline"></ion-icon></a>';
                    $html .= '</div>';
                    $html .= '</div>';
                    $html .= '</li>';
                } else {
                    $html .= '<li class="uk-active">';
                    $html .= '<a uk-tooltip="' . $user['firstname'] . ' ' . $user['lastname'] .  '" href="#">';
                    $html .= '<img class="uk-border-pill" src="' . $user['gravatar'] . '" width="40" alt="">';
                    $html .= '</a>';
                    $html .= '</li>';
                }

                $i++;
                if ($i === $breakpoint) {
                    break;
                }
                
            }
            if ($total !== $breakpoint)
                $html .= '<li><sup>+' . ($total - $breakpoint) .' more</sup></li>';

            $html .= '</ul>';

            return $html;

        } else {
            return '<ion-icon name="help-outline"></ion-icon>';
        }

    }

    public function getAuthorByID(int $id)
    {
        $user = (new UserModel())->getRepo()->findObjectBy(['id' => $id], ['firstname', 'lastname']);
        if ($user) {
            return sprintf('%s %s', $user->firstname, $user->lastname);
        }
    }

    public function getLogLevelColor(string $levelName)
    {
        if (is_string($levelName) && $levelName !='') {
            return match($levelName) {
                'warning', 'alert' => 'warning',
                'emergency' => 'success',
                'critical', 'error' => 'danger',
                'notice', 'info' => 'primary',
                'debug' => 'secondary'
            };
        }
    }

    /**
     * @param int $countPending
     * @param int $countActive
     * @param object $request
     */
    public function togglePendingActiveStatusLabel(int $countPending, int $countActive, $tab, object $request)
    {
        //$html = '';
        if (isset($request)){
            $status = $request->handler()->query->get('status');
            if (isset($status) && $status === 'pending') {
                $html = '<span class="uk-text-small uk-label uk-label-success">' . $tab['data'] . '</span>';
                /* show the active count and and link back to index */
            } else {
                /* show pending count and link back to pending status */
            }
        }
        return $html;
    }

    public function icon(string $name, mixed $size = 1.5, string $type = 'uikit'): string
    {
        return IconLibrary::getIcon($name, $size, $type);
    }

    public function unserializer(mixed $data)
    {
        return Serializer::unCompress($data);
    }

    public function discovery($session, $controller, string $key = 'controller_discover')
    {
        $controllerKey = 'new_' . $controller . '_discovery';

        if ($session->has($key)) {

            $data = $session->get($key);
            if ($data['parent_controller'] === $controller) {
                $methods = $data[$controllerKey];
                if (is_array($methods) && count($methods) > 0) {
                    $html = '<ul class="uk-list uk-list-divider uk-list-collapse">';
                    foreach ($methods as $method) {
                        $html .= '
                            <li>
                                <div class="uk-clearfix">
                                    <div class="uk-float-left">Method [' . $method . ']</div>
                                    <div class="uk-float-right">install</div>
                                </div>
                            </li>

                        ';
                    }
                    $html .= '</ul>';

                    return $html;
                }
            }

        }
    }
}