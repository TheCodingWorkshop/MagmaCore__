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

namespace MagmaCore\Ash\Extensions;

use Exception;
use MagmaCore\Auth\Authorized;
use MagmaCore\Utility\Singleton;
use MagmaCore\Utility\Stringify;
use MagmaCore\Base\BaseController;

use MagmaCore\Session\SessionTrait;
use MagmaCore\Utility\DateFormatter;

use MagmaCore\Auth\Model\PermissionModel;
use MagmaCore\Ash\Extensions\Modules\NavBarExtension;
use MagmaCore\Ash\Extensions\Modules\IconNavExtension;
use MagmaCore\Ash\Extensions\Modules\SearchBoxExtension;
use MagmaCore\Ash\Extensions\Modules\SubheaderExtension;
use MagmaCore\Ash\Extensions\Modules\ColumnActionExtension;
use MagmaCore\Ash\Extensions\Modules\FlashMessageExtension;

class TemplateExtension
{

    use SessionTrait;

    /**
     * Undocumented function
     *
     * @param array $row
     * @param string $field
     * @param boolean $short
     * @return string
     */
    public function tableDateFormat(array $row, string $field, bool $short = false) : string
    {
        if ($row) {
            $time = $row[$field];
            return DateFormatter::timeFormat($time, $short);
        }
    }

    /**
     * Return the name of the permission based on the permission ID
     *
     * @param integer $permID
     * @return string
     */
    public function getPermissionName(int $permID): string
    {
        return '';
        // $permName = (new PermissionModel())->getRepo()->findObjectBy(['id' => $permID], ['permission_name']);
        // return $permName->permission_name;
    }

    /**
     * @inheritdoc
     * @param array $icons
     * @param array|Object $row
     * @param string $controller
     * @param boolean $vertical
     * @return void
     */
    public function iconNav(array $icons = [], array $row = null, Object $twigExt = null, string $controller = null, bool $vertical = false, \Closure $callback = null)
    {
        return (new IconNavExtension())->iconNav(
            $icons,
            $row,
            $twigExt,
            $controller,
            $vertical,
            $callback
        );
    }

    /**
     * @inheritdoc
     * @param mixed $values
     * @return string
     */
    public function getModal($values): string
    {
        return (new IconNavExtension())->getModal($values);
    }

    /**
     * @inheritdoc
     * @param mixed $values
     * @return string
     */
    public function getDropdown(array $items = [], string|null $status = null, array $row = [], string|null $controller = null): string
    {
        $element = '';
        $_controller = ($controller !==null) ? $controller : '';
        $_row = ($row) ? $row : [];
        if (is_array($items) && count($items) > 0) {
            $element .= '<div uk-dropdown="pos: left-center; mode: click">';
            $element .= '<ul class="uk-nav uk-dropdown-nav">';
            $element .= '<li class="uk-active"><a href="#">' . ($status !==null) ? Stringify::capitalize($status) : 'Status Unknown' . '</a></li>';
            foreach ($items as $key => $item) {
                $element .= '<li>';
                $element .= '<a data-turbo="true" href="'.(isset($item['path']) ? $item['path']:'') . '">';
                $element .= (isset($item['icon']) ? '<ion-icon size="small" name="' . $item['icon'] . '"></ion-icon>' : '');
                $element .= Stringify::capitalize($item['name']);
                $element .= '</a>';
                $element .= '</li>';
                $element .= PHP_EOL;
            }
            $element .= '<li class="uk-nav-divider"></li>';
            $element .= '<li><a data-turbo="true" href="/admin/' . $_controller . '/' . $_row['id'] . '/hard-delete" class="ion-28"><ion-icon name="trash"></ion-icon></a></li>';
            $element .= '</ul>';
            $element .= PHP_EOL;
            $element .= '</div>';
            $element .= PHP_EOL;
        }

        return $element;
    }


    /**
     * @inheritdoc
     * @return void
     */
    public function navMenu(array $items)
    {
        return (new NavBarExtension())->navMenu($items);
    }

    /**
     * @inheritdoc
     * @return void
     */
    public function searchBox(string $filter = 's', string $placeholder = 'Search...')
    {
        return (new SearchBoxExtension())->searchBox($filter, $placeholder);
    }

    /**
     * @inheritdoc
     * @param string $searchFilter
     * @param string $icon
     * @param string $iconColor
     * @param string $iconSize
     * @param string $prefix
     * @param string $controller
     * @param integer $totalRecords
     * @param array $actions
     * @param boolean $actionVertical
     * @param array $row
     * @return string
     */
    public function subHeader(
        string $searchFilter = null,
        string $controller = null,
        int $totalRecords = null,
        array $actions = null,
        bool $actionVertical = false,
        array $row = null,
        ?string $headerIcon = null,
        \Closure $callback = null,
        string $info = null
    ): string {
        return (new SubheaderExtension())->subHeader($searchFilter, $controller, $totalRecords, $actions, $actionVertical, $row, $headerIcon, $callback, $info);
    }

    /**
     * @inheritdoc
     * @param array $action
     * @param array $row
     * @param Object $twigExt
     * @param string $controller
     * @param boolean $vertical
     * @param string $title
     * @param string $description
     * @return string
     */
    public function action(
        array $action,
        array $row = null,
        Object $twigExt = null,
        string $controller,
        bool $vertical = false,
        string $title = null,
        string $description = null
    ): string {
        return (new ColumnActionExtension())->action($action, $row, $twigExt, $controller, $vertical, $title, $description);
    }
}
