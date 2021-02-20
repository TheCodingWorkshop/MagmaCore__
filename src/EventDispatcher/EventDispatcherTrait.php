<?php

declare(strict_types=1);

namespace MagmaCore\EventDispatcher;

use Closure;

trait EventDispatcherTrait
{

    /**
     * Helper method which allows filtering of the various routes. This enables us to 
     * execute the method only on the routes we need to execute the method on.
     *
     * @param Object $event
     * @param string $route
     * @return boolean
     */
    public function onRoute(Object $event, string $route): bool
    {
        return ($event->getObject()->thisRouteAction() === $route) ? true : false;
    }

    /**
     * Undocumented function
     *
     * @param Object $event
     * @param array $routesArray
     * @param string|null $defaultMessage
     * @param string|null $deleteAction
     * @param Closure $cb
     * @return void
     */
    public function flashingEvent(
        Object $event, 
        array $routesArray = [], 
        ?string $defaultMessage = null, 
        ?string $deleteAction = null,
        Closure $cb = null)
    {
        if (!empty($event->getMethod())) {
            if (in_array($event->getMethod(), array_keys($routesArray), true)) {
                if ($event) {
                    $_msg = $routesArray[$event->getMethod()]['msg'];
                    $event->getObject()->flashMessage($_msg ? $_msg : $defaultMessage);
                    if (null !==$cb) {
                        call_user_func_array($cb, [$event, $routesArray]);
                    } else {
                        $event->getObject()
                        ->redirect(
                            ($this->onRoute($event, $deleteAction) ?
                                '/' .$event->getObject()->getSession()->get('redirect_parameters') :
                                $event->getObject()->onSelf())
                        );
                    }
                }
            }
        }

    }

}