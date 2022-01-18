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

use App\Model\NotificationModel;
use MagmaCore\UserManager\Event\UserActionEvent;
use MagmaCore\UserManager\Model\UserMetaDataModel;
use MagmaCore\UserManager\Model\UserRoleModel;
use MagmaCore\Base\BaseView;
use MagmaCore\Base\Contracts\BaseActionEventInterface;
use MagmaCore\EventDispatcher\EventDispatcherTrait;
use MagmaCore\EventDispatcher\EventSubscriberInterface;
use MagmaCore\Mailer\Exception\MailerException;
use MagmaCore\Mailer\MailerFacade;
use MagmaCore\Utility\Yaml;
use Exception;

/**
 * Note: If we want to flash other routes then they must be declared within the ACTION_ROUTES
 * protected constant
 */
class UserActionSubscriber implements EventSubscriberInterface
{

    use EventDispatcherTrait;

    /** @var int - we want this to execute last so it doesn't interrupt other process */
    private const FLASH_MESSAGE_PRIORITY = -1000;

    private MailerFacade $mailer;
    private BaseView $view;
    private UserRoleModel $userRole;
    private NotificationModel $notify;

    /**
     * Add other route index here in order for that route to flash properly. this array is index array
     * which means the first item starts at 0. See ACTION_ROUTES constant for correct order of how to
     * load other routes for flashing
     * @var int
     */
    protected const NEW_ACTION = 'new';
    protected const EDIT_ACTION = 'edit';
    protected const DELETE_ACTION = 'delete';
    protected const BULK_ACTION = 'bulk';
    protected const TRASH_ACTION = 'trash';
    protected const TRASH_RESTORE_ACTION = 'trash-restore';
    protected const REGISTER_ACTION = 'register';
    protected const LOCK_ACTION = 'lock';
    protected const UNLOCK_ACTION = 'unlock';
    protected const ACTIVE_ACTION = 'active';
    protected const ACTIVATION_PATH = '/activation/activate';


    /**
     * Main constructor class
     *
     * @param MailerFacade $mailer
     * @param BaseView $view
     * @param UserRoleModel $userRole
     * @param NotificationModel $notify
     */
    public function __construct(MailerFacade $mailer, BaseView $view, UserRoleModel $userRole, NotificationModel $notify)
    {
        $this->mailer = $mailer;
        $this->view = $view;
        $this->userRole = $userRole;
        $this->notify = $notify;
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
            UserActionEvent::NAME => [
                ['flashUserEvent', self::FLASH_MESSAGE_PRIORITY],
                ['assignedUserRole'],
                ['sendActivationEmail'],
                ['updateUserRole'],
                ['createUserLog'],
                ['updateStatusIfStatusIsTrash', -900]
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
     * @param UserActionEvent $event
     * @return void
     * @throws Exception
     */
    public function flashUserEvent(UserActionEvent $event)
    {
        $this->flashingEvent($event);
    }

    /**
     * Construct message for user activation
     *
     * @param BaseActionEventInterface $event
     * @param array $user
     * @return string
     */
    private function templateMessage(BaseActionEventInterface $event, array $user): string
    {
        $link = $event->getObject()->getSiteUrl(self::ACTIVATION_PATH . '/' . $user['activation_hash']);
        $html = '<div>';
        $html .= '<h1>' . Yaml::file('app')['activation']['title'] . '</h1>';
        $html .= isset($user['random_pass']) ? '<p><strong>Temporary Password: </strong>' . $user['random_pass'] . '</p>' : '';
        $html .= Yaml::file('app')['activation']['message'];
        $html .= '<a href="' . $link . '">' . Yaml::file('app')['activation']['call_to_action'] . '</a>';
        $html .= '</div>';
        return $html;
    }

    /**
     * Send an activation email to the registered email address each time a user
     * register for a new account.
     *
     * @param UserActionEvent $event
     * @return bool
     * @throws MailerException
     */
    public function sendActivationEmail(UserActionEvent $event): bool
    {
        if ($this->onRoute($event, (string)self::NEW_ACTION) || $this->onRoute($event, self::REGISTER_ACTION)) {
            if ($event) {
                $user = $this->flattenContext($event->getcontext());
                if (is_array($user) && count($user) > 0) {
                    if ($user['status'] === 'pending') {
                        $mail = $this->mailer->basicMail(
                            'New Account',
                            'admin@example.com',
                            $user['email'],
                            $this->templateMessage($event, $user)
                        );
                        if ($mail) {
                            return true;
                        }
                    }
                }
            }
        }
        return false;
    }

    /**
     * Initialize a user log when a brand new user is created. Either from the
     * admin panel or created from the application front end. User log contains
     * meta data ie snail trail of a user activities across the application
     *
     * @param UserActionEvent $event
     * @return bool
     */
    public function createUserLog(UserActionEvent $event): bool
    {
        if ($this->onRoute($event, (string)self::NEW_ACTION) || $this->onRoute($event, self::REGISTER_ACTION)) {
            if ($event) {
                $user = $this->flattenContext($event->getContext());
                if ($user) {
                    $userLog = new UserMetaDataModel();
                    if ($userLog) {
                        $onLogin = ['last_login' => NULL, 'login_from' => NULL];
                        $onLogout = ['last_logout' => NULL, 'logout_from' => NULL];
                        $onBruteForce = ['failed_logins' => NULL, 'failed_login_timestamp' => NULL];
                        $push = $userLog->getRepo()
                            ->getEm()
                            ->getCrud()
                            ->create(
                                [
                                    'user_id' => $user['last_id'],
                                    'user_browser' => serialize(get_browser()),
                                    'login' => serialize($onLogin),
                                    'logout' => serialize($onLogout),
                                    'brute_force' => serialize($onBruteForce)
                                ]
                            );

                        return (bool)$push;
                    }
                }
            }
        }
        return false;
    }

    /**
     * Assign the user the subscriber role on public registration and assigned
     * the selected role from the admin panel
     *
     * @param UserActionEvent $event
     * @return bool
     */
    public function assignedUserRole(UserActionEvent $event): bool
    {
        if ($this->onRoute($event, (string)self::NEW_ACTION)) {
            if ($event) {
                $user = $this->flattenContext($event->getContext());
                if (is_array($user) && count($user) > 0 && !empty($user['role_id'])) {
                    if ($this->userRole) {
                        $push = $this->userRole->getRepo()
                            ->getEm()
                            ->getCrud()
                            ->create(['user_id' => $user['last_id'], 'role_id' => $user['role_id']]);
                        return (bool)$push;
                    }
                }
            }
        }
        return false;
    }

    /**
     * Update the user role.
     * @param UserActionEvent $event
     * @return bool
     */
    public function updateUserRole(UserActionEvent $event): bool
    {
        if ($this->onRoute($event, self::EDIT_ACTION)) {
            if ($event) {
                $user = $this->flattenContext($event->getContext());
                if (array_key_exists('role_id', $user)) {
                    $roleID = $user['role_id'];
                    $update = $this->userRole
                        ->getRepo()
                        ->getEm()
                        ->getCrud()
                        ->update(['role_id' => $roleID, 'user_id' => $event->getObject()->thisRouteID()], 'user_id');
                    return (bool)$update;
                }
            }
        }
        return false;
    }

    /**
     * When a new user account is created and the status is set to trash for whatever reason
     * We are not able to update the 2 relevant fields at time. So When the event is fired
     * we can listen for that and update the record as its created and update the relevant
     * fields to signify that the trash status is indeed for the trash.
     *
     * @param UserActionEvent $event
     * @return bool
     */
    public function updateStatusIfStatusIsTrash(UserActionEvent $event): bool
    {
        /* @todo log this request on success or failure */
        if ($this->onRoute($event, (string)self::NEW_ACTION)) {
            $user = $this->flattenContext($event->getContext());
            if ($user) {
                $status = $user['status']; /* Get the status */
                if ($status == 'trash') { /* If the status is set to trash lets update the database on the last inserted id */
                    $userID = $user['last_id'];
                    if (isset($userID) && $userID !=null) {
                        return $event->getObject()
                            ->repository
                            ->getRepo()
                            ->findByIdAndUpdate(['deleted_at' => 1, 'deleted_at_datetime' => date('Y-m-d H:i:s')], $userID);
                    }
                }
            }
        }
        return false;
    }

    public function assignedRoleToClones()
    {
        // if ($this->onRoute($event, 'bulk-clone')) {
        //     if ($event) {
        //         $user = $this->flattenContext($event->getContext());
        //         if (is_array($user) && count($user) > 0 && !empty($user['role_id'])) {
        //             if ($this->userRole) {
        //                 $push = $this->userRole->getRepo()
        //                     ->getEm()
        //                     ->getCrud()
        //                     ->create(['user_id' => $user['last_id'], 'role_id' => $user['role_id']]);
        //                 return (bool)$push;
        //             }
        //         }
        //     }
        // }
        // return false;

    }

}
