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

namespace MagmaCore\EventDispatcher;

use Closure;
use Exception;
use MagmaCore\Base\BaseActionEvent;
use MagmaCore\Notification\NotificationModel;
use MagmaCore\UserManager\Event\UserActionEvent;
use MagmaCore\Utility\Yaml;
use MagmaCore\DataObjectLayer\DataLayerTrait;

trait EventDispatcherExtendedTrait
{

    /**
     * Returns an array of register_route_feedbacks from the events.yml file. This will
     * help construct the redirect and flash messages for each routes defined.
     *
     * @param Object $event
     * @return array
     * @throws Exception
     */
    public function trailingExtendedRoutes(Object $event) : array
    {

        $feedback = Yaml::file('extend_events')
        ['services']
        ['subscribers']
        [strtolower($event->getObject()->thisRouteController() . '.subscriber')]
        ['register_route_feedback'];
        if (isset($feedback))
            return $feedback;
    }

    /**
     * Execute only if we are not using a callback function. The onRoute
     * method provides the means of redirecting back to a referral page ie.
     * the page the request was made from. It will attempt to redirect back there
     *
     * only because we can't redirect on a object that might have been deleted. ie
     * if we delete a user on like an edit page we don't want to redirect back onSelf
     * cause the object would have been deleted. so instead we will redirect back
     * based on the session which was set on the indexAction of each controller
     *
     * if we however choose to omit this then the script will look for a redirect
     * key within ACTION_ROUTES array and redirect there else will just redirect
     * back on self if the redirect key wasn't set.
     *
     * If we are using a route which is deleting the object then we MUST set the
     * $actionRoute from the EventSubscriber class
     *
     * @param Object $event
     * @param Closure|null $cb
     * @return void
     */
    public function flashingExtendedEvent(
        Object $event,
        Closure $cb = null
    ) {

        if (!empty($event->getMethod())) {
            $routesArray = $this->trailingExtendedRoutes($event);
            if (in_array($event->getMethod(), array_keys($routesArray), true)) {
                $_msg = array_key_exists('msg', $routesArray[$event->getMethod()]);
                $event->getObject()->flashMessage(($_msg === true) ? '<ion-icon name="checkmark-outline"></ion-icon> ' . $routesArray[$event->getMethod()]['msg'] : ''); /* render a default message */
                if (null !== $cb) {
                    call_user_func_array($cb, [$event, $routesArray]);
                } else {
                    $event->getObject()->redirect($this->autoRedirectExtended($event));
                }
            }
        }
    }

    private function autoRedirectExtended($event): string
    {
        $redirect = '';
        $routesArray = $this->trailingExtendedRoutes($event);
        $optionalRedirect = array_key_exists('redirect', $routesArray[$event->getMethod()]);
        if ($this->onRoute($event, null)) {
            $redirect = '/' . $event->getObject()->getSession()->get('redirect_parameters');
        } elseif ($optionalRedirect == true){
            $redirect = $this->resolveRedirect($routesArray[$event->getMethod()]['redirect'], $event);
        } else {
            $redirect = $event->getObject()->onSelf();
        }
        return $redirect;
    }


}


