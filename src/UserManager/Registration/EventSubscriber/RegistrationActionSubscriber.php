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

namespace MagmaCore\UserManager\Registration\EventSubscriber;

use MagmaCore\UserManager\Registration\Event\RegistrationActionEvent;
use MagmaCore\UserManager\Model\UserMetaDataModel;
use MagmaCore\UserManager\Model\UserRoleModel;
use Exception;
use JetBrains\PhpStorm\ArrayShape;
use MagmaCore\Base\BaseView;
use MagmaCore\Base\Contracts\BaseActionEventInterface;
use MagmaCore\EventDispatcher\EventDispatcherTrait;
use MagmaCore\EventDispatcher\EventSubscriberInterface;
use MagmaCore\Mailer\Exception\MailerException;
use MagmaCore\Mailer\MailerFacade;
use MagmaCore\Utility\Yaml;

/**
 * Note: If we want to flash other routes then they must be declared within the ACTION_ROUTES
 * protected constant
 */
class RegistrationActionSubscriber implements EventSubscriberInterface
{

    use EventDispatcherTrait;
    private MailerFacade $mailer;
    private BaseView $view;


    /** @var int - we want this to execute last so it doesn't interrupt other process */
    private const FLASH_MESSAGE_PRIORITY = -1000;

    /**
     * Add other route index here in order for that route to flash properly. this array is index array
     * which means the first item starts at 0. See ACTION_ROUTES constant for correct order of how to
     * load other routes for flashing
     * @var int
     */
    protected const INDEX_ACTION = 'registered';
    protected const REDIRECT_PATH = '/registration/registered';
    protected const ACTIVATION_PATH = '/activation/activate';

    private UserRoleModel $userRole;
    /**
     * Main constructor class
     *
     * @param MailerFacade $mailer
     * @param BaseView $view
     * @return void
     */
    public function __construct(MailerFacade $mailer, BaseView $view, UserRoleModel $userRole)
    {
        $this->mailer = $mailer;
        $this->view = $view;
        $this->userRole = $userRole;
    }

    /**
     * Subscribe multiple listeners to listen for the NewActionEvent. This will fire
     * each time a new user is added to the database. Listeners can then perform
     * additional tasks on that return object.
     *
     * @return array
     */
    #[ArrayShape([RegistrationActionEvent::NAME => "array"])] public static function getSubscribedEvents(): array
    {
        return [
            RegistrationActionEvent::NAME => [
                ['flashRegisterEvent', self::FLASH_MESSAGE_PRIORITY],
                ['assignedRegisteredUsersAsSubscriber'],
                ['sendRegistrationActivationEmail'],
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
     * @param RegistrationActionEvent $event
     * @return void
     * @throws Exception
     */
    public function flashRegisterEvent(RegistrationActionEvent $event)
    {
        $this->flashingEvent($event,
            function($cbEvent, $routeArray) {
                $cbEvent->getObject()->redirect(self::REDIRECT_PATH);
            }
        );
    }

    /**
     * @param RegistrationActionEvent $event
     * @param array $user
     * @return string
     */
    private function templateMessage(RegistrationActionEvent $event, array $user): string
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
     * @param RegistrationActionEvent $event
     * @return bool
     * @throws MailerException
     */
    public function sendRegistrationActivationEmail(RegistrationActionEvent $event)
    {
        if ($this->onRoute($event, 'register')) {
            if ($event) {
                $user = $this->flattenContext($event->getcontext());
                if (is_array($user) && count($user) > 0) {
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
        return false;
    }

    /**
     * Adds the basic user access for frontend registered user.
     *
     * @param RegistrationActionEvent $event
     * @return void
     */
    public function assignedRegisteredUsersAsSubscriber(RegistrationActionEvent $event)
    {
        if ($this->onRoute($event, 'register')) {
            if ($event) {
                $user = $this->flattenContext($event->getContext());
                if (is_array($user) && count($user) > 0) {
                    $subRole = Yaml::file('app')['system']['default_role']['props']['id'];
                    $push = $this->userRole->getRepo()
                        ->getEm()
                        ->getCrud()
                        ->create(['user_id' => $user['last_id'], 'role_id' => $subRole]);
                    return $push;
                }
            }
        }
    }


}
