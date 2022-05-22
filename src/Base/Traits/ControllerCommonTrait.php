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
     * Returns a 404 error page if the data is not present within the database
     * else return the requested object
     *
     * @return mixed
     */
    public function findOr404(): mixed
    {
        if (isset($this)) {
            return $this->repository->getRepo()
                ->findAndReturn($this->thisRouteID())
                ->or404();
        }
    }

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
        mixed $field = 'deleted_at'
    ): void
    {
        $_name = strtolower($controller->thisRouteController());

        foreach (
            [
                'emptyTrash-' . $_name, 
                'restoreTrash-' . $_name, 
                'bulkTrash-' . $_name, 
                'bulkClone-' . $_name,
                's-' . $_name,
            ] as $action) {

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
                            (isset($fields) && is_array($fields) && count($fields) > 0 ? $fields : ['deleted_at' => 0])
                        );
                        break;
                    case 'bulkTrash-' . $_name :
                        $this->bulkTrash(
                            $action, $data, $actionEvent, (isset($fields) && is_array($fields) && count($fields) > 0 ? $fields : ['deleted_at' => 1])
                        );
                        break;
                    case 'bulkClone-' . $_name :
                        $this->bulkClone($action, $data, $actionEvent);
                        break;
                    default :
                        echo 'test';
                        break;
                endswitch;
             }
        }
        $this->flashMessage(($redirectMsg !==null) ? $redirectMsg : 'Records Updated!');
        $this->redirect(sprintf('/admin/%s/index', $_name));
    }

    /**
     * Bulk empty the trash from any controller
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
        if (array_key_exists($action, $data)) {
            $this->bulkDeleteAction
                ->setAccess($this, Access::CAN_BULK_DELETE)
                ->execute($this, NULL, $eventDispatcher, NULL, __METHOD__, [], [], $optional)
                ->endAfterExecution();
        }
    }

    /**
     * Bulk restore trash from any controller
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
        if (array_key_exists($action, $data)) {
            $this->bulkUpdateAction 
                ->setAccess($this, Access::CAN_BULK_RESTORE)
                ->execute($this, NULL, $eventDispatcher, NULL, __METHOD__, [], [], $optional)
                ->endAfterExecution();
        }
    }

    /**
     * bulk update the trash from any controller
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

        if (array_key_exists($action, $data)) {
            $this->bulkUpdateAction 
                ->setAccess($this, Access::CAN_BULK_TRASH)
                ->execute($this, NULL, $eventDispatcher, NULL, __METHOD__, [], [], $optional)
                ->endAfterExecution();
        }
    }

    /**
     * Bulk clone selected items
     *
     * @param string|null $action
     * @param array $data
     * @param string|null $eventDispatcher
     * @param mixed $optional
     * @return void
     */
    private function bulkClone(string $action = null, array $data = [], ?string $eventDispatcher = null,mixed $optional = null)
    {
        // $data variables contains the selected object ids only. We can use these to fetch the data which belongs to those ids
        if (array_key_exists($action, $data)) {
            $this->bulkCloneAction
                ->setAccess($this, Access::CAN_BULK_CLONE)
                ->execute($this, NULL, $eventDispatcher, NULL, __METHOD__)
                ->endAfterExecution();
        }
    }



}