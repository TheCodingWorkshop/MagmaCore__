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

namespace MagmaCore\CommanderBar\Traits;

use MagmaCore\Utility\Stringify;

trait ManagerTrait
{

    private function manager(): string
    {
        if (isset($this->controller)) {
            if (in_array($this->controller->thisRouteAction(), $this->controller->commander->unsetManager())) {
                return '';
            }
        }

        $commander = PHP_EOL;
        $commander .= '<li>';
        $commander .= '<a href="#"><ion-icon size="large" name="home-outline"></ion-icon></a>';
        $commander .= '<div uk-dropdown="mode: click" class="uk-navbar-dropdown uk-navbar-dropdown-width-3">';
        $commander .= '<div class="uk-navbar-dropdown-grid uk-child-width-1-3" uk-grid>';
        $commander .= '<div class="uk-width-1-3">';

        if (is_array($lists = $this->controller->commander->getYml()) && count($lists) > 0) {
            $commander .= '<ul class="uk-nav uk-navbar-dropdown-nav">';
            foreach ($lists as $key => $value) {
                if (isset($value['nav_header']) && $value['nav_header'] !== '') {
                    $commander .= '<li class="uk-nav-header">' . $value['nav_header'] . '</li>';
                }
                if ($this->controller->thisRouteAction() === $key) {
                    unset($value);
                }
                $commander .= PHP_EOL;
                $commander .= '<li>';
                $commander .= '<a uk-toggle="target: #toggle-custom; cls: highlight" href="' . ($value['path'] ?? $this->path($key)) . '">';
                $commander .= (isset($value['name']) ? Stringify::capitalize($value['name']) : '');
                $commander .= '</a>';
                $commander .= '</li>';

                $commander .= PHP_EOL;
            }
            $commander .= '<li class="uk-nav-divider"></li>';
            $commander .= '<li>';
            $commander .= '<a href="/admin/' . $this->controller->thisRouteController() . '/index" uk-tooltip="View Trash" class="ion-28">';
            $commander .= '<ion-icon name="home"></ion-icon>';
            $commander .= '</a>';
            $commander .= '</li>';
            $commander .= '</ul>';
        }

        $commander .= '</div>';
        $commander .= PHP_EOL;
        $commander .= '<div class="uk-width-expand">';
        $commander .= '<div>';
        $commander .= '<div class="uk-card">';
        if (isset($this->controller)) {
            if (in_array($this->controller->thisRouteAction(), $this->controller->commander->unsetManager())) {
                $commander .= '<h3 class="uk-card-title">Change Status</h3>';
                if (is_array($statusColumns = $this->controller->repository->getColumnStatus()) && count($statusColumns) > 0) {
                    $commander .= '<ul class="uk-nav uk-dropdown-nav">';
                    foreach ($statusColumns as $key => $value) {
                        foreach ($value as $val) {
                            $commander .= '<li>';
                            $commander .= '<a class="uk-text-success uk-link-reset uk-text-capitalize" href="?' . $key . '=' . $val . '">';
                            $badgeColor = ['pending' => 'warning', 'active' => 'success', 'trash' => 'danger', 'lock' => 'secondary'];
                            $commander .= '<span class="uk-badge uk-badge-' . $badgeColor[$val] . ' uk-margin-small-right">';
                            $commander .= $this->controller->repository->getRepo()->count([$key => $val]);
                            $commander .= '</span>' . Stringify::capitalize($val);
                            $commander .= '</a>';
                            $commander .= '';
                            $commander .= '</li>';
                        }
                    }
                    $commander .= '<li class="uk-nav-divider"></li>';
                    $commander .= '<li><a class="ion-24" href="/admin/user/index">';
                    $commander .= '<ion-icon size="large" name="home-outline"></ion-icon> <span class="uk-text-meta"><span class="uk-badge uk-text-bolder">' . $this->controller->repository->getRepo()->count() . ' </span> total records within this model</span></a>';
                    $commander .= '</li>';
                    $commander .= '</ul>' . PHP_EOL;
                }
            } else {
                    $commander .= '<p>Not enough data to generate a graph</p>';
                /* display individual user graph */
            }
        }

        $commander .= '</div>';
        $commander .= '</div>';
        $commander .= '</div>';
        $commander .= PHP_EOL;
        $commander .= '</div>';
        $commander .= '</div>';
        $commander .= '</li>';
        $commander .= PHP_EOL;

        return $commander;
    }


}