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

namespace MagmaCore\UserManager\Security\EventSubscriber;

use Exception;
use function date;
use function array_map;
use function serialize;
use function array_reduce;
use MagmaCore\Auth\Authorized;
use JetBrains\PhpStorm\ArrayShape;
use MagmaCore\UserManager\Model\UserMetaDataModel;
use MagmaCore\EventDispatcher\EventDispatcherTrait;
use MagmaCore\EventDispatcher\EventSubscriberInterface;
use MagmaCore\Administrator\Model\ControllerSessionBackupModel;
use MagmaCore\PanelMenu\MenuModel;
use MagmaCore\UserManager\Security\Event\LogoutActionEvent;

/**
 * Note: If we want to flash other routes then they must be declared within the ACTION_ROUTES
 * protected constant
 */
class LogoutActionSubscriber implements EventSubscriberInterface
{

    use EventDispatcherTrait;

    /** @var int - we want this to execute last so it doesn't interrupt other process */
    private const FLASH_MESSAGE_PRIORITY = -1000;
    private const LOGOUT_ACTION = 'logout';

    private ControllerSessionBackupModel $sessionBackup;
    private MenuModel $menuModel;

    public function __construct(ControllerSessionBackupModel $sessionBackup, MenuModel $menuModel)
    {
        $this->sessionBackup = $sessionBackup;
        $this->menuModel = $menuModel;
    }

    /**
     * Subscribe multiple listeners to listen for the NewActionEvent. This will fire
     * each time a new user is added to the database. Listeners can then perform
     * additional tasks on that return object.
     *
     * @return array
     */
    #[ArrayShape([LogoutActionEvent::NAME => "array[]"])] public static function getSubscribedEvents(): array
    {
        return [
            LogoutActionEvent::NAME => [
                ['flashLogoutEvent', self::FLASH_MESSAGE_PRIORITY],
                //['logControllerSession', -999],
                ['afterLogout']
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
     * @param LogoutActionEvent $event
     * @return void
     * @throws Exception
     */
    public function flashLogoutEvent(LogoutActionEvent $event)
    {
        $this->flashingEvent(
            $event,
            /**
             * As we are dealing with modal for adding and editing roles we want to redirect
             * back to the role index page.
             */
            function ($cbEvent, $actionRoutes) {
                $cbEvent->getObject()->redirect(Authorized::getReturnToPage());
            }
        );
    }

    /**
     * Log a user after they've successfully logged in. We are also logging failed
     * login attempts with timestamps
     *
     * @param LogoutActionEvent $event
     * @return void
     */
    public function afterLogout(LogoutActionEvent $event)
    {
        if ($this->onRoute($event, self::LOGOUT_ACTION)) {
            if ($event) {
                $user = $event->getContext();
                if ($user) {
                    $value = array_unique(
                        array_reduce(array_map('array_values', $user), 'array_merge', [])
                    );
                    $logLogout = ['last_logout' => date('Y-m-d H:i:s'), 'logout_from' => $_SERVER['HTTP_REFERER']];
                    $userLog = new UserMetaDataModel();
                    $userLog->getRepo()
                        ->getEm()
                        ->getCrud()
                        ->update(
                            [
                                'user_id' => ($value[0] ?? false),
                                'logout' => serialize($logLogout)
                            ],
                            'user_id'
                        );
                }
            }
        }
    }

    public function logControllerSession(LogoutActionEvent $event)
    {
        /* first we need to somehow get all the controller within the system. We should 
        be able to retrieve this from the menus database table */
        $controllers = array_column($this->menuModel->getRepo()->findBy(), 'menu_name');
        /* append the _settings suffix to the controller name */
        $sessionKey = array_map(fn($controller) => $controller . '_settings', $controllers );

        /* Now we have the keys we will loop through and get the relevant session data belonging
        to the key */

        if (is_array($sessionKey) && count($sessionKey) > 0) {
            /* This is safest way to retrive the index position of the $sessionKey array */
            $indexPosition = array_search('dashboard_settings', $sessionKey);
            /* Now we have the exact position we can unset it from the array */
            unset($sessionKey[$indexPosition]);

            $sessionObject = $event->getObject()->getSession();
            array_map(function($key) use($sessionObject) {
                if (!$sessionObject->has($key)) {
                    throw new Exception();
                }
                $sessionData = $sessionObject->get($key);
                if ($sessionData) {
                    $this->sessionBackup
                        ->getRepo()
                        ->getEm()
                        ->getCrud()
                        ->create(
                            [
                                'controller' => $key,
                                'context' => serialize($sessionData)
                            ]
                        );

                }
                
            }, $sessionKey);


        }
        return true;

    }



}
