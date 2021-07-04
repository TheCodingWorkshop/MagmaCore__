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
use MagmaCore\Utility\Yaml;
use MagmaCore\DataObjectLayer\DataLayerTrait;

trait EventDispatcherTrait
{

    use DataLayerTrait;

    /**
     * Helper method which allows filtering of the various routes. This enables us to
     * execute the method only on the routes we need to execute the method on.
     *
     * @param Object $event
     * @param string|null $route
     * @return boolean
     */
    public function onRoute(Object $event, string|null $route = null): bool
    {
        return $event->getObject()->thisRouteAction() === $route;
    }

    /**
     * Undocumented function
     *
     * @param Object $event
     * @return array
     * @throws Exception
     */
    public function trailingRoutes(Object $event) : array
    {
        return Yaml::file('events')
            ['services']
                ['subscribers']
                    [strtolower($event->getObject()->thisRouteController() . '.subscriber')]  
                        ['register_route_feedback'];
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
     * @param array $routesArray
     * @param string|null $defaultMessage
     * @param string|null $actionRoute
     * @param Closure|null $cb
     * @return void
     */
    public function flashingEvent(
        Object $event,
        array $routesArray = [],
        ?string $defaultMessage = null,
        ?string $actionRoute = null,
        Closure $cb = null
    ) {
        if (!empty($event->getMethod())) {
            if (in_array($event->getMethod(), array_keys($routesArray), true)) {
                $_msg = array_key_exists('msg', $routesArray[$event->getMethod()]);
                $event->getObject()->flashMessage(($_msg === true) ? $routesArray[$event->getMethod()]['msg'] : $defaultMessage);
                if (null !== $cb) {
                    call_user_func_array($cb, [$event, $routesArray]);
                } else {
                    $_redirect = array_key_exists('redirect', $routesArray[$event->getMethod()]);
                    $event->getObject()
                        ->redirect(
                            ($this->onRoute($event, $actionRoute) ?
                                '/' . $event->getObject()->getSession()->get('redirect_parameters') : (($_redirect === true) ? $routesArray[$event->getMethod()]['redirect'] : (($actionRoute !==null) ? $actionRoute : $event->getObject()->onSelf())))
                        );
                }
            }
        }
    }
}
