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
    private $sessionKey = 'session_export_settings';

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
        $this->exportReset($formBuilder);
        list($exportOptions) = $this->exportSessionInit();

        if (isset($formBuilder) && $formBuilder->isFormvalid($this->getSubmitValue())) :
            if ($formBuilder?->csrfValidate()) {       
                $formData = $formBuilder->getData();
                if (is_array($formData) && count($formData) > 0) {
                    
                    /* Unset unwanted data */
                    unset(
                        $formData['_CSRF_INDEX'], 
                        $formData['_CSRF_TOKEN'], 
                        $formData[$this->getSubmitValue()]
                    );

                    $oldExportSession = $exportOptions;
                    $newExportSession = $formData + $oldExportSession;
                    if (is_array($newExportSession) && count($newExportSession) > 0) {
                        /* flush old session */
                        $this->flushSessionSettings($this->controller, $this->sessionKey, $newExportSession);
                    }
                    $this->csvExport($objectSchema, $newExportSession);
                    $this->controller->flashMessage('Data exported.');
                    $this->controller->redirect($this->controller->onSelf());
        
                }

            }
        endif;
        return $this;
    }

    /**
     * csv export
     *
     * @param string|null $objectSchema
     * @param array|null $exportData
     * @return void
     */
    private function csvExport(string $objectSchema = null, array $exportData = null): void
    {
        $delimiter  = ",";
        $appendDate = date('Y-m-d');
        $exportFormat = (isset($exportData['export_format']) ? $exportData['export_format'] : 'csv');
        $defaultFileanme = $this->controller->thisRouteController() . "-data_" . $appendDate . '.' . $exportFormat;
        $filename = (isset($exportData['export_filename']) && $exportData['export_filename'] !=='' ? $exportData['export_filename'] . '_' . $appendDate . '.' . $exportFormat : $defaultFileanme);

        $output = fopen("php://output", 'w');
        header('Content-Type: text/' . $exportFormat); 
        header('Content-Disposition: attachment; filename="' . $filename . '";'); 
        $columns = $this->controller->repository->getColumns($objectSchema);
        $logLimit = ['limit' => (int)$exportData['log_records'], 'offset' => 0] ?? [];
        $dbData = $this->controller->repository->getRepo()->findBy([], [], $logLimit, ['orderby' => 'id ASC']);
        fputcsv($output, $columns, $delimiter);
        foreach ($dbData as $data) {
            fputcsv($output, $data, $delimiter);
        }
        fseek($output, 0);
        fpassthru($output);
        exit;

    }

    /**
     * Initialize the session data for the export settings page.
     *
     * @return array
     */
    private function exportSessionInit(): array
    {
        $exportSettings = ['export_filename' => '', 'log_records' => '1000', 'export_format' => 'csv', 'export_conditions' => '1_year', 'custom_export_conditions' => '', 'last_export_timestamp' => time()];  
        $this->createSessionSettings($this->controller, $this->sessionKey, $exportSettings);
        $exportOptions = $this->getSessionSettings($this->controller, $this->sessionKey);

        return [$exportOptions];

    }

    private function exportReset(object $formBuilder)
    {
        if ($formBuilder->isFormValid('export-' . $this->controller->thisRouteController() . '-reset')) {
            $session = $this->controller->getSession();
            if ($session->has($this->sessionKey)) {
                $session->delete($this->sessionKey);

                $this->exportSessionInit();
            }
            $this->controller->flashMessage('Export settings reset.');
            $this->controller->redirect($this->controller->onSelf());

        }
    }

}
