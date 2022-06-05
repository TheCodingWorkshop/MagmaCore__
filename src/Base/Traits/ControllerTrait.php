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

use MagmaCore\Utility\Yaml;
use MagmaCore\Base\BaseApplication;
use MagmaCore\Base\Exception\BaseRuntimeException;
use MagmaCore\EventDispatcher\EventSubscriberInterface;
use MagmaCore\EventDispatcher\ListenerProviderInterface;
use MagmaCore\Base\Exception\BaseBadFunctionCallException;
use MagmaCore\Base\Exception\BaseInvalidArgumentException;
use MagmaCore\Base\Exception\BaseUnexpectedValueException;

trait ControllerTrait
{
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
                    if ($class) {
                        $output = (
                            $property === 'dataColumns' || 
                            $property === 'column' || 
                            $property === 'rawSchema' ||
                            $property === 'actionEvent') ? $this->$property = $class : $this->$property = BaseApplication::diGet($class);
                    }
                }
            }
            return $output;
        }
    }

    /**
     * Alias of diContainer
     *
     * @param array|null $args
     * @return mixed
     */
    public function addDefinitions(?array $args = null): mixed
    {
        return $this->diContainer($args);
    }


    /**
     * Return only the parameter array of the extended event configurations
     *
     * @return array
     */
    private function getExtendedEventServiceParams(): array
    {
        $_params = [];
        $extendedEvents = Yaml::file('extend_events');
        if (is_array($extendedEvents) && count($extendedEvents) > 0) {
            foreach ($extendedEvents as $events) {
                foreach ($events as $key => $param) {
                    if (is_array($param)) {
                        $_params = $param;
                    }
                }
            }
        }
        return $_params;
    }

    /**
     * Register all application event subscribers and listeners
     *
     * @return void
     */
    public function registerSubscribedServices(array $otherServices = []): void
    {
        $extendedEventsParams = $this->getExtendedEventServiceParams();
        $fileServices = Yaml::file('events');
        if (!empty($fileServices)) {
            $services = $fileServices ? $fileServices : self::getSubscribedEvents();
            if (is_array($services) && count($services) > 0) {
                foreach ($services as $serviceParams) {
                    foreach ($serviceParams as $key => $params) {
                        $extendedParams = array_merge($extendedEventsParams ?? [], $params);
                        if (isset($key) && is_string($key) && $key !== '') {
                            switch ($key) {
                                case 'listeners':
                                    $this->resolveListeners($extendedParams);
                                    break;
                                case 'subscribers':
                                    $this->resolveSubscribers($extendedParams);
                                    break;
                            }
                        }
                    }
                }
            }    
        }

    }

    /**
     * Resolve the event listeners from the events.yml file
     *
     * @param array $parameters
     * @return void
     */
    private function resolveListeners(array $parameters): void
    {
        foreach ($parameters as $listeners => $values) {
            if (isset($listeners)) {

                if (!class_exists($values['class'])) {
                    throw new BaseBadFunctionCallException($values['class'] . ' Listener class was not found within /App/EventListener');
                }
                $listenerObject = BaseApplication::diGet($values['class']);
                if (!$listenerObject instanceof ListenerProviderInterface) {
                    throw new BaseInvalidArgumentException($listenerObject . ' is not a valid Listener Object.');
                }
                if ($this->eventDispatcher) {
                    if (in_array('name', $values['props'])) {
                        $this->eventDispatcher->addListener($values['props']['name']::NAME, [$listenerObject, $values['props']['event']]);
                    }
                }
            }
        }
    }

    /**
     * Resolve the events subscribers from the events.yml file
     *
     * @param array $parameters
     * @return void
     */
    private function resolveSubscribers(array $parameters): void
    {
        foreach ($parameters as $subscribers => $values) {
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
    }

    /**
     * Register all events and listeners within the base controller constructor
     * @throws \Exception
     */
    public function initEvents(): void
    {
        try {
            $this->registerSubscribedServices();
        } catch(BaseRuntimeException) {

        }
    }

    /**
     * Undocumented function
     *
     * @param string $columnString
     * @param string $part
     * @return array
     */
    public function getColumnParts(string $columnString, string $part = 'sortable'): array
    {
        $columns = BaseApplication::diGet($columnString);
        if ($columns) {
            return array_filter(
                $columns->columns(),
                fn ($column) => isset($column[$part]) && $column[$part] === true ? $column['dt_row'] : []
            );
        }
    }

    /**
     * Return an array of sortable columns from a *Column class. Only the sortable 
     * columns which is set to true will be returned
     *
     * @param string $columnString
     * @return array
     */
    public function getSortableColumns(string $columnString): array
    {
        $sortables = $this->getColumnParts($columnString);
        return array_map(fn ($col) => $col['db_row'], $sortables);
    }

    /**
     * Return an array of visible columns from a *Column class. Only the show  
     * columns which is set to true will be returned
     *
     * @param string $columnString
     * @return array
     */
    public function getVisibleColumns(string $columnString)
    {
        $visibleColumns = $this->getColumnParts($columnString, 'show_column');
        return array_map(fn ($col) => $col['db_row'], $visibleColumns);
    }

    /**
     * Return an array of searchable columns from a *Column class. Only the searchable 
     * columns which is set to true will be returned
     *
     * @param string $columnString
     * @return array
     */
    public function getSearchableColumns(string $columnString)
    {
        $sortables = $this->getColumnParts($columnString, 'searchable');
        return array_map(fn ($col) => $col['db_row'], $sortables);
    }

    /**
     * Method is called from ControllerMenuTrait (buildVontrollerMenu) method and will only execute if
     * controller menu isn't already initialize
     *
     * Initialize each participating controller with controller settings data
     * which is stored and retrive from the database. All controller have options
     * which can adjust the current listing pages ie. change how much data is return
     * within the data table. or change the search term or even enable advance
     * pagination.
     *
     * @param string $controller - the participating controller object
     * @param string $columns - the matching *Column class as a qualified namespace
     * @return boolean
     */
    public function initializeControllerSettings(string $controller, string $columnString, $menu_id): bool
    {
        if (is_array($controllers = Yaml::file('controller'))) {
            foreach ($controllers as $key => $setting) {
                if ($key === $controller) {
                    $find = $this->controllerSettings->getRepo()->findObjectBy(['controller_name' => $key]);
                    if (!is_null($find) && $key === trim($find->controller_name)) {
                        continue;
                    }
                    $controllerSettings = [
                        'controller_menu_id' => $menu_id,
                        'controller_name' => $key,
                        'records_per_page' => 1, /* set to 1 by default as will inherit the global value if the value is below 5 */
                        'visibility' => serialize($this->getVisibleColumns($columnString)),
                        'sortable' => serialize($this->getSortableColumns($columnString)),
                        'searchable' => NULL,
                        'query_values' => serialize($setting['status_choices']),
                        'query' => $setting['query'],
                        'filter' => serialize($setting['filter_by']),
                        'alias' => $setting['filter_alias']
                    ];
                    $action = $this->controllerSettings
                        ->getRepo()
                        ->getEm()
                        ->getCrud()
                        ->create($controllerSettings);
                    if ($action) {
                        return $action;
                    }

                }
            }
        }

        return false;
    }

    /**
     * @param string $event
     */
    public function dispatchEvent(string $event, string $method, array $context = [], object $controller)
    {
        if (empty($event) || !is_string($event)) {
            throw new BaseUnexpectedValueException('Please specify the required argument for the method ' . __METHOD__);
        }
        $cloneObject = clone $this;
        if (isset($this->eventDispatcher)) {
            $this->eventDispatcher->dispatch(
                new $event($method, $context, $controller), 
                $event::NAME
            );
        }
    }
}
