<?php

declare(strict_types=1);

namespace MagmaCore\Base;

use ReflectionMethod;
use MagmaCore\Utility\Yaml;
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
    protected function container(?array $args = null)
    {
        if ($args !==null && !is_array($args)) {
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
        $subscriberLocation = Yaml::file('subscribers');
        $subscribers = $subscriberLocation ? $subscriberLocation : self::getSubscribedServices();
        if (is_array($subscribers) && count($subscribers) > 0) {
            foreach ($subscribers as $subscriberParams) {
                foreach ($subscriberParams as $property => $params) {
                    if (isset($property) && is_string($property) && $property !=='') {
                        $subscriberObject = BaseApplication::diGet($params['class']);

                        if (!$subscriberObject instanceof EventSubscriberInterface) {
                            throw new BaseInvalidArgumentException('Invalid Subscriber object ' . $subscriberObject);
                        }

                        $this->eventDispatcher->addSubscriber($subscriberObject);

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
                    if (isset($event) && is_string($event) && $event !=='') {

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

                            if($this->eventDispatcher) {
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