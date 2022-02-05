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

namespace MagmaCore\Datatable;

abstract class AbstractDatatableColumn implements DatatableColumnInterface
{
    private object $controller;

    /**
     * @inheritdoc
     * @param array $dbColumns
     * @param object|null $callingController
     * @return array
     */
    abstract public function columns(array $dbColumns, ?object $callingController = null) : array;

    /**
     * Checks whether model has defined any status columns and returns true
     * if there is one or false otherwise
     *
     * @param object $controller
     * @return boolean
     */
    public function hasStatusCols(object $controller): bool
    {
        $this->controller = $controller;
        $columns = $controller->repository->getColumnStatus();
        return is_array($columns) && count($columns) > 0;
    }

    /**
     * Return an array of the defined status columns within the specified
     * model
     *
     * @return array
     */
    public function getStatusCols(): array
    {
        return $this->controller->repository->getColumnStatus();
    }

    public function getStatusValues(object $controller, callable $callback = null)
    {
        if ($this->hasStatusCols($controller)) {
            foreach ($this->getStatusCols() as $key => $value) {
                if (is_callable($callback)) {
                    return $callback($key, $value);
                }
            }
        }
    }

    /**
     * Return an array of action links for each row within the data table
     *
     * @param array $row
     * @param string|null $controller
     * @param object|null $tempExt
     * @return array
     */
    abstract public function columnActions(array $row = [], ?string $controller = null, object $tempExt = null): array;



}