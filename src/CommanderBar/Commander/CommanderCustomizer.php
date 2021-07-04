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

use MagmaCore\Utility\Stringify;

class CommanderCustomizer
{

    public function customizer(object $comm): string
    {
        if (isset($comm->controller)) {
            if (in_array($comm->controller->thisRouteAction(), $comm->controller->commander->unsetCustomizer())) {
                return '';
            }
        }
        $command = '<li>';
        $command .= '<a href="#"><ion-icon size="large" name="settings-outline"></ion-icon></a>';
        $command .= '<div uk-dropdown="mode: click" class="uk-navbar-dropdown uk-navbar-dropdown-width-3">';

        $command .= '<div class="uk-navbar-dropdown-grid uk-child-width-1-3" uk-grid>';

        $command .= '<div class="uk-width-1-3">';
        $command .= '<div class="uk-margin">';
        $command .= '<div class="uk-form-label">Toggle Columns</div>';
        $command .= '<hr>';
        $command .= PHP_EOL;
        $command .= '<div class="uk-form-controls">';
        if (is_array($columns = $comm->controller->tableGrid->getDataColumns())) {
            foreach ($columns as $column) {
                if (isset($column['show_column']) && $column['show_column'] != false) {
                    if ($column['db_row'] === 'id') {
                        $column['show_column'] = true;
                    }
                    $command .= '<label uk-toggle="target: #toggle-' . $column['db_row'] . '">';
                    $command .= '<input class="uk-checkbox" type="checkbox" name="' . $column['db_row'] . '">';
                    $command .= ' ' . Stringify::capitalize($column['dt_row']);
                    $command .= '</label>';
                    $command .= '<br>';
                    $command .= PHP_EOL;
                }
            }
        }
        $command .= '</div>';
        $command .= PHP_EOL;
        $command .= '</div>';
        $command .= '</div>';
        $command .= PHP_EOL;

        $command .= '<div class="uk-width-expand">';
        $command .= '<div>';
        $command .= '<div class="uk-card">';
        $command .= '<h3 class="uk-card-title">Settings</h3>';
        $command .= $comm->controller
            ->controllerSettings
            ->createForm(
                "/admin/{$comm->controller->thisRouteController()}/settings",
                $comm->controller
                    ->controllerRepository
                    ->getRepo()
                    ->findObjectBy(['controller_name' => $this->controller->thisRouteController()]) ?? '<span class="ion-64 uk-float-left"><ion-icon name="alert-circle-outline"></ion-icon></span><small class="uk-float-left uk-margin-medium-top">Settings Unavailable.</small>',
                $comm->controller
            );

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