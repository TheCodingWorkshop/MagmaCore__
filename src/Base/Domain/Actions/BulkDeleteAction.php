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

use MagmaCore\Base\Domain\DomainActionLogicInterface;
use MagmaCore\Base\Domain\DomainTraits;

/**
 * Class which handles the domain logic when adding a new item to the database
 * items are sanitize and validated before persisting to database. The class will 
 * also dispatched any validation error before persistence. The logic also implements
 * event dispatching which provide usable data for event listeners to perform other
 * necessary tasks and message flashing
 */
class BulkDeleteAction implements DomainActionLogicInterface
{

    use DomainTraits;

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
     * @return BulkDeleteAction
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
        $formBuilder = $controller->formBuilder;
        var_dump($formData);
        die;

        if (isset($formBuilder) && $formBuilder?->isFormValid($this->getSubmitValue())) :

            $formData = $this->isAjaxOrNormal();
            if ($this->isArrayGood($formData)) {
                $schemaID = $controller->repository->getSchemaID();
                $action = array_map(fn($id) => $controller?->repository
                ->getRepo()
                ->getEm()
                ->getCrud()
                ->delete([$schemaID => $id]), $formData[$schemaID]);
                if ($action) {
                    $this->dispatchSingleActionEvent(
                        $controller,
                        $eventDispatcher,
                        $method,
                        ['action' => $action],
                        $additionalContext
                    );
                }

            }
        endif;
        return $this;
    }
}
