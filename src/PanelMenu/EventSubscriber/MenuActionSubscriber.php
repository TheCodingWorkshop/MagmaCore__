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

namespace MagmaCore\PanelMenu\EventSubscriber;

use Exception;
use MagmaCore\PanelMenu\Event\MenuActionEvent;
use MagmaCore\PanelMenu\MenuItems\MenuItemModel;
use MagmaCore\EventDispatcher\EventDispatcherTrait;
use MagmaCore\EventDispatcher\EventSubscriberInterface;

/**
 * Note: If we want to flash other routes then they must be declared within the ACTION_ROUTES
 * protected constant
 */
class MenuActionSubscriber implements EventSubscriberInterface
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

    private MenuItemModel $menuItem;

    /**
     * @param MenuItemModel $menuItem
     */
    public function __construct(MenuItemModel $menuItem)
    {
        $this->menuItem = $menuItem;
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
            MenuActionEvent::NAME => [
                ['flashLoginEvent', self::FLASH_MESSAGE_PRIORITY],
                ['addMenuItem']
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
     * @param MenuActionEvent $event
     * @return void
     * @throws Exception
     */
    public function flashLoginEvent(MenuActionEvent $event)
    {
        $this->flashingEvent($event,);
    }

    /**
     * @param MenuActionEvent $event
     * @return bool
     */
    public function addMenuItem(MenuActionEvent $event): bool
    {
        if ($this->onRoute($event, self::EDIT_ACTION)) {
            $context = $this->flattenContext(array_column($event->getContext(), 'item_usable'));
            if (isset($context) && count($context) > 0) {
                foreach ($context as $itemID) {
                    $itemID = (int)$itemID;
                    $this->menuItem->getRepo()->findByIdAndUpdate(['item_usable' => 1], $itemID);
                }
                return true;
            }
        }
        return false;
    }


}
