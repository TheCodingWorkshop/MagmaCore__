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

class CommanderNotification
{

    public function notifications(object $comm): string
    {
        if (isset($comm->controller)) {
            if (in_array($comm->controller->thisRouteAction(), $comm->controller->commander->unsetNotification())) {
                return '';
            }
        }
        //$off = '<ion-icon name="notifications-off-outline"></ion-icon>';
        $command = '<li class="uk-active">';
        $command .= '<a href="javascript:void()" class="uk-text-muted">';
        $command .= '<ion-icon size="large" name="notifications-outline"></ion-icon>';
        $command .= '<span><sup class="uk-badge">3</sup></span>';
        $command .= '</a>';
        $command .= '</li>';

        return $command;
    }

}