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

use MagmaCore\EventDispatcher\EventDispatcherInterface;
use MagmaCore\EventDispatcher\EventSubscriberInterface;

class EventDispatcher implements EventDispatcherInterface
{

    /** @var array */
    protected array $listeners = [];
    /** @var array */
    protected array $sorted = [];
    /** @var string */
    protected string $currentEvent;

    /** @return void */
    public function __construct()
    { }

    /**
     * @inheritdoc
     */
    public function dispatch(object $event, array $args = [])
    {
        if (!isset($this->listeners[$event])) {
            return;
        }
        $this->currentEvent = $event;
        return $this->callListenders($this->listeners[$event], $args);
    }

    private function callListenders(iterable $listeners, $args)
    {
        if (is_array($listeners) && count($listeners) > 0) {
            foreach ($listeners as $listener) {
                $stopPropogating = call_user_func_array($listener, $args);
                if ($stopPropogating) {
                    return true;
                }
            }
            return false;
        }
    }

    /**
     * Remove an event listener from the event array list
     *
     * @param mixed $event
     * @param string $listener
     * @return void
     */
    public function removeListeners($event, string $listener) : void
    {
        if (!isset($this->listeners[$event])) {
            return;
        }
        if (count($this->listeners) > 0) {
            foreach ($this->listeners[$event] as $priority => $listeners) {
                if (false !== ($key = array_search($listener, $listeners, true))) {
                    unset($this->listeners[$event][$priority][$key], $this->sorted[$event]);
                }
            }
        }
    }

    /**
     * Add a listener
     *
     * @param mixed $event
     * @param mixed $listener
     * @param integer $priority
     * @return void
     */
    public function addListener($event, $listener, int $priority = 0) : void
    {
        $this->listeners[$event][$priority][] = $listener;
        unset($this->sorted[$event]);
    }

    /**
     * Returns a list of sorted events.
     *
     * @param $event
     * @return array
     */
    public function getListeners($event = null) : iterable
    {
        if (null !== $event) {
            if (!isset($this->sorted[$event])) {
                $this->sortListeners($event);
            }
            return $this->sorted[$event];
        }
        foreach (array_keys($this->listeners) as $event) {
            if (!isset($this->sorted[$event])) {
                $this->sortLIsteners($event);
            }
        }
    }

    /**
     * Sort the listeners by their key
     *
     * @param Object $event
     * @return void
     */
    private function sortListeners(Object $event) : void
    {
        $this->sorted[$event] = [];
        if (isset($this->listeners[$event])) {
            ksort($this->listeners[$event]);
            $this->sorted[$event] = call_user_func_array('array_merge', $this->listeners[$event]);
        }
    }

    /**
     * Check if we have a particular event
     *
     * @param $event
     * @return boolean
     */
    public function hasListener($event = null) : bool
    {
        return (bool)count($this->getListeners($event));
    }

    /**
     * Check if a an actual event was called
     *
     * @param mixed $event
     * @param integer $priority
     * @return boolean
     */
    public function isListening($event, int $priority = 0) : bool
    {
        if (isset($this->listeners[$event][$priority])) {
            return true;
        }
        return false;
    }

    /**
     * Returns the currently listening event
     *
     * @return string
     */
    public function nowListening() : string
    {
        return $this->currentEvent;
    }

    public function addSubscriber(EventSubscriberInterface $subscriber)
    {
        foreach ($subscriber->getSubscribedEvents() as $eventName => $params) {
            if (is_string($params)) {
                $this->addListener([$subscriber, $params], $eventName);
            } elseif (is_string($params[0])) {
                $this->addListener([$subscriber, $params[0]], $eventName, $params[1] ?? 0);
            } else {
                foreach ($params as $listener) {
                    $this->addListener([$subscriber, $listener[0]], $eventName, $listener[1] ?? 0);
                }
            }
        }
    }

    public function removeSubscriber(EventSubscriberInterface $subscriber)
    {
        foreach ($subscriber->getSubscribedEvents() as $eventName => $params) {
            if (is_array($params) && is_array($params[0])) {
                foreach ($params as $listener) {
                    $this->removeListeners([$subscriber, $listener[0]], $eventName);
                }
            } else {
                $this->removeListeners([$subscriber, is_string($params) ? $params : $params[0]], $eventName);
            }
        }
    }

}