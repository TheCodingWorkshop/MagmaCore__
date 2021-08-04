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

namespace MagmaCore\Base\Events;

use MagmaCore\EventDispatcher\Event;

class BeforeRenderActionEvent extends Event
{
    public const NAME = 'magmacore.base.event_before_render_action_event';

}