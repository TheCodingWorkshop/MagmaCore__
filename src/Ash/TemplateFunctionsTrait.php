<?php

namespace MagmaCore\Ash;

use DateTime;
use Exception;
use MagmaCore\IconLibrary;
use MagmaCore\Utility\Yaml;
use MagmaCore\Auth\Authorized;
use MagmaCore\Utility\Serializer;
use MagmaCore\Session\SessionTrait;
use MagmaCore\Utility\UtilityTrait;
use MagmaCore\UserManager\UserModel;
use MagmaCore\Utility\DateFormatter;
use MagmaCore\Auth\Roles\PrivilegedUser;
use MagmaCore\Base\Traits\BaseAnchorTrait;
use MagmaCore\UserManager\Rbac\Role\RoleModel;
use MagmaCore\UserManager\Rbac\Permission\PermissionModel;
use MagmaCore\Ash\Exception\TemplateLocaleOutOfBoundException;

trait TemplateFunctionsTrait
{

    use BaseAnchorTrait,
        UtilityTrait,
        SessionTrait;


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

    public function getUser(mixed $field = null): string
    {
        $string = '';
        /* check the argument against a list of valid fields for the user object throw ex exception is no match found */
        if ($field !==null) {
            if (is_array($field) && count($field) > 0) {
                foreach ($field as $col) {
                    $string = $col . ' ';
                }
            } else {
                $string = Authorized::grantedUser()->$field;
            }
            
        }

        return $string;
    }

    public function getYear()
    {
        return date('Y');
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

    /**
     * Fetch an icon from the system icon object
     * @param string $name
     * @param mixed|float $size
     * @param string $type
     * @return string
     */
    public function icon(string $name, mixed $size = 1.5, string $type = 'uikit'): string
    {
        return IconLibrary::getIcon($name, $size, $type);
    }

    /**
     * Allows us to unserialize serialize data from our html templates
     * @param mixed $data
     * @return mixed|string
     */
    public function unserializer(mixed $data)
    {
        return Serializer::unCompress($data);
    }

    /**
     * Show the latest discoveries on the main discovery page
     * @param array $discoveries
     * @return string
     */
    public function showDiscoveries(array $discoveries = []): string
    {
        $html = '';
        if ($this->isArrayCountable($discoveries)) {
            $count = 0;
            foreach ($discoveries as $discovery) {
                $unSerializeMethods = Serializer::unCompress($discovery['current_new_method']);
                $html .= sprintf('<div>%s</div>', ucwords($discovery['controller']));
                $html .= sprintf('<div><small class="uk-block">Discovered %s</small></div>', DateFormatter::timeFormat($discovery['created_at']));
                $html .= sprintf('<code>[%s] new method was discovered within this %s controller</code>', count($unSerializeMethods), ucwords($discovery['controller']));
                $html .= sprintf('<div><code>[methods] = {%s}</code></div>', implode(', ', $unSerializeMethods));
                $html .= '<hr>';
                $count++;
                if ($count === 2) {
                    break;
                }
            }
        }

        return $html;
    }

    /**
     * @param $session
     * @param $controller
     * @param string $key
     * @return string
     */
    public function discover($methods): string
    {
        $html = '';
        if ($this->isArrayCountable($methods)) {
            $html .= '<form method="post" action="/admin/discovery/install" />';
            $html .= '<ul class="uk-list uk-list-divider uk-list-collapse">';
            foreach ($methods as $method) {
                $html .= '
                            <li>
                                <div class="uk-clearfix">
                                    <div class="uk-float-left">Method [' . $method . ']</div>
                                    <input type="hidden" name="methods[]" value="' . $method . '" />
                                    <div class="uk-float-right"><a uk-tooltip="Push to methods" class="uk-link-reset" href="/admin/discovery/install">' . IconLibrary::getIcon('push') . '</a></div>
                                </div>
                            </li>

                        ';

            }
            $html .= '</ul>';
            $html .= '<div class="uk-margin">
                            <input type="hidden" name="controller_id" value="' . (isset($_GET) ? $_GET['edit'] : 0) .' " />
                            <input type="submit" class="uk-button uk-button-small uk-button-secondary" value="Add" name="install-discovery" />
                        </div>';
            $html .= '</form>';

        }

        return $html;
    }

    /**
     * Return the breadcrumbs trail
     *
     * @param string $separator
     * @return string
     */
    public function getBreadcrumbs(string $separator = ' &raquo; ', string $home = 'Home'): string
    {
        $breadcrumbs = new \MagmaCore\Utility\Breadcrumbs;
        return $breadcrumbs->breadcrumbs($separator, $home);
    }

    public function canAccess($privilegeUser, string $permission = null): bool
    {
        $session = self::sessionFromGlobal()->get('current_permission');
        $permissionArray = array_column((array)$privilegeUser, $privilegeUser->getRole())[0]->permissions;
        return array_key_exists(($permission !==null ? $permission : $session), $permissionArray) ?? true;
    }


}