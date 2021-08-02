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
use MagmaCore\Utility\Stringify;
use MagmaCore\Base\BaseApplication;
use MagmaCore\Base\Exception\BaseException;
use MagmaCore\EventDispatcher\EventSubscriberInterface;
use MagmaCore\Base\Exception\BaseBadFunctionCallException;
use MagmaCore\Base\Exception\BaseInvalidArgumentException;

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
                        $output = ($property === 'dataColumns' || $property === 'column') ? $this->$property = $class : $this->$property = BaseApplication::diGet($class);
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
     * Register all event subscribers and listeners
     *
     * @return void
     */
    public function registerSubscribedServices(): void
    {
        $fileServices = Yaml::file('events');
        $services = $fileServices ? $fileServices : self::getSubscribedEvents();
        if (is_array($services) && count($services) > 0) {
            foreach ($services as $serviceParams) {
                foreach ($serviceParams as $key => $params) {
                    if (isset($key) && is_string($key) && $key !== '') {
                        switch ($key) {
                            case 'listeners':
                                $this->resolveListeners($params);
                                break;
                            case 'subscribers':
                                $this->resolveSubscribers($params);
                                break;
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
     * Initialize each participating controller with controller settings data 
     * which is stored and retrive from the database. All controller have options
     * which can adjust the current listing pages ie. change how much data is return
     * within the data table. or change the search term or even enable advance 
     * pagination.
     *
     * @param string|null $controller - the participating controller object
     * @param string $columns - the matching *Column class as a qualified namespace
     * @return boolean
     */
    public function initializeControllerSettings(?string $controller = null, string $columnString): bool
    {
        if (is_array($controllers = Yaml::file('controller'))) {
            foreach ($controllers as $key => $setting) {
                if ($this->thisRouteAction() !== 'index') {
                    continue;
                }
                if (!in_array($controller, array_keys($controllers))) {
                    throw new BaseException('Cannot initialize settings for ' . Stringify::capitalize($key) . 'Controller. Please ensure your controller.yml is referencing specific the controller name. As the array key without the controller suffix');
                }
                $find = $this->controllerSettingsModel
                    ->getRepo()
                    ->findObjectBy(['controller_name' => $key]);


                if (!is_null($find) && $key === trim($find->controller_name)) {
                    continue;
                }
                $controllerSettings = [
                    'controller_name' => $key,
                    'records_per_page' => $setting['records_per_page'],
                    'visibility' => serialize($this->getVisibleColumns($columnString)),
                    'sortable' => serialize($this->getSortableColumns($columnString)),
                    'searchable' => NULL,
                    'query_values' => serialize($setting['status_choices']),
                    'query' => $setting['query'],
                    'filter' => serialize($setting['filter_by']),
                    'alias' => $setting['filter_alias']
                ];
                $action = $this->controllerSettingsModel
                    ->getRepo()
                    ->getEm()
                    ->getCrud()
                    ->create($controllerSettings);
                if ($action) {
                    return $action;
                }
            }
        }

        return false;
    }
}
