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

namespace MagmaCore\Base\EventSubscriber;

use MagmaCore\Base\Events\BeforeControllerActionEvent;
use MagmaCore\EventDispatcher\EventDispatcherTrait;
use MagmaCore\EventDispatcher\EventSubscriberInterface;

class BeforeControllerActionSubscriber implements EventSubscriberInterface
{

    use EventDispatcherTrait;

    protected const PREVENTION_ROUTES = ['trash', 'delete', 'hardDelete', 'lock', 'clone'];

    /**
     * Subscribe multiple listeners to listen for the BeforeControllerActionEvent. This will fire
     * each time a new controller is called. Listeners can then perform
     * additional tasks on that return object.
     * @return array
     */

    public static function getSubscribedEvents(): array
    {
        return [
           BeforeControllerActionEvent::NAME => [
               ['disableDeleteCurrentUser'],
               ['disableDeleteImportantRolesAndPermissions'],
               //['DisableModificationOfUnownContent']
           ]
        ];
    }


    /**
     * This will prevent the active user  within the session from deleting and trashing their own account.
     * 
     * if the token route is active and it matches the userID from the current session and any 
     * of 4 defined action is being carried out. Then we want to prevent. The action from completing
     * We must also take into account when deleting via bulk. As this will allow the user to be trash
     *
     * @return void
     */
    public function disableDeleteCurrentUser(BeforeControllerActionEvent $event): void
    {
        $userID = $event->getObject()->getSession()->get('user_id');
        $token = $event->getObject()->thisRouteID();
        $action = $event->getMethod();
        $controller = $event->getObject()->thisRouteController();
        $postData = $event->getObject()->formBuilder->getData();


        if ($this->isBulk($controller, $postData)) {
            /* return error page here if bulk is selected without IDs */
            if (array_key_exists('id', $postData)) {
                if (in_array($userID, $postData['id'])) {
                    $this->flash($event, 'The action was rejected. Because an item in your bulk selection is yourself', sprintf('/admin/%s/index', $controller));
                }    
            } else {
                $this->flash($event, 'Looks like you\'ve not selected any bulk items.', sprintf('/admin/%s/index', $controller));
            }
        } else if (isset($token) && $token === $userID && in_array($action, self::PREVENTION_ROUTES)) {
            $this->flash($event, 'The action was rejected. The action is not permitted on yourself', sprintf('/admin/%s/index', $controller));
        }
                
    }

    /**
     * This will prevent a the deletion of the two important roles and permission within the system {superadmin and subscriber roles}
     * {basic_access, have_admin_access}
     * these are the default roles within the system and is required at all time.
     *
     * @param BeforeControllerActionEvent $event
     * @return void
     */
    public function disableDeleteImportantRolesAndPermissions(BeforeControllerActionEvent $event): void
    {
        if ($event->getObject()->thisRouteController() === 'role' || $event->getObject()->thisRouteController() === 'permission') {
            $postData = $event->getObject()->formBuilder->getData();
            $roles = $event->getObject()->repository->guardedID();
            $token = $event->getObject()->thisRouteID();

            for ($i=0; $i < count($roles); $i++) {
                if ($this->isBulk($controller = $event->getObject()->thisRouteController(), $postData)) {
                    if (in_array($roles[$i], $postData['id'])) {
                        $this->flash($event, 'This action was rejected. One or more of your bulk selection is guarded. Meaning the action is not allowed.', sprintf('/admin/%s/index', $controller));
                    }
                } 
                elseif (isset($token) && $token === $roles[$i] && in_array($event->getMethod(), self::PREVENTION_ROUTES)) {
                    $this->flash($event, 'This action was rejected. This role is guarded. Meaning the action is not allowed.', sprintf('/admin/%s/index', $controller));
                }
            }
        }
    }

    /**
     * Prevent a user from editing another high level user data. If not admin who ultimately have full controller over all other user
     * and their content
     * We will need to check created_byid and prevent editing other content which wasn't created by created_byid or skip if current
     * session is superadmin
     */
    public function DisableModificationOfUnownContent(BeforeControllerActionEvent $event): void
    {

    }

}

