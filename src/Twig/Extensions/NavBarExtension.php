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

namespace MagmaCore\Twig\Extensions;

class NavBarExtension
{

    /**
     * @param array $items
     * @return string
     */
    public function navMenu(array $items = [])
    {
        $html = '';
        if (is_array($items) && !empty($items)) {
            $html .= '<ul class="uk-nav-default uk-nav-parent-icon" uk-nav>';
            foreach ($items as $key => $item) {
                if (isset($key) && $key != "") {
                    $html .= '<li class="' . (isset($item["children"]) ? "uk-parent" : "") . '">';
                    if (isset($item["children"]) && !empty($item["children"])) {
                        $html .= '<a href="' . $item["path"] . '">';
                        if (isset($item["icon"]) && $item["icon"] != "") {
                            $html .= '<span class="uk-margin-small-right" uk-icon="icon:' . (isset($item["icon"]) ? $item["icon"] : "") . '"></span>';
                        }
                        $html .= $item["title"];
                        $html .= '</a>';
                        if (is_array($item["children"]) && count($item["children"]) > 1) {
                            $html .= '<ul class="uk-nav-sub">';
                            foreach ($item["children"] as $k => $children) {
                                $html .= '<li>';
                                $html .= '<a href="' . $children["path"] . '">' . (isset($children["title"]) ? $children["title"]  : $k) . '</a>';
                                $html .= '</li>';
                            }
                            $html .= '</ul>';
                        }
                    } else {
                        $html .= '<a href="' . (isset($item["path"]) ? $item["path"] : "") . '">';
                        if (isset($item["icon"]) && $item["icon"] != "") {
                            $html .= '<span class="uk-margin-small-right" uk-icon="icon:' . (isset($item["icon"]) ? $item["icon"] : "") . '"></span>';
                        }
                        $html .= (isset($item["title"]) ? $item["title"] : "");
                        $html .= '</a>';
                    }
                    $html .= '</li>';
                    if (isset($item["header"]) && $item["header"] != "") {
                        $html .= '<li class="uk-nav-header">' . $item["header"] . '</li>';
                    }
                    if (isset($item["divider"]) && $item["divider"] == true) {
                        $html .= '<li class="uk-nav-divider"></li>';
                    }
                }
            }
            $html .= '</ul>';
        }

        return $html;
    }

}