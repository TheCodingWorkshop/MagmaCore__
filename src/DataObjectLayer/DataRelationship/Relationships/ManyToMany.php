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

namespace MagmaCore\DataObjectLayer\DataRelationship\Relationships;

use MagmaCore\DataObjectLayer\DataRelationship\DataRelationship;
use MagmaCore\DataObjectLayer\DataRelationship\DataRelationalInterface;

/**
 * Both tables can have only one record on each side of the relationship.
 * each primary key value relates to none or only one record in the related table
 */
class ManyToMany extends DataRelationship implements DataRelationalInterface
{

    private string $query = '';

    public function __construct()
    {
        parent::__construct();
    }

    public function filterSelection(
        string $parentModel,
        array $selectors,
        $defaultSelector = ['*']
    ) {
        $filter = array_map(
            fn ($selector): string => $parentModel . '.' . $selector,
            $selectors
        );
        return (empty($filter)) ? $defaultSelector : $filter;
    }


    /**
     * Undocumented function
     *
     * @param array $leftSelectors
     * @param array $rightSelectors
     * @return void
     */
    public function manyToMany(array $leftSelectors = [], array $rightSelectors = []): self
    {
        $_leftSelectors = implode(', ', $this->filterSelection($this->getLeft()->getSchema(), $leftSelectors));
        $_rightSelectors = implode(', ', $this->filterSelection('', $rightSelectors));

        $this->query .= "SELECT 
        {$_leftSelectors} 
        AS 
        {$this->getLeft()->getSchema()}, 
        " . str_replace('.', '', $_rightSelectors) . "
        AS
        {$this->getRight()->getSchema()}
        FROM
        {$this->getLeft()->getSchema()}, {$this->getRight()->getSchema()}, {$this->getPivot()->getSchema()}
        ";
        return $this;

    }

    /**
     * Undocumented function
     *
     * @param array $conditions
     * @return void
     */
    public function where(array $conditions = []): self /* ['id' => 'user_id'] */
    {

        if (is_array($conditions) && count($conditions) > 0) {
            foreach ($conditions as $key => $value) {
                $this->query .= "WHERE {$this->getLeft()->getSchema()}.{$key} = {$this->getPivot()->getSchema()}.{$value}";
            }
        }
        return $this;
    }

    public function and(array $conditions = [])
    {
        if (is_array($conditions) && count($conditions) > 0) {
            foreach ($conditions as $key => $value) {
                if (count($conditions) > 0) {
                    $this->query .= " AND {$this->getRight()->getSchema()}.{$key} = {$this->getPivot()->getSchema()}.{$value}";
                }
            }
        }
        return $this;
    }

    public function limit(array $conditions = [])
    {
        if (is_array($conditions) && count($conditions) > 0) {
            foreach ($conditions as $key => $value) {
                $this->query .= " AND {$this->getLeft()->getSchema()}.{$key} = {$value}";
            }
        }
        return $this;

    }

    public function get($type = 'fetch')
    {
        return $this->getLeft()
            ->getRepo()
            ->getEm()
            ->getCrud()
            ->rawQuery($this->query, [], $type);
    }

    public function all()
    {
        return $this->get('fetch_all');
    }

    public function column()
    {
        return $this->get('column');
    }


    public function __toString()
    {
        return $this->query;
    }
}
