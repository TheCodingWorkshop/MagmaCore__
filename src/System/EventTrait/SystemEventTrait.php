<?php

declare(strict_types=1);

namespace MagmaCore\System\EventTrait;

use MagmaCore\Base\Events\EventLogger;
use MagmaCore\System\Event\SystemActionEvent;
use MagmaCore\Utility\ClientIP;

trait SystemEventTrait
{

    /**
     * Helper re-usable method for logging system events
     * @param string $method
     * @param $context
     * @param object $object
     */
    public function logSystemEvent(string $method, $context, object $object)
    {
        if (isset($this->eventDispatcher) && $this->eventDispatcher->hasListeners('magmacore.system.event_system_action_event')) {
            $this->eventDispatcher->dispatch(
                new SystemActionEvent(
                    $method,
                    $context,
                    $object
                ),
                SystemActionEvent::NAME);
        }

    }

}