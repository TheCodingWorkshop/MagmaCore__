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

namespace MagmaCore\Utility;

use InvalidArgumentException;

class Sortable
{

    /** @var array */
    protected array $columns;

    /** @var string */
    protected string $column;

    /** @var string */
    protected string $order;

    /** @var string */
    protected string $direction;
    
    /** @var string */
    protected string $sortDirection = '';

    /** @var string */
    protected string $class = 'highlight';

    public function __construct(array $columns = [])
    {
        if (empty($columns)) {
            throw new InvalidArgumentException('Invalid argument. Please specify a default columns array.');
        }
        $this->columns = $columns;
    }

    public function getColumn() : string
    {
        $cols = array_values(array_filter($this->columns, fn($col) => $col));
        if (is_array($this->columns)) {
            $this->column = isset($_GET['column']) && in_array($_GET['column'], $this->columns) ? $_GET['column'] : $cols[0];
            if ($this->column) {
                return $this->column;
            }
        }
    }

    public function getDirection() : string
    {
        $this->order = isset($_GET['order']) && strtolower($_GET['order']) == 'desc' ? 'DESC' : 'ASC';
        if ($this->order) {
            return $this->order;
        }
    }

    public function sortDirection() : string
    {
        $this->direction = str_replace(array('ASC', 'DESC'), array('up', 'down'), $this->getDirection());
        if ($this->direction) {
            return $this->direction;
        }
    }

    public function sortDescAsc(): string
    {
        if ($this->getDirection()) {
            return $this->getDirection() == 'ASC' ? 'desc' : 'asc';
        }
    }

    public function getClass() : string
    {
        return $this->class;
    }

}