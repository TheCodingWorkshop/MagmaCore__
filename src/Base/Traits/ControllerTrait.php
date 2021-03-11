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

namespace MagmaCore\Base\Traits;

use ReflectionMethod;
use MagmaCore\Utility\Yaml;
use MagmaCore\Base\BaseApplication;
use MagmaCore\EventDispatcher\EventSubscriberInterface;
use MagmaCore\Base\Exception\BaseBadMethodCallException;
use MagmaCore\Base\Exception\BaseBadFunctionCallException;
use MagmaCore\Base\Exception\BaseInvalidArgumentException;

trait ControllerTrait
{


    /**
     * Get the reflection action method
     *
     * @param [type] $method
     * @param array $argument
     * @return void
     */
    private function ResolvedControllerMethods($method, array $argument)
    {
        $reflectionMethod = new ReflectionMethod($this, $method);
        $args = [];
        foreach ($reflectionMethod->getParameters() as $param) {
            $name = $param->getName();
            $class = $param->getClass();
            if ($class === null) {
                $args[] = $this->routeParams[$name];
            } else {
                if ($class->isInstance('')) {
                    $args[] = '';
                } else {
                    throw new \BadMethodCallException("Method {$method} does not exists.");
                }
                return call_user_func_array([$this, $method], $argument);
            }
        }
    }

    /**
     * Method for allowing child controller class to dependency inject other objects
     * 
     * @param array|null $args
     * @return Object
     * @throws BaseInvalidArgumentException
     * @throws ReflectionException
     */
    protected function diContainer(?array $args = null)
    {
        if ($args !== null && !is_array($args)) {
            throw new BaseInvalidArgumentException('Invalid argument called in container. Your dependencies should return a key/value pair array.');
        }
        $args = func_get_args();
        if ($args) {
            $output = '';
            foreach ($args as $arg) {
                foreach ($arg as $property => $class) {
                    //if (strpos($class, $arg[$property]) !== false) {
                    if ($class) {
                        $output = ($property === 'dataColumns' || $property === 'column') ? $this->$property = $class : $this->$property = BaseApplication::diGet($class);
                    }
                    //}
                }
            }
            return $output;
        }
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function registerSubscribedServices()
    {
        $fileServices = Yaml::file('events');
        $services = $fileServices ? $fileServices : self::getSubscribedEvents();
        if (is_array($services) && count($services) > 0) {
            foreach ($services as $serviceParams) {
                foreach ($serviceParams as $key => $params) {
                    if (isset($key) && is_string($key) && $key !== '') {
                        switch ($key) {
                            case 'listeners':
                                foreach ($params as $listeners => $values) {
                                    if (isset($listeners)) {

                                        if (!class_exists($values['class'])) {
                                            throw new BaseBadFunctionCallException($values['class'] . ' Listener class was not found within /App/EventListener');
                                        }

                                        $listenerObject = BaseApplication::diGet($values['class']);
                                        /*if (!$listenerObject instanceof ListenerProviderInterface) {
                                            throw new BaseInvalidArgumentException($listenerObject . ' is not a valid Listener Object.');
                                        }*/
                                        if ($this->eventDispatcher) {
                                            if (in_array('name', $values['props'])) {
                                                $this->eventDispatcher->addListener($values['props']['name']::NAME, [$listenerObject, $values['props']['event']]);
                                            }
                                        }
                                    }
                                }
                                break;
                            case 'subscribers':
                                foreach ($params as $subscribers => $values) {
                                    if (isset($subscribers)) {
                                        $subscriberObject = BaseApplication::diGet($values['class']);
                                        if (!$subscriberObject instanceof EventSubscriberInterface) {
                                            throw new BaseInvalidArgumentException($subscriberObject . ' is not a valid subscriber object.');
                                        }
                                        if ($this->eventDispatcher) {
                                            $this->eventDispatcher->addSubscriber($subscriberObject);
                                        }
                                    }
                                }
                                break;
                        }
                    }
                }
            }
        }
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function registerEventListenerServices()
    {
        $eventListenerLocation = Yaml::file('listeners');
        $eventListeners = $eventListenerLocation ? $eventListenerLocation : self::getListenersForEvent();
        if (is_array($eventListeners) && count($eventListeners) > 0) {
            foreach ($eventListeners as $eventListener) {
                foreach ($eventListener as $event => $listeners) {
                    if (isset($event) && is_string($event) && $event !== '') {

                        foreach ($listeners['listeners'] as $key => $value) {

                            $listenerObject = BaseApplication::diGet($value[0]);
                            if (!$listenerObject) {
                                throw new BaseInvalidArgumentException('Invalid Event Listener object.');
                            }

                            $newEvent = "\App\Event\\" . $event;
                            if (!class_exists($newEvent)) {
                                throw new BaseBadFunctionCallException("The event class {$newEvent} does not exists.");
                            }

                            if (!method_exists($listenerObject, $value[2])) {
                                throw new BaseBadMethodCallException("The listener method {$value[2]} does not exists.");
                            }

                            if ($this->eventDispatcher) {
                                $this->eventDispatcher->addListener($newEvent::NAME, [$listenerObject, $value[2]]);
                            }
                        }
                        /*
                            $this->eventDispatcher->dispatch(new $newEvent(new $params['listener'][2]()), $newEvent::NAME);
                        }*/
                    }
                }
            }
        }
        return false;
    }
}
