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
        Closure $callback = null
    ): string {
        $filter = (isset($searchFilter) && !empty($searchFilter)) ? true : false;
        $html = '';
        $html .= '<nav uk-navbar class="uk-margin-small-top">';
        $html .= '<div class="nav-overlay uk-navbar-left">';
        $html .= '
        <div class="uk-grid-small uk-flex-middle" uk-grid style="margin-top: -10px;">
            <div class="uk-width-auto uk-label uk-background-teal-s3 uk-padding-small uk-margin-remove-top">
                    <span uk-icon="icon:' . (($filter) ? 'search' : strtolower($headerIcon)) . '; ratio:1.5"></span>
                </div>
                <div class="uk-width-expand">
                <h2 class="uk-text-normal uk-text-emphasis uk-margin-remove-bottom">' . (($filter) ? $totalRecords . ' Records found' : (new Stringify())->pluralize($controller)) . '</h2>
                <p class="uk-text-meta uk-margin-remove-top">
                    <span class"uk-text-justify">
                        <span class="uk-text-bolder uk-text-warning">' . (($filter) ? '<span class="uk-text-muted">Searching For </span>' . (new Stringify())->justify($searchFilter, 'ucwords') . ' - <a href="/admin/user/index">Clear</a>'  : (new Breadcrumbs())->breadcrumbs()) . '</span>
                    </span>
                </p>
                </div>
            </div>';
        $html .= '</div>';

        $html .= '<div class="nav-overlay uk-navbar-right">';
        $html .= (new SearchBoxExtension())->triggerSearchBox();
        if (is_array($actions) && count($actions) > 0) {
            if (is_null($row)) {
                $html .= (new IconNavExtension())->iconNav($actions, $row, $twigExt, $controller, $actionVertical);
            } else {
                // send a warning
            }
        }
        $html .= '</div>';
        $html .= (new SearchBoxExtension())->searchBox();
        $html .= '</nav>';

        return $html;
    }
}
