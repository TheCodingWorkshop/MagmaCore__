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

namespace MagmaCore\Base\Domain\Actions;

use MagmaCore\Base\Domain\DomainTraits;
use MagmaCore\Base\Domain\DomainActionLogicInterface;

/**
 * Class which handles the domain logic when adding a new item to the database
 * items are sanitize and validated before persisting to database. The class will
 * also dispatched any validation error before persistence. The logic also implements
 * event dispatching which provide usable data for event listeners to perform other
 * necessary tasks and message flashing
 */
class ControllerSettingsAction implements DomainActionLogicInterface
{

    use DomainTraits;

    /** @return void - not currently being used */
    public function __construct()
    {
    }

    /**
     * execute logic for adding new items to the database()
     *
     * @param Object $controller - The controller object implementing this object
     * @param string|null $entityObject
     * @param string|null $eventDispatcher - the eventDispatcher for the current object
     * @param string|null $objectSchema
     * @param string $method - the name of the method within the current controller object
     * @param array $rules
     * @param array $additionalContext - additional data which can be passed to the event dispatcher
     * @return ActivateAction
     */
    public function execute(
        object $controller,
        ?string $entityObject,
        ?string $eventDispatcher,
        ?string $objectSchema,
        string $method,
        array $rules = [],
        array $additionalContext = [],
        mixed $optional = null
    ): self {

        $this->controller = $controller;
        $this->method = $method;
        $this->schema = $objectSchema;

        if (isset($controller->formBuilder)) :
            if ($controller->formBuilder->isFormValid($this->getSubmitValue())) { /* return true if form  is valid */
                $controller->formBuilder->validateCsrf($controller); /* Checks for csrf validation token */
                $formData = $controller->formBuilder->getData();
                $settingsController = yaml::file('controller')[$this->thisRouteController()];
                $controllerSettings = [
                    'controller_name' => $settingsController,
                    'records_per_page' => $settingsController['records_per_page'],
                    'visibility' => serialize($controller->getVisibleColumns($columnString)),
                    'sortable' => serialize($controller->getSortableColumns($columnString)),
                    'searchable' => NULL,
                    'query_values' => serialize($settingsController['status_choices']),
                    'query' => $settingsController['query'],
                    'filter' => serialize($settingsController['filter_by']),
                    'alias' => $settingsController['filter_alias']
                ];
                $action = $controller->repository
                    ->getRepo()
                    ->getRepo()
                    ->getEm()
                    ->getCrud()
                    ->create($controllerSettings);

                if ($action) {
                    if ($controller->eventDispatcher) {
                        $controller->eventDispatcher->dispatch(
                            new $eventDispatcher(
                                $method,
                                array_merge(
                                    $controller->repository->getRepo()->validatedDataBag(),
                                    $additionalContext ? $additionalContext : []
                                ),
                                $controller
                            ),
                            $eventDispatcher::NAME
                        );
                    }
                }
                $this->domainAction = $action;
            }
        endif;

        return $this;
    }
}
