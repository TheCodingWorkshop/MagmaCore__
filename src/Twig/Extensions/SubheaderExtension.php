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

use MagmaCore\Twig\Extensions\IconNavExtension;
use MagmaCore\Twig\Extensions\SearchBoxExtension;
use MagmaCore\Utility\Stringify;
use MagmaCore\Utility\Breadcrumbs;
use Closure;

class SubheaderExtension
{

    /**
     * Undocumented function
     *
     * @param string $searchFilter
     * @param string $controller
     * @param integer $totalRecords
     * @param array $actions
     * @param boolean $actionVertical
     * @param array $row
     * @param Closure $callback
     * @return string
     */
    public function subheader(
        string $searchFilter = null,
        string $controller = null,
        int $totalRecords = null,
        array $actions = null,
        bool $actionVertical = false,
        array $row = null,
        Object $twigExt = null,
        ?string $headerIcon = null,
        Closure $callback = null,
        string $info = null
    ): string { //
        $html = '';
        $html .= '<nav uk-navbar-container" uk-navbar>';
        $html .= '<div class="uk-navbar-left">';
        $html .= '<div class="uk-grid-small uk-flex-middle" uk-grid style="margin-top: -10px;">';
        $html .= '<div class="uk-width-auto">';
        if (str_contains($headerIcon, 'ion')) {
            $html .= '
                <h1><span class="' . $headerIcon . '" style="font-size:48px;">
                </span> ' . (new Stringify())->pluralize(ucwords($controller)) . '</h1>';
        } else {
            $html .= '
            <h1><span uk-icon="icon:' . strtolower($headerIcon) . '; ratio:2.5">
            </span> ' . (new Stringify())->pluralize(ucwords($controller)) . '</h1>';
        }
        $html .= '<p>' . ($info !==null ? $info : '') . '</p>';
        $html .= '<ul class="uk-breadcrumb">
                            <li><a href="/">Home</a></li>
                            <li><span href="">Dashboard</span></li>
                        </ul>';

        $html .= '</div>';
        $html .= '</div>';
        $html .= '</div>';

        $html .= '<div class="uk-navbar-right">';
        if (is_array($actions) && count($actions) > 0) {
            if (is_null($row)) {
                $html .= (new IconNavExtension())->iconNav($actions, $row, $twigExt, $controller, false);
            } else {
                // send a warning
            }
        }
        $html .= '</div>';
        $html .= '</nav>';

        return $html;
    }
}
