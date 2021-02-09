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
     * @return string
     */
    public function subheader(
        string $searchFilter = null,
        string $controller = null,
        int $totalRecords = null,
        array $actions = null,
        bool $actionVertical = false,
        array $row = null,
        Object $twigExt = null
    ): string {
        $html = '';
        $html .= '<nav uk-navbar class="uk-margin-small-top">';
        $html .= '<div class="uk-navbar-left">';
        $html .= (new SearchBoxExtension())->searchBox();
        $html .= '</div>';

        $html .= '<div class="uk-navbar-center">';
        $html .= '';
        $html .= '</div>';

        $html .= '<div class="uk-navbar-right">';
        if (isset($searchFilter) && !empty($searchFilter)) {
            $html .= '<p class="uk-text-large" style="margin-top: 20px;">';
            $html .= '<span class="uk-text-teal" uk-icon="icon: search; ratio: 2"></span>&nbsp;';
            $html .= $totalRecords . ' ' . 'Records Found for <span class="uk-text-primary">`' . $searchFilter . '`</span>';
            $html .= '</p>';
            
        } else {
            if (is_array($actions) && count($actions) > 0) {
                if (is_null($row)) {
                    $html .= (new IconNavExtension())->iconNav($actions, $row, $twigExt, $controller, $actionVertical);
                } else {
                    /* send a warning */
                }
            }

        }
        $html .= '</div>';
        $html .= '</nav>';

        return $html;
    }
}
