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
use function count;
use function get_class;
use function is_array;

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
     * @param Object $event
     * @param string $eventName
     * @return Object
     */
    public function dispatch(object $event, string $eventName = null): object
    {
        $eventName = $eventName ?? get_class($event);
        if (empty($this->listeners[$eventName])) {
            $listeners = $this->listeners[$eventName];
        } else {
            $listeners = $this->getListeners($eventName);
        }

        if ($listeners) {
            $this->callListeners($listeners, $eventName, $event);
        }

        return $event;
    }

    /**
     * Gets the listeners of a specific event or all listeners sorted by descending priority.
     *
     * @param string|null $eventName
     * @return array - The event listeners for the specified event,
     *                  or all event listeners by event name
     */
    public function getListeners(string $eventName = null) : array
    {
        if (null !== $eventName) {
            if (empty($this->listeners[$eventName])) {
                return [];
            }
            if (!isset($this->sorted[$eventName])) {
                $this->sortListeners($eventName);
            }
            return $this->sorted[$eventName];
        }

        foreach ($this->listeners as $eventName => $eventListeners) {
            if (!isset($this->sorted[$eventName])) {
                $this->sortListeners($eventName);
            }
        }

        return array_filter($this->sorted);
    }

    /**
     * Checks whether an event has any registered listeners.
     *
     * @param string|null $eventName
     * @return bool - true if the specified event has any listeners, false otherwise
     */
    public function hasListeners(string $eventName = null): bool
    {
        if (null !== $eventName) {
            return !empty($this->listeners[$eventName]);
        }
        foreach ($this->listeners as $eventListeners) {
            if ($eventListeners) {
                return true;
            }
        }
        return false;
    }

    /**
     * Check if a an actual event was called
     *
     * @param string|null $eventName
     * @param integer $priority
     * @return bool
     */
    public function isListening(string $eventName = null, int $priority = 0): bool
    {
        if (isset($this->listeners[$eventName][$priority])) {
            return true;
        }
        return false;
    }

    /**
     * Adds an event listener that listens on the specified events.
     *
     * @param string $eventName
     * @param callable $listener - the listener
     * @param integer $priority - The higher this value, the earlier an event
     *                           listener will be triggered in the chain (defaults to 0)
     * @return void
     */
    public function addListener(string $eventName, $listener, int $priority = 0)
    {
        $this->listeners[$eventName][$priority][] = $listener;
        unset($this->sorted[$eventName]);
    }

    /**
     * Add an event to a subscribed lists of events
     *
     * @param EventSubscriberInterface $subscriber
     * @return void
     */
    public function addSubscriber(EventSubscriberInterface $subscriber)
    {
        foreach ($subscriber->getSubscribedEvents() as $eventName => $params) {
            if (is_string($params)) {
                $this->addListener($eventName, [$subscriber, $params]);
            } elseif (is_string($params[0])) {
                $this->addListener($eventName, [$subscriber, $params[0]], $params[1] ?? 0);
            } else {
                foreach ($params as $listener) {
                    $this->addListener($eventName, [$subscriber, $listener[0]], $listener[1] ?? 0);
                }
            }
        }
    }

    /**
     * Undocumented function
     *
     * @param iterable $listeners
     * @param string $eventName
     * @param Object $event
     * @return void
     */
    protected function callListeners(iterable $listeners, string $eventName, object $event)
    {
        $stoppable = $event instanceof StoppableEventInterface;
        foreach ($listeners as $listener) {
            if ($stoppable && $event->isPropagationStopped()) {
                break;
            }
            $listener($event, $eventName, $this);
        }
    }

    /**
     * Sorts the internal list of listeners for the given event by priority.
     *
     * @param string $eventName
     * @return void
     */
    private function sortListeners(string $eventName)
    {
        krsort($this->listeners[$eventName]);
        $this->sorted[$eventName] = [];

        foreach ($this->listeners[$eventName] as &$listeners) {
            foreach ($listeners as $k => &$listener) {
                /*if (is_array($listener) && isset($listener[0]) && $listener[0] instanceof \Closure && 2 >= count($listener)) {
                    $listener[0] = $listener[0]();
                    $listener[1] = $listener[1] ?? '__invoke';
                }*/
                $this->arrayClosure($listener);
                $this->sorted[$eventName][] = $listener;
            }
        }
    }

    /**
     * Undocumented function
     *
     * @param array $value
     * @return void
     */
    private function arrayClosure(array &$value)
    {
        if (
            is_array($value) &&
            isset($value[0]) &&
            $value[0] instanceof Closure &&
            2 >= count($value)
        ) {
            $value[0] = $value[0]();
            $value[1] = $value[1] ?? '__invoke';
        }
    }

    /**
     * Removes an event listener from the specified events.
     *
     * @param string $eventName
     * @param callable $listener - the listener to remove
     * @return void
     */
    public function removeListeners(string $eventName, callable $listener)
    {
        if (empty($this->listeners[$eventName])) {
            return;
        }

        /*if (is_array($listener) && isset($listener[0]) && $listener[0] instanceof Closure && 2 >= count($listener)) {
            $listener[0] = $listener[0]();
            $listener[1] = $listener[1] ?? '__invoke';
        }*/
        $value1 = (array)$listener;
        $this->arrayClosure($value1);
        foreach ($this->listeners[$eventName] as $priority => $listeners) {
            foreach ($listeners as $key => $value) {
                if ($value !== $listener) {
                    $this->arrayClosure($value);
                }
                /*if ($value !== $listener && is_array($value) && isset($value[0]) && $value[0] instanceof Closure && 2 >= count($value)) {
                    $value[0] = $value[0]();
                    $value[1] = $value[1] ?? '__invoke';
                }*/
                if ($value === $listener) {
                    unset($listener[$key], $this->sorted[$eventName]);
                }
            }
            if (!$listener) {
                unset($this->listeners[$eventName][$priority]);
            }
        }
    }

    /**
     * Remove Subscribed events
     *
     * @param EventSubscriberInterface $subscriber
     * @return void
     */
    public function removeSubscriber(EventSubscriberInterface $subscriber)
    {
        foreach ($subscriber->getSubscribedEvents() as $eventName => $params) {
            if (is_array($params) && is_array($params[0])) {
                foreach ($params as $listener) {
                    $this->removeListeners($eventName, [$subscriber, $listener[0]]);
                }
            } else {
                $this->removeListeners($eventName, [$subscriber, is_string($params) ? $params : $params[0]]);
            }
        }

    }

}
