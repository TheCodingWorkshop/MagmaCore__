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

namespace MagmaCore\EventDispatcher;
/**
 * Defines a dispatcher for events.
 */
interface EventDispatcherInterface
{

    /**
     * Provide all relevant listeners with an event to process.
     *
     * @param object $event - The object to process.
     * @param string $eventName - the name of the event created
     * @return object - The Event that was passed, now modified by listeners.
     */
    public function dispatch(object $event, string $eventName = null) : Object;
    
}
