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

use MagmaCore\UserManager\Event\UserRoleActionEvent;
use MagmaCore\UserManager\Model\UserRoleModel;
use MagmaCore\UserManager\Rbac\Model\TemporaryRoleModel;
use JetBrains\PhpStorm\ArrayShape;
use MagmaCore\EventDispatcher\EventDispatcherTrait;
use MagmaCore\EventDispatcher\EventSubscriberInterface;
use Exception;

/**
 * Note: If we want to flash other routes then they must be declared within the ACTION_ROUTES
 * protected constant
 */
class UserRoleActionSubscriber implements EventSubscriberInterface
{

    use EventDispatcherTrait;

    /** @var int - we want this to execute last so it doesn't interrupt other process */
    private const FLASH_MESSAGE_PRIORITY = -1000;

    private UserRoleModel $userRole;
    private TemporaryRoleModel $tempRoleModel;

    /**
     * Main constructor class
     *
     * @param UserRoleModel $userRole
     * @param TemporaryRoleModel $tempRoleModel
     */
    public function __construct(UserRoleModel $userRole, TemporaryRoleModel $tempRoleModel)
    {
        $this->userRole = $userRole;
        $this->tempRoleModel = $tempRoleModel;
    }

    /**
     * Subscribe multiple listeners to listen for the NewActionEvent. This will fire
     * each time a new user is added to the database. Listeners can then perform
     * additional tasks on that return object.
     * @return array
     */

    #[ArrayShape([UserRoleActionEvent::NAME => "array"])] public static function getSubscribedEvents(): array
    {
        return [
            UserRoleActionEvent::NAME => [
                ['flashUserEvent', self::FLASH_MESSAGE_PRIORITY],
                ['createTemporaryRole'],
                ['addExpirationTemporaryRole']
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
     * @param UserRoleActionEvent $event
     * @return void
     * @throws Exception
     */
    public function flashUserEvent(UserRoleActionEvent $event)
    {
        if ($this->onRoute($event, 'privilege')) {
            $event->getObject()->flashMessage('Role updated successfully');
            $event->getObject()->redirect('/admin/user/' . $event->getObject()->thisRouteID() . '/privilege');
        }
    }

    /**
     * @param UserRoleActionEvent $event
     * @return bool
     */
    public function createTemporaryRole(UserRoleActionEvent $event): bool
    {
        if ($this->onRoute($event, 'privilege')) {
            $data = $this->flattenContext($event->getContext());
            if (is_array($data) && count($data) > 0) {
                $fields = [
                    'user_id' => $data['user_id'],
                    'prev_role_id' => $data['prev_role_id'],
                    'current_role_id' => intval($data['role_id']),
                ];
                return $this->tempRoleModel->getRepo()
                    ->getEm()
                    ->getCrud()
                    ->create($fields);
            }

        }
        return false;
    }

    /**
     * @param UserRoleActionEvent $event
     * @return bool
     */
    public function addExpirationTemporaryRole(UserRoleActionEvent $event): bool
    {
        if ($this->onRoute($event, 'privilege-expiration')) {
            $data = $this->flattenContext($event->getContext());
            if ($data['user_id'] === $event->getObject()->thisRouteID()) {
                $duration = $data['duration'];
                $time = $data['time'];
                $expiration = $duration . ' ' . $time . ':00';
                $fields = [
                    'duration' => $expiration,
                    'user_id' => $data['user_id']
                ];
                return $this->tempRoleModel->getRepo()
                    ->getEm()
                    ->getCrud()
                    ->update($fields, 'user_id');
            }

        }
        return false;

    }

}

