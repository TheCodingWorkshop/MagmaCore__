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

namespace MagmaCore\System\Subscriber;

use JetBrains\PhpStorm\ArrayShape;
use MagmaCore\Base\Events\EventLogger;
use MagmaCore\EventDispatcher\Event;
use MagmaCore\System\App\Model\EventModel;
use MagmaCore\System\Event\SystemActionEvent;
use MagmaCore\EventDispatcher\EventSubscriberInterface;

class SystemActionSubscriber implements EventSubscriberInterface
{

    private EventModel $eventModel;

    public function __construct(EventModel $eventModel)
    {
        $this->eventModel = $eventModel;
    }

    /**
     * Subscribe multiple listeners to listen for the BeforeControllerActionEvent. This will fire
     * each time a new controller is called. Listeners can then perform
     * additional tasks on that return object.
     * @return array
     */

    public static function getSubscribedEvents(): array
    {
        return [
            SystemActionEvent::NAME => [
                ['newControllerMenuAdded']
            ]
        ];
    }

    /**
     * Log an event each time a new controller menu is added to the database
     */
    public function newControllerMenuAdded(SystemActionEvent $event): bool
    {
        $fields = EventLogger::LOG_FIELDS;
        if (is_array($fields)) {
            $combine = array_combine(EventLogger::LOG_FIELDS, $event->getContext());
            if ($combine) {
                $addEvent = $this->eventModel->getRepo()->getEm()->getCrud()->create($combine);
                if (is_bool($addEvent) && $addEvent === true) {
                    return true;
                }
            }
        }
        return false;
    }


}

