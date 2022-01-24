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
use MagmaCore\Auth\Roles\PrivilegedUser;
use MagmaCore\Utility\Stringify;
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
                $element = '<ul class="uk-nav-default uk-nav-parent-icon" uk-nav>';
                $element .= '<li class="uk-nav-header">Manage
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
            $element .= '<span style="margin-bottom: 15px;" class="uk-margin-small-right"><ion-icon class="ion-' . $config->get('menu_icon_size') . '" name="' . $item['menu_icon'] . '-outline"></ion-icon></span>';
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
}
