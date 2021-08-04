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

namespace MagmaCore\Base\EventSubscriber;

use JetBrains\PhpStorm\ArrayShape;
use MagmaCore\EventDispatcher\Event;
use MagmaCore\Base\Events\BeforeControllerActionEvent;
use MagmaCore\EventDispatcher\EventSubscriberInterface;

class BeforeControllerActionSubscriber implements EventSubscriberInterface
{

    /**
     * Subscribe multiple listeners to listen for the BeforeControllerActionEvent. This will fire
     * each time a new controller is called. Listeners can then perform
     * additional tasks on that return object.
     * @return array
     */

    #[ArrayShape([BeforeControllerActionEvent::NAME => "array"])] public static function getSubscribedEvents(): array
    {
        return [
//            BeforeControllerActionEvent::NAME => [
//            ]
        ];
    }


}

