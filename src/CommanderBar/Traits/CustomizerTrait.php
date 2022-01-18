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

trait CustomizerTrait
{

    private function customizer(): string
    {
        if (isset($this->controller)) {
            if (in_array($this->controller->thisRouteAction(), $this->controller->commander->unsetCustomizer())) {
                return '';
            }
        }
        $commander = '<li>';
        $commander .= '<a href="#"><ion-icon size="large" name="settings-outline"></ion-icon></a>';
        $commander .= '<div uk-dropdown="mode: click" class="uk-navbar-dropdown uk-navbar-dropdown-width-3">';

        $commander .= '<div class="uk-navbar-dropdown-grid uk-child-width-1-3" uk-grid>';

        $commander .= '<div class="uk-width-1-3">';
        $commander .= '<div class="uk-margin">';
        $commander .= '<div class="uk-form-label">Toggle Columns</div>';
        $commander .= '<hr>';
        $commander .= PHP_EOL;
        $commander .= '<div class="uk-form-controls">';
        if (is_array($columns = $this->controller->tableGrid->getDataColumns())) {
            foreach ($columns as $column) {
                /* We do not need the action column being togglable so will disable it here */
                if ($column['dt_row'] === 'Action') {
                    $column['show_column'] = false;
                }

                if (isset($column['show_column']) && $column['show_column'] != false) {
                    if ($column['db_row'] === 'id') {
                        $column['show_column'] = true;
                    }

                    $commander .= '<label uk-toggle="target: #toggle-' . $column['db_row'] . '">';
                    $commander .= '<input class="uk-checkbox" type="checkbox" name="' . $column['db_row'] . '">';
                    $commander .= ' ' . Stringify::capitalize($column['dt_row']);
                    $commander .= '</label>';
                    $commander .= '<br>';
                    $commander .= PHP_EOL;
                }
            }
        }
        $commander .= '</div>';
        $commander .= PHP_EOL;
        $commander .= '</div>';
        $commander .= '</div>';
        $commander .= PHP_EOL;

        $commander .= '<div class="uk-width-expand">';
        $commander .= '<div>';
        $commander .= '<div class="uk-card">';
        $commander .= '<h3 class="uk-card-title">Settings</h3>';
        $commander .= $this->customizerSettings();
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

    private function customizerSettings(): string
    {
        //return '';
        // $this->controller
        // ->controllerRepository
        // ->getRepo()
        // ->findObjectBy(['controller_name' => $this->controller->thisRouteController()]) ?? '<span class="ion-64 uk-float-left"><ion-icon name="alert-circle-outline"></ion-icon></span><small class="uk-float-left uk-margin-medium-top">Settings Unavailable.</small>',

        return $this->controller
        ->controllerSettingsForm
        ->createForm(
            "/admin/{$this->controller->thisRouteController()}/settings",
            $this->controller->controllerRepository,
            $this->controller
        );


    }


}
