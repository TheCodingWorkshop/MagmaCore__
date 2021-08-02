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

namespace MagmaCore\Twig;

use Exception;
use Throwable;
use Twig\TwigFilter;
use Twig\TwigFunction;
use MagmaCore\Utility\Yaml;
use InvalidArgumentException;
use MagmaCore\Auth\Authorized;
use MagmaCore\Utility\Singleton;
use MagmaCore\Utility\Stringify;
use MagmaCore\Base\BaseController;

use MagmaCore\Session\SessionTrait;
use MagmaCore\Utility\DateFormatter;
use Symfony\Component\Asset\Package;
use Twig\Extension\AbstractExtension;
use MagmaCore\Translation\Translation;

use MagmaCore\Auth\Model\PermissionModel;
use MagmaCore\Twig\Extensions\NavBarExtension;
use MagmaCore\Twig\Extensions\IconNavExtension;
use MagmaCore\Twig\Extensions\SearchBoxExtension;
use MagmaCore\Twig\Extensions\SubheaderExtension;
use MagmaCore\Twig\Extensions\ColumnActionExtension;
use MagmaCore\Twig\Extensions\FlashMessageExtension;
use Symfony\Component\Asset\VersionStrategy\EmptyVersionStrategy;
use Symfony\Component\Asset\VersionStrategy\StaticVersionStrategy;

/**
 * Class TwigExtension
 * @package Core\Twig
 */
class TwigExtension extends AbstractExtension implements \Twig\Extension\GlobalsInterface
{

    use SessionTrait;

    public function getFilters(): array
    {
        return [];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('asset', [$this, 'asset']),
            new TwigFunction('locale', [$this, 'locale']),
            new TwigFunction('varDump', [$this, 'varDump']),
            new TwigFunction('Config', [$this, 'Config']),
            new TwigFunction('path', [$this, 'path']),
            new TwigFunction('tableDateFormat', [$this, 'tableDateFormat']),
            new TwigFunction('getPermissionName', [$this, 'getPermissionName']),

            new TwigFunction('flashMessages', [new FlashMessageExtension(), 'flashMessages']),
            new TwigFunction('navMenu', [new NavBarExtension(), 'navMenu']),
            new TwigFunction('iconNav', [new IconNavExtension(), 'iconNav']),
            new TwigFunction('subHeader', [new SubheaderExtension(), 'subHeader']),
            new TwigFunction('subHeader', [new SubheaderExtension(), 'subHeader']),
            new TwigFunction('searchBox', [new SearchBoxExtension(), 'searchBox']),

        ];
    }

    /**
     * @return array
     * @throws GlobalManagerException
     * @throws Throwable
     */
    public function getGlobals(): array
    {
        return [
            'current_user' => Authorized::grantedUser(),
            'app' => Yaml::file('app'),
            'asset' => Yaml::file('asset'),
            'items' => Yaml::file('menu')
        ];
    }

    /**
     * Return the relative path for our resources
     *
     * @param string $path
     * @return string
     */
    public function asset($path)
    {
        return (new Package(
            new StaticVersionStrategy(
                'v1',
                '%s?version=%s'
            )
        ))->getUrl($path);
    }

    public function locale(string $string)
    {
        //return Translation::getInstance()->$string;
        return $string;
    }

    /**
     * @param $var
     * @return bool
     *
     */
    public function varDump($var)
    {
        if (!empty($var)) {
            var_dump($var);
        }
        return false;
    }

    /**
     * @param $file
     * @return mixed
     * @throws Exception
     */
    public function Config($file)
    {
        return Yaml::file($file);
    }

    public function path(Object $object, string $action, int $row_id = 0): string
    {
        if ($object instanceof BaseController) {
            if ($row_id === 0) {
                $path = "/admin/{$object->thisRouteController()}/{$action}";
            } else {
                $path = "/admin/{$object->thisRouteController()}/{$row_id}/{$action}";
            }
            if ($path) {
                return $path;
            }
        }
    }

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
            //if ($short) {
                return DateFormatter::timeFormat($time, $short);
            //} else {
               // return DateFormatter::formatLong(strtotime($time));
            //}
            
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


    /*=======================================================================================*/
    /* TWIG EXTENSIONS */
    /*=======================================================================================*/

    /**
     * @inheritdoc
     * @return string
     * @throws GlobalManager
     * @throws Exception
     * @throws GlobalManagerException
     */
    public function flashMessages()
    {
        return $this->flashMessages();
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
