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

use MagmaCore\Base\Access;

trait ControllerCommonTrait
{

    /**
     * index route contains various submit button with unique name attributes that
     * performs different task. The page is typically encapsulated by one form which 
     * actions post back to this route
     *
     * @param object|null $controller
     * @param string|null $redirectMsg
     * @param string|null $actionEvent
     * @param string $field
     * @return void
     */
    protected function chooseBulkAction(
        ?object $controller = null, 
        ?string $actionEvent = null, 
        ?string $redirectMsg = null, 
        string $field = 'deleted_at'
    ): void
    {
        $_name = strtolower($controller->thisRouteController());

        foreach (
            ['emptyTrash-' . $_name, 
            'restoreTrash-' . $_name, 
            'bulkTrash-' . $_name, 
            'bulkClone-' . $_name] as $action) {
            if (array_key_exists($action, $this->formBuilder->getData())) {
                $data = $this->formBuilder->getData();
                switch ($action) :
                    case 'emptyTrash-' . $_name :
                        $this->emptyTrash($action, $data, $actionEvent);
                        break;
                    case 'restoreTrash-' . $_name :
                        $this->restoreTrash(
                            $action, 
                            $data, 
                            $actionEvent, 
                            [$field => 0]);
                        break;
                    case 'bulkTrash-' . $_name :
                        $this->bulkTrash(
                            $action, $data, $actionEvent, [$field => 1]
                        );
                        break;
                endswitch;
             }
        }
        $this->flashMessage(($redirectMsg !==null) ? $redirectMsg : 'Records Updated!');
        $this->redirect(sprintf('/admin/%s/index', $_name));
    }

    /**
     * Undocumented function
     *
     * @param string|null $action
     * @param array $data
     * @param string|null $eventDispatcher
     * @param mixed $optional
     * @return void
     */
    private function emptyTrash(
        string $action = null, 
        array $data = [], 
        ?string $eventDispatcher = null, 
        mixed $optional = null
        )
    {
        if (array_key_exists($action, $this->formBuilder->getData())) {
            $this->bulkDeleteAction
                ->setAccess($this, Access::CAN_BULK_DELETE)
                ->execute($this, NULL, $eventDispatcher, NULL, __METHOD__, [], [], $optional)
                ->endAfterExecution();
        }
    }

    /**
     * Undocumented function
     *
     * @param string|null $action
     * @param array $data
     * @param string|null $eventDispatcher
     * @param mixed $optional
     * @return void
     */
    private function restoreTrash(
        string $action = null, 
        array $data = [], 
        ?string $eventDispatcher = null, 
        mixed $optional = null
        )
    {
        if (array_key_exists($action, $this->formBuilder->getData())) {
            $this->bulkUpdateAction 
                ->setAccess($this, Access::CAN_BULK_RESTORE)
                ->execute($this, NULL, $eventDispatcher, NULL, __METHOD__, [], [], $optional)
                ->endAfterExecution();
        }
    }

    /**
     * Undocumented function
     *
     * @param string|null $action
     * @param array $data
     * @param string|null $eventDispatcher
     * @param mixed $optional
     * @return void
     */
    private function bulkTrash(
        string $action = null, 
        array $data = [], 
        ?string $eventDispatcher = null,
        mixed $optional = null
        )
    {
        if (array_key_exists($action, $this->formBuilder->getData())) {
            $this->bulkUpdateAction 
                ->setAccess($this, Access::CAN_BULK_TRASH)
                ->execute($this, NULL, $eventDispatcher, NULL, __METHOD__, [], [], $optional)
                ->endAfterExecution();
        }
    }

    /**
     * Undocumented function
     *
     * @param string|null $action
     * @param array $data
     * @param string|null $eventDispatcher
     * @param mixed $optional
     * @return void
     */
    private function bulkClone(
        string $action = null, 
        array $data = [], 
        ?string $eventDispatcher = null,
        mixed $optional = null
        )
    {
        die('bulk cloning');
    }



}