<?php
/*
 * This file is part of the MagmaCore package.
 *
 * (c) Ricardo Miller <ricardomiller@lava-studio.co.uk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare (strict_types = 1);

namespace MagmaCore\Twig\Extensions;

use MagmaCore\Twig\Extensions\IconNavExtension;

class SubheaderExtension
{

    /**
     * Undocumented function
     *
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
    public function subheader(
        string $searchFilter = null, 
        string $icon = null, 
        string $iconColor = null,
        string $iconSize = null,
        string $prefix = null,
        string $controller = null,
        int $totalRecords = null,
        array $actions = null,
        bool $actionVertical = false,
        array $row = null) : string
    {
        $html = '';
        $html .= '<nav uk-navbar class="uk-margin-small-top">';
            $html .= '<div class="uk-navbar-left">';
                $html .= '<p class="uk-text-large">';
                    if (!empty($searchFilter)) {
                        $html .= '<span class="uk-text-' . (isset($iconColor) ? $iconColor : 'primary') . '" uk-icon="icon:search; ratio:3"></span>&nbsp;';
                        $html .= $totalRecords . ' ' . 'records_found';
                    } else {
                        $html .= '<span class="uk-text-' . (isset($iconColor) ? $iconColor : 'primary') . '" uk-icon="icon:' . $icon . '; ratio:' . $iconSize . '"></span>&nbsp;';
                        $html .= (null !==$prefix) ? $prefix : $controller;
                    }
                $html .= '</p>';
            $html .= '</div>';

            $html .= '<div class="uk-navbar-center">';
            $html .= '</div>';

            $html .= '<div class="uk-navbar-right">';
                if (is_array($actions) && count($actions) > 0) {
                    if (is_null($row)) {
                        $html .= (new IconNavExtension())->iconNav($actions, $row, $controller, $actionVertical);
                    } else {
                        /* send a warning */
                    }
                }
            $html .= '</div>';
        $html .= '</nav>';

        return $html;
    }

}
