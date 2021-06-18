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

use JetBrains\PhpStorm\Pure;
use MagmaCore\Utility\Stringify;

class UikitDropdownExtension
{

    /** @var string */
    public const NAME = 'uikit_dropdown';

    /**
     * Get the session flash messages on the fly.
     *
     * @param object|null $controllerObj
     * @param array $items
     * @param string|null $status
     * @param array $row
     * @param string|null $controller - the current controller object
     * @return string
     */
    #[Pure] public function register(
        object $controllerObj = null,
        array $items = [], 
        string|null $status = null, 
        array $row = [], 
        string|null $controller = null): string
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
                        $element .= '<a data-turbo="false" href="'.($item['path'] ?? '') . '">';
                        $element .= (isset($item['icon']) ? '<ion-icon size="small" name="' . $item['icon'] . '"></ion-icon>' : '');
                        $element .= Stringify::capitalize($item['name']);
                        $element .= '</a>';
                        $element .= '</li>';
                        $element .= PHP_EOL;
                    }
                    $element .= '<li class="uk-nav-divider"></li>';
                    $element .= '<li><a data-turbo="false" href="/admin/' . $_controller . '/' . $_row['id'] . '/hard-delete" class="ion-28"><ion-icon name="trash"></ion-icon></a></li>';
                $element .= '</ul>';
                $element .= PHP_EOL;
            $element .= '</div>';
            $element .= PHP_EOL;
        }

        return $element;
    }

}