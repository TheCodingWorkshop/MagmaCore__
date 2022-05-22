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
use MagmaCore\Base\Traits\SessionSettingsTrait;
use MagmaCore\Utility\Serializer;

/**
 * Class which handles the domain logic when adding a new item to the database
 * items are sanitize and validated before persisting to database. The class will 
 * also dispatched any validation error before persistence. The logic also implements
 * event dispatching which provide usable data for event listeners to perform other
 * necessary tasks and message flashing
 */
class ExportAction implements DomainActionLogicInterface
{

    use DomainTraits;
    use SessionSettingsTrait;

    public bool $passwordRequired = false;

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
        $key = 'session_export_settings';
        $exportSettings = ['log_records' => '1000', 'export_format' => 'csv', 'export_conditions' => '1_year', 'custom_export_conditions' => ''];  
        $this->createSessionSettings($this->controller, $key, $exportSettings);
        $exportOptions = $this->getSessionSettings($this->controller, $key);

        if (isset($formBuilder) && $formBuilder->isFormvalid($this->getSubmitValue())) :
            if ($formBuilder?->csrfValidate()) {       
                $postData = $formBuilder->getData();
                foreach ($exportOptions as $key => $value) {
                    if (array_key_exists($key, $postData)) {
                        unset($postData['_CSRF_INDEX'], $postData['_CSRF_TOKEN'], $postData[$this->getSubmitValue()]);
                        /* if the value of the submitted data isn't the same as the old session data lets update the session data with the new submitted data */
                        if ($postData[$key] !== $value) {
                            /* override the old values with the new ones */
                            $key = $value;
                        }
                    }
                }
                
                /* If array is equal to 0. Then this section will be skip as this will interpret array = 0 as no changes made */
                $comparison = array_diff($postData, $exportOptions);
                $newExportSession = [];
                /* if theres a change then a populated array will return the changes. This way we can trigger ourt session to handle the chnages */
                if (is_array($comparison) && count($comparison) > 0) {
                    /* we will just delete the old session and create a new one with the new post data */
                    $this->flushSessionSettings($this->controller, $key, $postData);
                }
                /* Get a fresh copy of the new session data */
                $newExportSession = Serializer::unCompress($this->controller->getSession()->get($key));
                var_dump($newExportSession);
                die;

                $delimiter  = ",";
                $filename = $this->controller->thisRouteController() . "-data-" . date("Y-m-d") . ".csv";
                $output = fopen("php://output", 'w');
                header('Content-Type: text/csv'); 
                header('Content-Disposition: attachment; filename="' . $filename . '";'); 
                $columns = $this->controller->repository->getColumns($objectSchema);
                $dbData = $this->controller->repository->getRepo()->findBy([], [], [], ['orderby' => 'id ASC']);
                fputcsv($output, $columns, $delimiter);
                foreach ($dbData as $data) {
                    fputcsv($output, $data, $delimiter);
                }
                fseek($output, 0);
                fpassthru($output);
                exit;

            }
        endif;
        return $this;
    }

    private function csvExport(string $objectSchema = null, string $filename = null, string $delimiter = null): void
    {
        $output = fopen("php://output", 'w');
        header('Content-Type: text/csv'); 
        header('Content-Disposition: attachment; filename="' . $filename . '";'); 
        $columns = $this->controller->repository->getColumns($objectSchema);
        $dbData = $this->controller->repository->getRepo()->findBy([], [], [], ['orderby' => 'id ASC']);
        fputcsv($output, $columns, $delimiter);
        foreach ($dbData as $data) {
            fputcsv($output, $data, $delimiter);
        }
        fseek($output, 0);
        fpassthru($output);
        exit;

    }
}
