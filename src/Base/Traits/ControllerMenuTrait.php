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

namespace MagmaCore\Base\Traits;

use ReflectionMethod;
use ReflectionException;
use MagmaCore\Utility\Yaml;
use MagmaCore\Utility\ClientIP;
use MagmaCore\Utility\Stringify;
use MagmaCore\Auth\Model\MenuModel;
use MagmaCore\Base\Events\EventLogger;
use MagmaCore\Auth\Model\MenuItemModel;
use MagmaCore\DataObjectLayer\DataLayerTrait;
use MagmaCore\System\Event\SystemActionEvent;
use MagmaCore\Base\Traits\BaseReflectionTrait;
use MagmaCore\System\EventTrait\SystemEventTrait;

trait ControllerMenuTrait
{

    use DataLayerTrait;
    use BaseReflectionTrait;
    use SystemEventTrait;
    use ControllerTrait;

    private array $usables = [
        'index' => 'View All',
        'new' => 'Add New',
        'log' => 'Logs',
        'statistics' => 'Statistics'
    ];

    /**
     * Return an instance of the menu item model
     * @return object
     */
    public function getMenuItem(): object
    {
        return new MenuItemModel();
    }

    /**
     * return an instance of the menu model
     * @return MenuModel
     */
    private function getMenu(): object
    {
        return new MenuModel();
    }

    /**
     * Return a single query result row from the database based on the argument conditions
     * @param array $routeParams
     * @return object
     */
    public function getControllerMenu(array $condition): ?object
    {
        $menu = $this->getMenu();
        return $menu->getRepo()->findObjectBy($condition);
    }

    /**
     * Return a single query result row from the database based on the argument conditions
     * @param array $routeParams
     * @return object
     */
    public function getControllerMenuItem(array $routeParams): ?object
    {
        $menuItem = $this->getMenuItem();
        return $menuItem->getRepo()->findBy(['item_original_label' => $routeParams['controller']]);
    }

    /**
     * Automatically build a parent menu and parent menu items when a controller is requested.
     * This process only happens once. ie. It will not rebuild an already build menu and 
     * menu items.
     *
     * @param array $routeParams
     * @return bool
     * @throws ReflectionException
     */
    public function buildControllerMenu(array $routeParams): bool
    {
        if (count($routeParams)) {
            $disallowedControllers = Yaml::file('app')['disallowed_controllers'];
            if (!in_array($routeParams['controller'], $disallowedControllers)) {
                $controllerMenu = $this->getControllerMenu(['menu_name' => $routeParams['controller']]);
                if (!isset($controllerMenu)) {
                    $fields = [
                        'menu_name' => $routeParams['controller'],
                        'menu_description' => $routeParams['controller'] . ' parent menu item.',
                        'menu_order' => null,
                        'menu_break_point' => null,
                        'menu_icon' => 'alert',
                        'parent_menu' => (isset($routeParams['controller']) ? 1 : 0), //true/false
                    ];
                    $new = $this->getMenu()->getRepo()->getEm()->getCrud()->create($fields);
                    if ($new) {
                        $lastMenuID = $this->getMenu()->getRepo()->fetchLastID();
                        $this->hasMenuItems($routeParams, $lastMenuID);

                        /* log Event data */
                        $context = ['menu' => $fields, 'last_id' => $lastMenuID, 'status' => $new];
                        $browser = get_browser(null, true);
                        $eventContext = [EventLogger::SYSTEM, EventLogger::INFORMATION, $this->getSession()->get('user_id'), __METHOD__, SystemActionEvent::NAME, serialize($context), serialize($browser), ClientIP::getClientIp()];
                        $this->logSystemEvent(__METHOD__, $eventContext, $this);
                        /* end */
                        $columnString = '\App\DataColumns\\' . ucwords($routeParams['controller'] . 'Column');
                        // if (class_exists($columnString)) {
                        //     $this->initializeControllerSettings($routeParams['controller'], $columnString, $lastMenuID);
                        // }
                        return true;
                    }
                }
            }
        }
        return false;
    }

    /**
     * Checks whether the parent menu has any child for example check if the controller
     * has any methods. which ultimately becomes the child of that parent menu/controller
     *
     * @param array $routeParams
     * @param int $lastMenuID
     * @param object $parentMenu
     * @throws ReflectionException
     * @return bool
     */
    private function hasMenuItems(array $routeParams, int $lastMenuID): bool
    {
        $parentMenu = $this->getControllerMenu(['id' => $lastMenuID]);
        if (isset($parentMenu->menu_name) && $parentMenu->menu_name !==null ) {
            $controllerName = Stringify::studlyCaps($parentMenu->menu_name . 'Controller');
            $namespace = (isset($routeParams['namespace']) ? '\App\Controller\Admin\\' . $controllerName : '\App\Controller\\' . $controllerName);
            $reflectionClass = $this->reflection($namespace ?? null);
            /* We only want the protected methods */
            $hasMethods = $this->reflection($namespace)->methods(ReflectionMethod::IS_PROTECTED);
            if (is_array($hasMethods) && count($hasMethods) > 0) {
                return $this->buildMenuItems($routeParams, $hasMethods, $parentMenu);
            }
            return false;
        }
    }

    /**
     * Insert all parent menu items within the menu_item database table. Each item will be
     * the child of the current controller/menu.
     *
     * @param array $routeParams
     * @param array $methods
     * @return bool
     */
    public function buildMenuItems(array $routeParams, array $methods, object $parentMenu): bool
    {
        if (!isset($parentMenu)) {
            return false;
        }
        if (is_array($methods) && count($methods) > 0) {
            $itemName = '';
            array_map(function($method) use ($parentMenu, $routeParams){
                $count = 0;
                if (str_contains($method->name, 'Action')) {
                    $newMethodName = str_replace('Action', '', $method->name);
                    $itemName = explode(' ', $newMethodName);
                    $fields = [
                        'item_original_id' => $parentMenu->id,
                        'item_original_label' => $routeParams['controller'] . '_' . $method->name,
                        'item_label' => $itemName[0],
                        'item_type' => 'child_of_' . $routeParams['controller'],
                        'item_url' => $this->buildItemUrl($itemName[0], $routeParams),
                        'item_order' => $count + $count,
                        'item_usable' => $this->getUsableMenuItems($itemName[0], $routeParams)
                    ];

                    return $this->getMenuItem()->getRepo()->getEm()->getCrud()->create($fields);

                }
            }, $methods);
        }

        return false;

    }

    /**
     * @param string $itemName
     * @param array $routeParams
     * @return bool
     */
    private function getUsableMenuItems(string $itemName, array $routeParams): bool
    {
        if (in_array($itemName, array_keys($this->usables))) {
            return true;
        }
        return false;
    }

    /**
     * @param string $itemName
     * @param array $routeParams
     * @return string
     */
    private function buildItemUrl(string $itemName, array $routeParams): string
    {
        $url = '/';
        $url .= isset($routeParams['namespace']) ? $routeParams['namespace'] : '';
        $url .= '/';
        $url .= isset($routeParams['controller']) ? $routeParams['controller'] : '';

        if (isset($routeParams['id']) && $routeParams['id'] !=='') {
            $url .= '/';
            $url .= $routeParams['id'];
        } else {
            $url .= '/';
        }

        $url .= $itemName;

        return strtolower($url);
    }

}