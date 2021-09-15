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
    public function onRoute(Object $event, ?string $route = null): bool
    {
        return $event->getObject()->thisRouteAction() === $route;
    }

    /**
     * Returns an array of register_route_feedbacks from the events.yml file. This will
     * help construct the redirect and flash messages for each routes defined.
     *
     * @param Object $event
     * @return array
     * @throws Exception
     */
    public function trailingRoutes(Object $event) : array
    {

        // $feedbacks = Yaml::file('events');
        // foreach ($feedbacks as $key => $value) {
        //     if (isset($key) && $key === 'services') {
        //         foreach ($value['subscribers'] as $param => $options) {
        //             if (isset($param)) {
        //                 $parts = explode('.', $param);
        //                 if (isset($parts[0])) {
        //                     //if ($parts[0] === $event->getObject()->thisRouteController()) {
        //                         var_dump($param);
        //                         die;
        //                    // }
        //                 }
        //             }
        //         }
        //     }
        // }
        $feedback = Yaml::file('events')
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
    public function flashingEvent(
        Object $event,
        Closure $cb = null
    ) {

        if (!empty($event->getMethod())) {
            $routesArray = $this->trailingRoutes($event);
            if (in_array($event->getMethod(), array_keys($routesArray), true)) {
                $_msg = array_key_exists('msg', $routesArray[$event->getMethod()]);
                $event->getObject()->flashMessage(($_msg === true) ? $routesArray[$event->getMethod()]['msg'] : $defaultMessage);
                if (null !== $cb) {
                    call_user_func_array($cb, [$event, $routesArray]);
                } else {
                    $event->getObject()->redirect($this->autoRedirect($event));
                }
            }
        }
    }

    /**
     * @param $event
     * @return mixed|string|null
     * @throws Exception
     */
    private function autoRedirect($event): string
    {
        $redirect = '';
        $routesArray = $this->trailingRoutes($event);
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

    /**
     * Resolve the syntax define for the redirect parameter within the events.yml file
     * ie. {user.index}
     *
     * @param string $redirectString
     * @param object $event
     * @return string|null
     */
    private function resolveRedirect(string $redirectString, object $event): ?string
    {
        if (is_string($redirectString) && str_contains($redirectString, '.')) {
            $element = explode('.', $redirectString);
            if (is_array($element) && count($element) > 0) {
                $controller = $element[0] ?? null;
                $action = $element[1] ?? null;
                if ($event->getObject()->thisRouteController() === $controller) {
                    $namespace = $event->getObject()->thisRouteNamespace() ?? null;
                    $redirect = "/{$namespace}/{$controller}/{$action}";
                    if ($redirect) {
                        return $redirect;
                    } else {
                        return null;
                    }
                }
            }
        }
        return null;
    }

    public function flattenContext(array $context): array
    {
        if (is_array($context)) {
            foreach ($context as $con) {
                return $con;
            }
        }
    }
}
