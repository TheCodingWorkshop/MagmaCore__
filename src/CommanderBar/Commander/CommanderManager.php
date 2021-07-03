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

namespace MagmaCore\CommanderBar\Commander;

use MagmaCore\CommanderBar\CommanderBar;
use MagmaCore\Utility\Stringify;

class CommanderManager
{

    public function manager(object $comm): string
    {
        if (isset($comm->controller)) {
            if (in_array($comm->controller->thisRouteAction(), $comm->controller->commander->unsetManager())) {
                return '';
            }
        }

        $command = PHP_EOL;
        $command .= '<li>';
        $command .= '<a href="#"><ion-icon size="large" name="home-outline"></ion-icon></a>';
        $command .= '<div uk-dropdown="mode: click" class="uk-navbar-dropdown uk-navbar-dropdown-width-3">';
        $command .= '<div class="uk-navbar-dropdown-grid uk-child-width-1-3" uk-grid>';
        $command .= '<div class="uk-width-1-3">';

        if (is_array($lists = $comm->controller->commander->getYml()) && count($lists) > 0) {
            $command .= '<ul class="uk-nav uk-navbar-dropdown-nav">';
            foreach ($lists as $key => $value) {
                if (isset($value['nav_header']) && $value['nav_header'] !== '') {
                    $command .= '<li class="uk-nav-header">' . $value['nav_header'] . '</li>';
                }
                if ($comm->controller->thisRouteAction() === $key) {
                    unset($value);
                }
                $command .= PHP_EOL;
                $command .= '<li>';
                $command .= '<a uk-toggle="target: #toggle-custom; cls: highlight" href="' . ($value['path'] ?? $this->path($key)) . '">';
                $command .= (isset($value['name']) ? Stringify::capitalize($value['name']) : '');
                $command .= '</a>';
                $command .= '</li>';
                $command .= PHP_EOL;
            }
            $command .= '<li class="uk-nav-divider"></li>';
            $command .= '<li>';
            $command .= '<a href="" uk-tooltip="View Trash" class="ion-28">';
            $command .= '<ion-icon name="trash"></ion-icon>';
            $command .= '</a>';
            $command .= '</li>';
            $command .= '</ul>';
        }

        $command .= '</div>';
        $command .= PHP_EOL;
        $command .= '<div class="uk-width-expand">';
        $command .= '<div>';
        $command .= '<div class="uk-card">';
        if (isset($comm->controller)) {
            if (in_array($comm->controller->thisRouteAction(), ['new'])) {
                $command .= '<h3 class="uk-card-title">Change Status</h3>';
                if (is_array($statusColumns = $comm->controller->repository->getColumnStatus()) && count($statusColumns) > 0) {
                    $command .= '<ul class="uk-nav uk-dropdown-nav">';
                    foreach ($statusColumns as $key => $value) {
                        foreach ($value as $val) {
                            $command .= '<li>';
                            $command .= '<a class="uk-text-success uk-link-reset uk-text-capitalize" href="?' . $key . '=' . $val . '">';
                            $badgeColor = ['pending' => 'warning', 'active' => 'success', 'trash' => 'danger', 'lock' => 'secondary'];
                            $command .= '<span class="uk-badge uk-badge-' . $badgeColor[$val] . ' uk-margin-small-right">';
                            $command .= $comm->controller->repository->getRepo()->count([$key => $val]);
                            $command .= '</span>' . Stringify::capitalize($val);
                            $command .= '</a>';
                            $command .= '';
                            $command .= '</li>';
                        }
                    }
                    $command .= '<li class="uk-nav-divider"></li>';
                    $command .= '<li><a class="ion-24" href="/admin/user/index">';
                    $command .= '<ion-icon size="large" name="home-outline"></ion-icon> <span class="uk-text-meta"><span class="uk-badge uk-text-bolder">' . $this->controller->repository->getRepo()->count() . ' </span> total records within this model</span></a>';
                    $command .= '</li>';
                    $command .= '</ul>' . PHP_EOL;
                }
            } else {
                $command .= '<p>Not enough data to generate a graph</p>';
                /* display individual user graph */
            }
        }

        $command .= '</div>';
        $command .= '</div>';
        $command .= '</div>';
        $command .= PHP_EOL;
        $command .= '</div>';
        $command .= '</div>';
        $command .= '</li>';
        $command .= PHP_EOL;

        return $command;
    }

}