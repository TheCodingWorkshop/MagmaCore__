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

trait ActionTrait
{

    private function actions(): string
    {

        if (isset($this->controller)) {
            if (in_array($this->controller->thisRouteAction(), $this->controller->commander->unsetAction())) {
                return '';
            }
        }

        $commander = PHP_EOL;
        $commander .= $this->commanderFiltering() ?? ''; // filtering
        $commander .= '<ul class="uk-iconnav">';
        $commander .= '<li>';
        $commander .= '<a href="/admin/' . $this->controller->thisRouteController() . '/log" uk-tooltip="View Log" class="ion-21">';
        $commander .= '<ion-icon name="reader-outline"></ion-icon>';
        $commander .= '</a>';
        $commander .= '</li>';
        $commander .= PHP_EOL;
        $commander .= '<li>';
        $commander .= '<a style="margin-top:-5px;" href="' . $this->actionPath() . '" class="uk-button uk-button-primary uk-button-small uk-link-reset uk-link-toggle">';
        $commander .= $this->actionButton();
        $commander .= '</a>';
        $commander .= '</li>';
        $commander .= PHP_EOL;
        $commander .= '</ul>';

        return $commander;
    }
    private function actionButton(): string
    {
        if (isset($this->controller)) {
            return match ($this->controller->thisRouteAction()) {
                'new', 'edit', 'show', 'hard-delete', 'preferences', 'privileges' => 'Listings',
                default => 'Add new'
            };
        }
    }

    private function actionPath(): string
    {
        if (isset($this->controller)) {
            return match ($this->controller->thisRouteAction()) {
                'new', 'edit', 'show', 'hard-delete', 'preferences', 'privileges' => '/' . $this->controller->thisRouteNamespace() . '/' . $this->controller->thisRouteController() . '/' . 'index',
                'index' => '/admin/' . $this->controller->thisRouteController() . '/new',
                default => 'javascript:history.back()'
            };
        }
    }


}
