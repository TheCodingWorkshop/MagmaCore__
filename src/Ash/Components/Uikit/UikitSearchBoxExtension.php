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

use MagmaCore\Utility\Stringify;
use MagmaCore\UserManager\UserColumn;

class UikitSimplePaginationExtension
{

    /** @var string */
    public const NAME = 'uikit_search';

    /**
     * Register the UIkit default search box
     *
     * @param object|null $controller
     * @return string
     */
    public function register(object $controller = null): string
    {
//        $name = $controller->thisRouteController();
//        $name = Stringify::pluralize($name);
//        $name = Stringify::capitalize($name);
//        // style="z-index: 980;" uk-sticky="offset: 80; bottom: #top; cls-active: uk-card uk-card-body uk-card-default; animation: uk-animation-slide-top"
//        $html = '<section>';
//        $html .= '<nav aria-label="Pagination" uk-navbar>';
//        $html .= '<div class="uk-navbar-left">';
//        $html .= $this->navContentLeft($controller, $name);
//        $html .= '</div>';
//        $html .= '<div class="uk-navbar-center">';
//        //$html .= $this->navContentCentre($controller);
//        $html .= '</div>';
//        $html .= '<div class="uk-navbar-right">';
//        $html .= $this->navContentRight($controller);
//        $html .= '</div>';
//        $html .= '</nav>';
//        $html .= '</section>';
//
//        return $html;

}
