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

namespace MagmaCore\Ash\Components\Uikit;

use Exception;
use MagmaCore\IconLibrary;
use MagmaCore\Notification\NotificationModel;
use MagmaCore\Utility\Stringify;
use MagmaCore\Auth\Roles\PrivilegedUser;
use MagmaCore\DataObjectLayer\DataLayerClientFacade;

class UikitNavigationExtension
{

    /** @var string */
    public const NAME = 'uikit_navigation';
    private object $repo;

    public function __construct()
    {
        $this->repo = (new DataLayerClientFacade(
            'system_menu',
            'menu',
            'id'))->getClientRepository();
    }

    /**
     * @param string $controller
     * @param string $permission
     * @return bool
     */
    public function hideMenuIfNoPermission(object $controller, $permission): bool
    {
        $privilege = PrivilegedUser::getUser();
        if (!$privilege->hasPrivilege($permission . '_' . $controller->thisRouteController())) {
            return false;
        }
        return true;
    }

    /**
     * @param object|null $controller
     * @return string
     * @throws Exception
     */
    public function register(object $controller = null): string
    {
        $config = $controller->settings;
        $routeController = $controller->thisRouteController();
        $element = $active = '';
        if (isset($controller)) {

            $query = 'SELECT * FROM menus ORDER BY menu_order DESC';

            $data = $this->repo->getClientCrud()->rawQuery($query, [], 'fetch_all');
            if (is_array($data) && count($data) > 0) {
                $element = '<ul class="uk-nav-default uk-nav-parent-icon" uk-nav uk-sortable="cls-custom: uk-box-shadow-small uk-flex uk-flex-middle uk-background">';
                $element .= '<li class="uk-nav-header">
                <span>Manage</span>
                <ul class="uk-iconnav uk-margin">
                    <li><a href="#" uk-icon="icon: plus"></a></li>
                    <li><a href="#" uk-icon="icon: file-edit"></a></li>
                    <li><a uk-tooltip="Discover" href="/admin/discovery/discover" uk-icon="icon: location"></a></li>
                    <li><a uk-toggle="target: #notification-panel" uk-tooltip="Notifications"><span uk-icon="icon: bell"></span> ' . $this->hasNotification($controller) . '</a></li>
                </ul>
                </li>';
                $element .= '<hr>';
                foreach ($data as $key => $item) {
                    
                    if ($controller->thisRouteController() === $item['menu_name']) {
                        $controller->getSession()->set('commander_icon', $item['menu_icon']);
                    }
                    $childQuery = 'SELECT * FROM menu_items WHERE item_original_id = ' . $item['id'];
                    $children = $this->repo->getClientCrud()->rawQuery($childQuery, [], 'fetch_all');

                    if (array_key_exists('parent_menu', $item) && $item['parent_menu'] === 1) {
                        $isParent = (isset($children) && count($children) > 0);
                        $active = ($routeController === $item['menu_name'] && $routeController !=='dashboard') ? 'uk-open' : '';
                        $element .= '<li class="' . ($isParent ? 'uk-parent' . $active : '') . '">';
                        $element .= $this->getParentAnchorElement($item, $element, $config);
                        if ($isParent) {
                            $element .= '<ul class="uk-nav-sub uk-navbar-primary">';
                            foreach ($children as $child) {
                                if ($child['item_usable'] === 1) {
                                    $element .= '<li>';
                                    $element .= $this->getChildAnchorElement($child, $element);
                                    $element .= '</li>';
                                }
                            }
                            $element .= '</ul>' . PHP_EOL;
                        }
                        $element .= '</li>' . PHP_EOL;
                    } else {
                        $element .= $this->getParentAnchorElement($item, $element, $config);
                    }

                }

                $element .= '</ul>';
                $element .= PHP_EOL;
            }
        }
        return $element;
    }

    /**
     * @param mixed $item
     * @param string $element
     * @param $config
     * @return string
     */
    public function getParentAnchorElement(mixed $item, string $element, $config): string
    {
        $element = '<a href="' . ($item['path'] ?? 'javascript:void(0)') . '">';
        if ($config->get('menu_icon') === 'on') {
            $element .= sprintf(
                '<span style="margin-bottom: 15px;"class="uk-margin-small-right">%s</span>',
                IconLibrary::getIcon($item['menu_icon'], $config->get('menu_icon_size'))
            );

        }

        $element .= Stringify::capitalize(($item['menu_name'] ?? 'Unknown'));
        $element .= '</a>';
        return $element;
    }

    /**
     * @param mixed $child
     * @param string $element
     * @return string
     */
    public function getChildAnchorElement(mixed $child, string $element): string
    {
        $element = '<a href="' . ($child['item_url'] ?? '') . '">';
        $element .= str_replace(
            ['Index', 'New', 'Log'],
            ['View', 'Add New', 'View Log'],
            Stringify::capitalize($child['item_label'] ?? 'Unknown Child'));
        $element .= '</a>';
        return $element;
    }

    private function hasNotification(object $controller = null)
    {
        $notify = new NotificationModel();
        $count = $notify->getRepo()->count(['notify_status' => 'unread']);
        return ($count !==0) ? '<sup class="uk-badge">' . $count . '</sup>' : null;
    }
}
