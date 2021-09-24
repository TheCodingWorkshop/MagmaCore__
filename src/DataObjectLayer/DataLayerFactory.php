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

namespace MagmaCore\DataObjectLayer;

use MagmaCore\DataObjectLayer\DataMapper\DataMapperFactory;
use MagmaCore\DataObjectLayer\Drivers\DatabaseDriverFactory;
use MagmaCore\DataObjectLayer\EntityManager\EntityManagerFactory;
use MagmaCore\DataObjectLayer\QueryBuilder\QueryBuilderFactory;
use MagmaCore\DataObjectLayer\QueryBuilder\QueryBuilder;
use MagmaCore\DataObjectLayer\EntityManager\Crud;

class DataLayerFactory
{
    /** @var string */
    protected string $tableSchema;

    /** @var string */
    protected string $tableSchemaID;

    /** @var DataLayerEnvironment */
    protected DataLayerEnvironment $environment;

    /**
     * Main class constructor
     *
     * @param DataLayerEnvironment $environment
     * @param string $tableSchema
     * @param string $tableSchemaID
     */
    public function __construct(DataLayerEnvironment $environment, string $tableSchema, string $tableSchemaID)
    {
        $this->environment = $environment;
        $this->tableSchema = $tableSchema;
        $this->tableSchemaID = $tableSchemaID;
    }

    /**
     * build method which glues all the components together and inject the necessary 
     * dependency within the respective object
     *
     * @return Object
     */
    public function dataEntityManagerObject() : Object
    {
        /* build the data mapper factory object */
        $dataMapperFactory = new DataMapperFactory();
        $dataMapper = $dataMapperFactory->create(DatabaseDriverFactory::class, $this->environment);
        if ($dataMapper) {
            /* build the query builder factory object */
            $queryBuilderFactory = new QueryBuilderFactory();
            /* todo we will need to have a QueryBuilderDriverFactory::class which loads the relevant query based on the database driver selected */
            $queryBuilder = $queryBuilderFactory->create(QueryBuilder::class);
            if ($queryBuilder) {
                /* build the entity manager factory object */
                $entityManagerFactory = new EntityManagerFactory($dataMapper, $queryBuilder);
                return $entityManagerFactory->create(Crud::class, $this->tableSchema, $this->tableSchemaID);
            }
        }
    }

}
