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

use MagmaCore\Utility\Yaml;
use MagmaCore\Utility\Stringify;

class UikitNavigationExtension
{

    /** @var string */
    public const NAME = 'uikit_navigation';

    /**
     * @param object $controller
     * @return string
     */
    public function register(object $controller = null): mixed
    {
        $element = $activeOpen = '';
        if (isset($controller)) {
            if (is_array($items = Yaml::file('menu')) && count($items) > 0) {
                $element = '<ul class="uk-nav-default uk-nav-parent-icon" uk-nav>';
                foreach ($items as $key => $item) {
                    if ($item) {

                        if (isset($item['header']) && $item['header'] !== '') {
                            $element .= '<li class="uk-nav-header">' . $item['header'] . '</li>';
                            $element .= PHP_EOL;
                        }

                        $isParent = (isset($item['children']) && count($item['children']) > 0);
                        if ($controller->thisRouteController() === strtolower($item['name'])) {
                            $activeOpen = ' uk-open';
                        }

                        $element .= '<li class="' . ($isParent ? 'uk-parent' . $activeOpen : '') . '">';
                        $element .= '<a href="' . (isset($item['path']) ? $item['path'] : '') . '">';
                        $element .= Stringify::capitalize((isset($item['name']) ? $item['name'] : 'Unknown'));
                        $element .= '</a>';
                        if (isset($item['children']) && count($item['children']) > 0) {
                            $element .= '<ul class="uk-nav-sub">';
                            foreach ($item['children'] as $child) {
                                $element .= '<li>';
                                $element .= '<a href="' . (isset($child['path']) ? $child['path'] : '') . '">';
                                $element .= Stringify::capitalize(isset($child['name']) ? $child['name'] : 'Unknown Child');
                                $element .= '</a>';
                                $element .= '</li>';
                            }
                            $element .= '</ul>';
                        }
                        $element .= '

                        ';
                        $element .= '</li>';
                        $element .= PHP_EOL;
                    }
                }
                $element .= '</ul>';
                $element .= PHP_EOL;
            }
        }
        return $element;
    }
}
