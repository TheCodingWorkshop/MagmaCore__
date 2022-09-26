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

use MagmaCore\DataObjectLayer\Exception\DataLayerException;
use MagmaCore\DataObjectLayer\Exception\DataLayerInvalidArgumentException;
use MagmaCore\DataObjectLayer\Exception\DataLayerUnexpectedValueException;
use MagmaCore\DataObjectLayer\DataMapper\DataMapper;
use MagmaCore\DataObjectLayer\QueryBuilder\QueryBuilder;
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

    private string $createQuery;
    private string $readQuery;
    private string $joinQuery;
    private string $updateQuery;
    private string $deleteQuery;
    private string $searchQuery;
    private string $rawQuery;

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
        return $this->tableSchema;
    }

    public function getMapping(): Object
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
        return $this->tableSchemaID;
    }

    /**
     * @inheritdoc
     *
     * @return integer
     * @throws Throwable
     */
    public function lastID(): int
    {
        return $this->dataMapper->getLastId();
    }

    /**
     * Undocumented function
     *
     * @param array $selectors
     * @param array $joinSelectors
     * @param string $joinTo
     * @param string $joinType
     * @param array $conditions
     * @param array $parameters
     * @param array $extras
     * @return array|null
     * @throws DataLayerException
     */
    public function join(
        array $selectors,
        array $joinSelectors,
        string $joinTo,
        string $joinType,
        array $conditions = [],
        array $parameters = [],
        array $extras = []
    ): ?array
    {

        $args = ['table' => $this->getSchema(), 'type' => 'join', 'selectors' => $selectors, 'join_to_selectors' => $joinSelectors, 'join_to' => $joinTo, 'join_type' => $joinType, 'conditions' => $conditions, 'params' => $parameters, 'extras' => $extras];
        $query = $this->queryBuilder->buildQuery($args)->joinQuery();
        $this->joinQuery = $query;
        $this->dataMapper->persist($query, $this->dataMapper->buildQueryParameters($conditions, $parameters));
        return ($this->dataMapper->numRows() >= 1) ? $this->dataMapper->results() : NULL;
    }

    /**
     * @inheritdoc
     *
     * @param array $fields
     * @return boolean
     * @throws DataLayerException
     */
    public function create(array $fields = []): bool
    {
        $args = ['table' => $this->getSchema(), 'type' => 'insert', 'fields' => $fields];
        $query = $this->queryBuilder->buildQuery($args)->insertQuery();
        $this->createQuery = $query;
        $this->dataMapper->persist($query, $this->dataMapper->buildQueryParameters($fields));
        return $this->dataMapper->numRows() == 1;
    }

    /**
     * @inheritdoc
     *
     * @param array $selectors
     * @param array $conditions
     * @param array $parameters
     * @param array $optional
     * @return array
     * @throws DataLayerException
     */
    public function read(array $selectors = [], array $conditions = [], array $parameters = [], array $optional = []): array
    {
        $args = ['table' => $this->getSchema(), 'type' => 'select', 'selectors' => $selectors, 'conditions' => $conditions, 'params' => $parameters, 'extras' => $optional];
        $query = $this->queryBuilder->buildQuery($args)->selectQuery();
        $this->readQuery = $query;
        $this->dataMapper->persist($query, $this->dataMapper->buildQueryParameters($conditions, $parameters));
        return ($this->dataMapper->numRows() >= 1) ? $this->dataMapper->results() : array();
    }

    /**
     * @inheritdoc
     *
     * @param array $fields
     * @param string $primaryKey
     * @return boolean
     * @throws DataLayerException
     *
     */
    public function update(array $fields, string $primaryKey): bool
    {
        $args = ['table' => $this->getSchema(), 'type' => 'update', 'fields' => $fields, 'primary_key' => $primaryKey];
        $query = $this->queryBuilder->buildQuery($args)->updateQuery();
        $this->updateQuery = $query;
        $this->dataMapper->persist($query, $this->dataMapper->buildQueryParameters($fields));
        return $this->dataMapper->numRows() == 1;
    }

    /**
     * @inheritdoc
     *
     * @param array $conditions
     * @return boolean
     * @throws DataLayerException
     */
    public function delete(array $conditions = []): bool
    {
        $args = ['table' => $this->getSchema(), 'type' => 'delete', 'conditions' => $conditions];
        $query = $this->queryBuilder->buildQuery($args)->deleteQuery();
        $this->deleteQuery = $query;
        $this->dataMapper->persist($query, $this->dataMapper->buildQueryParameters($conditions));
        return $this->dataMapper->numRows() == 1;
    }

    /**
     * @inheritdoc
     *
     * @param array $selectors
     * @param array $conditions
     * @return array
     * @throws DataLayerException
     */
    public function search(array $selectors = [], array $conditions = []): array
    {
        $args = ['table' => $this->getSchema(), 'type' => 'search', 'selectors' => $selectors, 'conditions' => $conditions];
        $query = $this->queryBuilder->buildQuery($args)->searchQuery();
        $this->searchQuery = $query;
        $this->dataMapper->persist($query, $this->dataMapper->buildQueryParameters($conditions), true);
        return ($this->dataMapper->numRows() >= 1) ? $this->dataMapper->results() : array();
    }

    /**
     * @inheritDoc
     *
     * @param array $selectors
     * @param array $conditions
     * @return Object|null
     * @throws DataLayerException
     */
    public function get(array $selectors = [], array $conditions = []): ?Object
    {
        $args = ['table' => $this->getSchema(), 'type' => 'select', 'selectors' => $selectors, 'conditions' => $conditions];
        $query = $this->queryBuilder->buildQuery($args)->selectQuery();
        $this->getQuery = $query;
        $this->dataMapper->persist($query, $this->dataMapper->buildQueryParameters($conditions));
        return ($this->dataMapper->numRows() >= 1) ? $this->dataMapper->result() : NULL;
    }

    /**
     * @inheritDoc
     * @return mixed
     * @throws Throwable
     */
    public function aggregate(string $type, ?string $field = 'id', array $conditions = []): mixed
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
     * @param string $rawQuery
     * @param array|null $conditions
     * @param string $resultType
     * @return mixed
     * @throws DataLayerException
     */
    public function rawQuery(string $rawQuery, ?array $conditions = [], string $resultType = 'column'): mixed
    {
        $args = ['table' => $this->getSchema(), 'type' => 'raw', 'conditions' => $conditions, 'raw' => $rawQuery];
        $query = $this->queryBuilder->buildQuery($args)->rawQuery();
        $this->rawQuery = $query;
        $this->dataMapper->persist($query, $this->dataMapper->buildQueryParameters($conditions));
        if ($this->dataMapper->numRows()) {
            if (!in_array($resultType, ['fetch', 'fetch_all', 'column', 'columns'])) {
                throw new DataLayerInvalidArgumentException('Invalid 3rd argument. Your options are "fetch, fetch_all or column"');
            }
            $data = match ($resultType) {
                'column' => $this->dataMapper->column(),
                'columns' => $this->dataMapper->columns(),
                'fetch' => $this->dataMapper->result(),
                'fetch_all' => $this->dataMapper->results(),
                default => throw new DataLayerUnexpectedValueException('Please choose a return type for this method ie. "fetch, fetch_all or column."'),
            };
            return $data;

        }
        return false;
    }

    /**
     * @param string $type
     * @return mixed
     */
    public function getQueryType(string $type)
    {
        $queryTypes = ['createQuery', 'readQuery', 'updateQuery', 'deleteQuery', 'joinQuery', 'searchQuery', 'rawQuery'];
        if (!empty($type)) {
            if (in_array($type, $queryTypes, true)) {
                return $this->$type;
            }
        }

    }

}
