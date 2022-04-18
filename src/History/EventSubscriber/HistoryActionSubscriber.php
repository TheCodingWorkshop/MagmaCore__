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

namespace MagmaCore\History\EventSubscriber;

use Exception;
use MagmaCore\History\Event\HistoryActionEvent;
use MagmaCore\History\HistoryModel;
use MagmaCore\EventDispatcher\EventDispatcherTrait;
use MagmaCore\EventDispatcher\EventSubscriberInterface;

/**
 * Note: If we want to flash other routes then they must be declared within the ACTION_ROUTES
 * protected constant
 */
class HistoryActionSubscriber implements EventSubscriberInterface
{

    use EventDispatcherTrait;

    /** @var int - we want this to execute last so it doesn't interrupt other process */
    private const FLASH_MESSAGE_PRIORITY = -1000;
    /* @var string */
    private const EDIT_ACTION = 'edit';
    /* @var string */
    protected const INDEX_ACTION = 'index';
    /* @var string */
    protected const NEW_ACTION = 'new';

    private HistoryModel $model;

    /**
     * @param HistoryModel $menuItem
     */
    public function __construct(HistoryModel $model)
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
            HistoryActionEvent::NAME => [
                ['saveHistory']
            ]
        ];
    }

    /**
     * @param MenuActionEvent $event
     * @return bool
     */
    public function saveHistory(HistoryActionEvent $event): bool
    {
        // if ($this->onRoute($event, self::EDIT_ACTION)) {
        //     $context = $this->flattenContext(array_column($event->getContext(), 'item_usable'));
        //     if (isset($context) && count($context) > 0) {
        //         foreach ($context as $itemID) {
        //             $itemID = (int)$itemID;
        //             $this->menuItem->getRepo()->findByIdAndUpdate(['item_usable' => 1], $itemID);
        //         }
        //         return true;
        //     }
        // }
        return false;
    }


}
