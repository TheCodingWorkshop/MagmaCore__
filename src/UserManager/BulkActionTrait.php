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

namespace MagmaCore\UserManager;

use MagmaCore\Base\Access;
use MagmaCore\Base\Events\BulkActionEvent;

trait BulkActionTrait
{

    /**
     * The bulk delete action request. is responsible for deleting multiple record from
     * the database. This method is not a submittable method hence why this check has
     * been omitted. This a simple click based action. which is triggered within the
     * datatable. An event will be dispatch by this action
     */
    protected function bulkAction()
    {
        foreach (['bulk-delete', 'bulk-clone'] as $action) {
            if (array_key_exists($action, $this->formBuilder->getData())) {
                $id = $this->repository->getSchemaID();
                $this->showBulkAction
                    ->setAccess($this, Access::CAN_BULK_DELETE)
                    ->execute($this, NULL, BulkActionEvent::class, NULL, __METHOD__)
                    ->render()
                    ->with(
                        [
                            'selected' => $this->formBuilder->getData()[$id] ?? $_POST[$id],
                            'action' => $action,
                        ]
                    )
                    ->form($this->bulkDeleteForm)
                    ->end();
            }
        }
    }


    /**
     * The bulk delete action request. is responsible for deleting multiple record from
     * the database. This method is not a submittable method hence why this check has
     * been omitted. This a simple click based action. which is triggered within the
     * datatable. An event will be dispatch by this action
     */
    protected function bulkDeleteAction()
    {
        if (array_key_exists('bulkDelete-' . $this->thisRouteController(), $this->formBuilder->getData())) {
            $this->bulkDeleteAction
                ->setAccess($this, Access::CAN_BULK_DELETE)
                ->execute($this, NULL, BulkActionEvent::class, NULL, __METHOD__)
                ->endAfterExecution();
        }
    }

    /**
     * Clone a user account and append a unique index to prevent email unique key
     * collision
     */
    protected function bulkCloneAction()
    {
        if (array_key_exists('bulkClone-' . $this->thisRouteController(), $this->formBuilder->getData())) {
            $this->bulkCloneAction
                ->setAccess($this, Access::CAN_BULK_CLONE)
                ->execute($this, NULL, BulkActionEvent::class, NULL, __METHOD__)
                ->endAfterExecution();
        }
    }


}