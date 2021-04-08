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

namespace MagmaCore\DataObjectLayer\EntityManager;

use MagmaCore\DataObjectLayer\Exception\DataLayerInvalidArgumentException;
use MagmaCore\DataObjectLayer\Exception\DataLayerUnexpectedValueException;
use MagmaCore\DataObjectLayer\DataMapper\DataMapper;
use MagmaCore\DataObjectLayer\QueryBuilder\QueryBuilder;
use MagmaCore\DataObjectLayer\EntityManager\CrudInterface;
use Throwable;

class Crud implements CrudInterface
{

    /** @var DataMapper */
    protected DataMapper $dataMapper;

    /** @var QueryBuilder */
    protected QueryBuilder $queryBuilder;

    /** @var string */
    protected string $tableSchema;

    /** @var string */
    protected string $tableSchemaID;

    /**
     * Main constructor
     *
     * @param DataMapper $dataMapper
     * @param QueryBuilder $queryBuilder
     * @param string $tableSchema
     * @param string $tableSchemaID
     */
    public function __construct(DataMapper $dataMapper, QueryBuilder $queryBuilder, string $tableSchema, string $tableSchemaID)
    {
        $this->dataMapper = $dataMapper;
        $this->queryBuilder = $queryBuilder;
        $this->tableSchema = $tableSchema;
        $this->tableSchemaID = $tableSchemaID;
    }

    /**
     * @inheritdoc
     *
     * @return string
     */
    public function getSchema(): string
    {
        return (string)$this->tableSchema;
    }

    public function getMapping() : Object
    {
        return $this->dataMapper;
    }

    /**
     * @inheritdoc
     *
     * @return string
     */
    public function getSchemaID(): string
    {
        return (string)$this->tableSchemaID;
    }

    /**
     * @inheritdoc
     *
     * @return integer
     */
    public function lastID(): int
    {
        return $this->dataMapper->getLastId();
    }

    /**
     * @inheritdoc
     *
     * @param array $fields
     * @return boolean
     */
    public function create(array $fields = []): bool
    {
        $args = ['table' => $this->getSchema(), 'type' => 'insert', 'fields' => $fields];
        $query = $this->queryBuilder->buildQuery($args)->insertQuery();
        $this->dataMapper->persist($query, $this->dataMapper->buildQueryParameters($fields));
        return ($this->dataMapper->numRows() == 1) ? true : false;
    }

    /**
     * @inheritdoc
     *
     * @param array $selectors
     * @param array $conditions
     * @param array $parameters
     * @param array $optional
     * @return array
     */
    public function read(array $selectors = [], array $conditions = [], array $parameters = [], array $optional = []): array
    {
        $args = ['table' => $this->getSchema(), 'type' => 'select', 'selectors' => $selectors, 'conditions' => $conditions, 'params' => $parameters, 'extras' => $optional];
        $query = $this->queryBuilder->buildQuery($args)->selectQuery();
        $this->dataMapper->persist($query, $this->dataMapper->buildQueryParameters($conditions, $parameters));
        return ($this->dataMapper->numRows() >= 1) ? $this->dataMapper->results() : array();
    }

    /**
     * @inheritdoc
     *
     * @param array $fields
     * @param string $primaryKey
     * @return boolean
     */
    public function update(array $fields = [], string $primaryKey): bool
    {
        $args = ['table' => $this->getSchema(), 'type' => 'update', 'fields' => $fields, 'primary_key' => $primaryKey];
        $query = $this->queryBuilder->buildQuery($args)->updateQuery();
        $this->dataMapper->persist($query, $this->dataMapper->buildQueryParameters($fields));
        return ($this->dataMapper->numRows() == 1) ? true : false;
    }

    /**
     * @inheritdoc
     *
     * @param array $conditions
     * @return boolean
     */
    public function delete(array $conditions = []): bool
    {
        $args = ['table' => $this->getSchema(), 'type' => 'delete', 'conditions' => $conditions];
        $query = $this->queryBuilder->buildQuery($args)->deleteQuery();
        $this->dataMapper->persist($query, $this->dataMapper->buildQueryParameters($conditions));
        return ($this->dataMapper->numRows() == 1) ? true : false;
    }

    /**
     * @inheritdoc
     *
     * @param array $selectors
     * @param array $conditions
     * @return array
     */
    public function search(array $selectors = [], array $conditions = []): array
    {
        $args = ['table' => $this->getSchema(), 'type' => 'search', 'selectors' => $selectors, 'conditions' => $conditions];
        $query = $this->queryBuilder->buildQuery($args)->searchQuery();
        $this->dataMapper->persist($query, $this->dataMapper->buildQueryParameters($conditions));
        return ($this->dataMapper->numRows() >= 1) ? $this->dataMapper->results() : array();
    }

    /**
     * @inheritDoc
     *
     * @param array $selectors
     * @param array $conditions
     * @return Object|null
     */
    public function get(array $selectors = [], array $conditions = []): ?Object
    {
        $args = ['table' => $this->getSchema(), 'type' => 'select', 'selectors' => $selectors, 'conditions' => $conditions];
        $query = $this->queryBuilder->buildQuery($args)->selectQuery();
        $this->dataMapper->persist($query, $this->dataMapper->buildQueryParameters($conditions));
        return ($this->dataMapper->numRows() >= 1) ? $this->dataMapper->result() : NULL;
    }

    /**
     * @inheritDoc
     * @throws Throwable
     */
    public function aggregate(string $type, ?string $field = 'id', array $conditions = [])
    {
        $args = [
            'table' => $this->getSchema(), 'primary_key' => $this->getSchemaID(),
            'type' => 'select', 'aggregate' => $type, 'aggregate_field' => $field,
            'conditions' => $conditions
        ];

        $query = $this->queryBuilder->buildQuery($args)->selectQuery();
        $this->dataMapper->persist($query, $this->dataMapper->buildQueryParameters($conditions));
        if ($this->dataMapper->numRows() > 0)
            return $this->dataMapper->column();
    }

    /**
     * @inheritDoc
     * @throws Throwable
     */
    public function countRecords(array $conditions = [], ?string $field = 'id'): int
    {
        if ($this->getSchemaID() != '') {
            return empty($conditions) ? $this->aggregate('count', $this->getSchemaID()) : $this->aggregate('count', $this->getSchemaID(), $conditions);
        }
    }

    /**
     * @inheritDoc
     *
     * @param string $sqlQuery
     * @param array|null $conditions
     * @param string $resultType
     * @return void
     */
    public function rawQuery(string $sqlQuery, ?array $conditions = [], string $resultType = 'column')
    {
        $args = ['table' => $this->getSchema(), 'type' => 'raw', 'conditions' => $conditions, 'raw' => $sqlQuery];
        $query = $this->queryBuilder->buildQuery($args)->rawQuery();
        $this->dataMapper->persist($query, $this->dataMapper->buildQueryParameters($conditions));
        if ($this->dataMapper->numRows()) {
            if (!in_array($resultType, ['fetch', 'fetch_all', 'column', 'columns'])) {
                throw new DataLayerInvalidArgumentException('Invalid 3rd argument. Your options are "fetch, fetch_all or column"');
            }
            switch ($resultType) {
                case 'column':
                    $data = $this->dataMapper->column();
                    break;
                case 'columns':
                    $data = $this->dataMapper->columns();
                    break;    
                case 'fetch':
                    $data = $this->dataMapper->result();
                    break;
                case 'fetch_all':
                    $data = $this->dataMapper->results();
                    break;
                default:
                    throw new DataLayerUnexpectedValueException('Please choose a return type for this method ie. "fetch, fetch_all or column."');
                    break;
            }
            if ($data) {
                return $data;
            }
        }
    }
}
