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

class CommanderAction
{

    public function actions(object $comm): string
    {
        if (isset($comm->controller)) {
            if (in_array($comm->controller->thisRouteAction(), $comm->controller->commander->unsetAction())) {
                return '';
            }
        }
        $command = PHP_EOL;
        $command .= $comm->commanderFiltering() ?? ''; // filtering
        $command .= '<ul class="uk-iconnav">';
        $command .= '<li>';
        $command .= '<a href="/admin/' . $comm->controller->thisRouteController() . '/log" uk-tooltip="View Log" class="ion-28">';
        $command .= '<ion-icon name="reader-outline"></ion-icon>';
        $command .= '</a>';
        $command .= '</li>';
        $command .= PHP_EOL;
        $command .= '<li>';
        $command .= '<a href="' . $comm->actionPath() . '" uk-tooltip="Go Back" class="uk-button uk-button-primary uk-button-small uk-link-reset uk-link-toggle">';
        $command .= $comm->actionButton();
        $command .= '</a>';
        $command .= '</li>';
        $command .= PHP_EOL;
        $command .= '</ul>';

        return $command;
    }

}