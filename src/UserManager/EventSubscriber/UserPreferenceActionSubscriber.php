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

namespace MagmaCore\UserManager\EventSubscriber;

use MagmaCore\UserManager\Event\UserPreferenceActionEvent;
use MagmaCore\UserManager\Model\UserPreferenceModel;
use MagmaCore\EventDispatcher\EventDispatcherTrait;
use MagmaCore\EventDispatcher\EventSubscriberInterface;
use Exception;

/**
 * Note: If we want to flash other routes then they must be declared within the ACTION_ROUTES
 * protected constant
 */
class UserPreferenceActionSubscriber implements EventSubscriberInterface
{

    use EventDispatcherTrait;

    /** @var int - we want this to execute last so it doesn't interrupt other process */
    private const FLASH_MESSAGE_PRIORITY = -1000;
    private const PREFERENCE = 'preference';

    private UserPreferenceModel $userPreferenceModel;

    /**
     * Main constructor class
     *
     * @param UserRoleModel $userRole
     */
    public function __construct(UserPreferenceModel $userPreferenceModel)
    {
        $this->userPreferenceModel = $userPreferenceModel;
    }

    /**
     * Subscribe multiple listeners to listen for the NewActionEvent. This will fire
     * each time a new user is added to the database. Listeners can then perform
     * additional tasks on that return object.
     * @return array
     */

    public static function getSubscribedEvents(): array
    {
        return [
            UserPreferenceActionEvent::NAME => [
                ['UpdateUserPreference'],
                ['flashPreferenceEvent', self::FLASH_MESSAGE_PRIORITY],
            ]
        ];
    }

    /**
     * Event flash allows flashing of any specified route defined with the ACTION_ROUTES constants
     * one can declare a message and a default route. if a default route isn't
     * set then the script will
     *
     * redirect back on it self using the onSelf() method. Delete route is automatically filtered to
     * redirect back to the index page. As this is the only logical route to redirect to. after we
     * remove the object. failure to comply with this will result in 404 error as the script will
     * try to redirect to an object that no longer exists.
     *
     * @param UserPreferenceActionEvent $event
     * @return void
     * @throws Exception
     */
    public function flashPreferenceEvent(UserPreferenceActionEvent $event)
    {
        if ($this->onRoute($event, self::PREFERENCE)) {
            $event->getObject()->flashMessage('Preference updated successfully');
            $event->getObject()->redirect('/admin/user/' . $event->getObject()->thisRouteID() . '/preference');
        }
    }

    /**
     * @param UserPreferenceActionEvent $event
     * @return bool
     */
    public function UpdateUserPreference(UserPreferenceActionEvent $event): bool
    {
        var_dump($event->getContext());
        die;
//        if ($this->onRoute($event, SELF::PRIVILEGE)) {
//            if ($event) {
//                $user = $this->flattenContext($event->getContext());
//                if (array_key_exists('role_id', $user)) {
//                    $findExisting = $this->userRole->getRepo()->findAll();
//                    if (count($findExisting) > 0) {
//                        /* Delete the eixtsing role before adding the new one */
//                        $truncate = $this->userRole->getRepo()->getEm()->getCrud()->delete(['user_id' => $user['user_id']]);
//                        if ($truncate) {
//                            return $this->addRole($user);
//                        }
//
//                    }
//                    /* update role is no role exists for this user. This will only execute if the above count() is less than 0 */
//                    return $this->addRole($user);
//
//                }
//            }
//        }
//        return false;
    }



}

