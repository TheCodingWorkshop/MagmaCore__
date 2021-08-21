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
use MagmaCore\Utility\Yaml;
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
    public function hideMenuIfNoPermission($controller, $permission): bool
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
        $routeController = $controller->thisRouteController();
        $element = $active = '';
        if (isset($controller)) {
            //$query = 'SELECT * FROM menus JOIN menu_item ON menus.id = menu_item.item_original_id';
            $query = 'SELECT * FROM menus ORDER BY menu_order DESC';
            $data = $this->repo->getClientCrud()->rawQuery($query, [], 'fetch_all');
            if (is_array($data) && count($data) > 0) {
                $element = '<ul class="uk-nav-default uk-nav-parent-icon" uk-nav>';
                $element .= '<li class="uk-nav-header">Actions</li>';
                $element .= '<hr>';
                foreach ($data as $key => $item) {

                    $childQuery = 'SELECT * FROM menu_item WHERE item_original_id = ' . $item['id'];
                    $children = $this->repo->getClientCrud()->rawQuery($childQuery, [], 'fetch_all');

                    if ($item) {
                        $isParent = (isset($children) && count($children) > 0);
                        $active = ($routeController === $item['menu_name'] && $routeController !=='dashboard') ? 'uk-open' : '';
                        $element .= '<li class="' . ($isParent ? 'uk-parent' . $active : '') . '">';
                        $element .= '<a href="' . ($item['path'] ?? 'javascript:void(0)') . '">';
                        $element .= Stringify::capitalize(($item['menu_name'] ?? 'Unknown'));
                        $element .= '</a>';
                        if ($isParent) {
                            $element .= '<ul class="uk-nav-sub uk-navbar-primary">';
                            foreach ($children as $child) {
                                if ($child['item_usable'] === 1) {
                                    //if ($this->hideMenuIfNoPermission($controller, 'can_view')) {
                                        $element .= '<li>';
                                            $element .= '<a href="' . ($child['item_url'] ?? '') . '">';
                                            $element .= str_replace(
                                                ['Index', 'New', 'Log'],
                                                ['View', 'Add New', 'View Log'],
                                                Stringify::capitalize($child['item_label'] ?? 'Unknown Child'));
                                            $element .= '</a>';
                                        $element .= '</li>';
                                    //}
                                }
                            }
                            $element .= '</ul>' . PHP_EOL;
                        }
                        $element .= '</li>' . PHP_EOL;
                    }

                     if (isset($item['menu_break_point']) && $item['menu_break_point'] !==null){
                        $element .= '<li class="uk-nav-header">' . $item['menu_break_point'] ?? null . '</li>';
                        continue;
                    }
                }

                $element .= '</ul>';
                $element .= PHP_EOL;
            }
        }
        return $element;
    }
}
