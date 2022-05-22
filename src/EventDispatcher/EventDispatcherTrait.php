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
use MagmaCore\Notification\NotificationModel;
use MagmaCore\Utility\Yaml;
use MagmaCore\DataObjectLayer\DataLayerTrait;

trait EventDispatcherTrait
{

    use DataLayerTrait;
    use EventDispatcherExtendedTrait;

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
                $event->getObject()->flashMessage(($_msg === true) ? '<ion-icon name="checkmark-outline"></ion-icon> ' . $routesArray[$event->getMethod()]['msg'] : ''); /* render a default message */
                if (null !== $cb) {
                    call_user_func_array($cb, [$event, $routesArray]);
                } else {
                    $event->getObject()->redirect($this->autoRedirect($event));
                }
            }
        }
    }


    /**
     * Determine the redirect path based on the properties set within the event configuration
     * 
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
            $redirect = $_SERVER['HTTP_REFERER'];
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

    /**
     * @param string $key
     * @param mixed $cleanData
     * @param mixed|null $dataRepository
     * @return mixed
     */
    public function isSet(string $key, mixed $cleanData, mixed $dataRepository = null): mixed
    {
        if (is_object($cleanData)) {
            return $cleanData->$key ?? (($dataRepository !== null) ? $dataRepository->$key : null);
        } elseif (is_array($cleanData)) {
            return array_key_exists($key, $cleanData) ? $cleanData[$key] : (($dataRepository !== null) ? $dataRepository->$key : null);
        } else {
            return $cleanData[$key];
        }
    }

    /**
     * @param array $context
     * @return array|string
     */
    public function flattenContext(array $context): array|string
    {
        if (is_array($context)) {
            foreach ($context as $con) {
                return $con;
            }
        }
    }

    /**
     * @param object|null $event
     * @param string|null $eventName
     * @param Closure|null $callback
     * @throws Exception
     */
    public function notify(object $event = null, ?string $eventName = null, Closure $callback = null)
    {
        if (!$callback instanceof Closure) {
            throw new Exception(sprintf('%s is not an instance of Closure', $callback));
        }
        $controller = $event->getObject();
        if ($controller->eventDispatcher->hasListeners($eventName) === true) {
            $data = $callback($event, $this);
            if (is_array($data) && count($data) > 0) {
                $notify = (new NotificationModel())->getRepo();
                $notify->getEm()
                    ->getCrud()
                    ->create($data);
            }
        }
    }

    /**
     * @param array $context
     * @param string|null $method
     * @param object|null $object
     * @return array|false
     */
    public function resolveContextForDescription(object $event = null, ?array $context = null, string $method = null, object|array $author = null)
    {
        if ($method) {
            $firstPiece = explode('\\', $method);
            if (isset($firstPiece[2])) {
                $secondPiece = explode('::', $firstPiece[2]);
                if (isset($secondPiece[0]) && isset($secondPiece[1])) {
                    $controller = $secondPiece[0];
                    $controllerMethod = $secondPiece[1];
                    $desc = sprintf(
                        'A request was made from %s on the %s method %sby %s @ %s. %sThe request was successful.' . PHP_EOL . '%s',
                        $controller,
                        $controllerMethod,
                        PHP_EOL,
                        $author->firstname . ' ' . $author->lastname,
                        date('Y-m-d : H:i:s'),
                        PHP_EOL,
                        json_encode($context)
                    );

                    return [
                        $desc,
                        'Route request made on ' . $controllerMethod
                    ];
                }
            }
        }

        return false;
    }

    /**
     * Helper method use for unsetting data from the input array ie first arguments
     *
     * @param array $array
     * @param array $optionalData
     * @return array
     */
    public function unsetter(array $array = [], array $optionalData = []): array
    {
        return array_map(function($key) use ($optionalData) {
            unset($optionalData[$key]);
        }, $array);

    }
    
    /**
     * isBulk action selected returns true or false
     *
     * @param string|null $controller
     * @param array|null $postData
     * @return boolean
     */
    private function isBulk(string $controller = null, array $postData = null): bool
    {
        if (array_key_exists('bulkTrash-' . $controller, $postData) || array_key_exists('bulkClone-' . $controller, $postData)) {
            return true;
        }
        return false;

    }

    /**
     * Helper method for creating a flash message with redirect
     *
     * @param object|null $event
     * @param string|null $message
     * @param string|null $redirect
     * @return void
     */
    private function flash(object $event = null, ?string $message = null, ?string $redirect = null)
    {
        $event->getObject()->flashMessage(sprintf('%s', $message), $event->getObject()->flashWarning());
        $event->getObject()->redirect($redirect);

    }

}


