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

namespace MagmaCore\Notification\EventSubscriber;

use Exception;
use MagmaCore\Notification\Event\NotificationActionEvent;
use MagmaCore\EventDispatcher\EventDispatcherTrait;
use MagmaCore\EventDispatcher\EventSubscriberInterface;
use MagmaCore\Notification\NotificationModel;

/**
 * Note: If we want to flash other routes then they must be declared within the ACTION_ROUTES
 * protected constant
 */
class NotificationActionSubscriber implements EventSubscriberInterface
{

    use EventDispatcherTrait;

    /** @var int - we want this to execute last so it doesn't interrupt other process */
    private const FLASH_MESSAGE_PRIORITY = -1000;
    /* @var string */
    private const EDIT_ACTION = 'edit';
    /* @var string */
    protected const INDEX_ACTION = 'index';
    /* @var string */
    protected const SHOW_ACTION = 'show';

    private NotificationModel $model;

    public function __construct(NotificationModel $model)
    {
        $this->model = $model;
    }


    /**
     * Subscribe multiple listeners to listen for the NewActionEvent. This will fire
     * each time a new user is added to the database. Listeners can then perform
     * additional tasks on that return object.
     *
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return [
            NotificationActionEvent::NAME => [
                ['flashNotificationEvent', self::FLASH_MESSAGE_PRIORITY],
                ['hasRead']
            ]
        ];
    }

    /**
     * Event flash allows flashing of any specified route defined with the ACTION_ROUTES constants
     * one can declare a message and a default route. if a default route isn't set then the script will
     * redirect back on it self using the onSelf() method. Delete route is automatically filtered to
     * redirect back to the index page. As this is the only logical route to redirect to. after we
     * remove the object. failure to comply with this will result in 404 error as the script will
     * try to redirect to an object that no longer exists.
     *
     * @param NotificationActionEvent $event
     * @return void
     * @throws Exception
     */
    public function flashNotificationEvent(NotificationActionEvent $event)
    {
        $this->flashingEvent($event,);
    }

    /**
     * When a notification is click and redirect to the show route. we want to mark to the
     * notification as read within the database.
     *
     * @param NotificationActionEvent $event
     */
    public function hasRead(NotificationActionEvent $event): bool
    {
        if ($this->onRoute($event, self::SHOW_ACTION)) {
            $notifyID = (int)$event->getObject()->thisRouteID();
            $notification = $this->model->getRepo()->findObjectBy(['id' => $notifyID]);
            if ($notification !==null) {
                $update = $this->model
                    ->getRepo()
                    ->findByIdAndUpdate(
                        [
                            'notify_status' => 'read',
                            'id' => $notifyID
                        ],
                        $notifyID
                    );
                if ($update)
                    return true;
            }
        }

        return false;
    }


}

