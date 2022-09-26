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
class ImportAction implements DomainActionLogicInterface
{

    use DomainTraits;

    /**
     * execute logic for adding new items to the database()
     *
     * @param object $controller - The controller object implementing this object
     * @param string|null $entityObject
     * @param string|null $eventDispatcher - the eventDispatcher for the current object
     * @param string|null $objectSchema
     * @param string $method - the name of the method within the current controller object
     * @param array $rules
     * @param array $additionalContext - additional data which can be passed to the event dispatcher
     * @return EditAction
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
        $this->actionOptional = $optional;

        $formBuilder = $controller->formBuilder;
       if (isset($formBuilder) && $formBuilder->isFormvalid('import-category')) :
            if ($formBuilder?->csrfValidate()) {    
                $filename = $_FILES['data_import']['tmp_name'];
                if ($_FILES['data_import']['size'] > 0) {

                    $file = fopen($filename, "r");
                    $importCount = 0;
                    while (($column = fgetcsv($file, 10000, ",")) !== false) {
                        if (!empty($column) && is_array($column)) {
                            if ($this->hasEmptyRow($column)) {
                                continue;
                            }
                            $_newData = [];
                            if (isset($column)) {
                                $combine = array_combine($this->controller->repository->getColumns($objectSchema), $column);
                                // var_dump($combine);
                                // die;
                                if (array_key_exists('created_at', $combine)) {
                                    $createdAt = $this->convertExportDatetime('created_at', $combine);
                                    $modifiedAt = $this->convertExportDatetime('modified_at', $combine);
                                    $_newData = $createdAt + $modifiedAt + $combine;
                                } else {
                                    $_newData = $combine;
                                }
                                $insertID = $this->controller->repository->getRepo()->getEm()->getCrud()->create($_newData);
                                if (!empty($insertID)) {
                                    $output['type'] = "success";
                                    $output["message"] = "Import complete";
                                    $importCount++;
                                }
                            }
                        } else {
                            $output['type'] = "error";
                            $output["message"] = "Problem in importing data.";                
                        }
                    }
                }
                if ($importCount == 0) {
                    $output['type'] = "error";
                    $output["message"] = "Duplicate data found.";
                }
                $this->dispatchSingleActionEvent(
                    $controller,
                    $eventDispatcher,
                    $method,
                    $output,
                    $additionalContext
                );

            }
        endif;
        return $this;
    }

    private function hasEmptyRow(array $column)
    {
        $columnCount = count($column);
        $isEmpty = true;
        for ($i = 0; $i < $columnCount; $i ++) {
            if (! empty($column[$i]) || $column[$i] !== '') {
                $isEmpty = false;
            }
        }
        return $isEmpty;
    }

    private function convertExportDatetime(string $key = null, mixed $context = []): array
    {
       return [$key => date('Y-m-d H:i:s', (int)strtotime(str_replace('/', '-', $context[$key])))];

    }
}
