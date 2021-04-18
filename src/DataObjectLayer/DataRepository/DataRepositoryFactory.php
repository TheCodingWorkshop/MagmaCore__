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

namespace MagmaCore\DataObjectLayer\DataRepository;

use MagmaCore\Utility\Yaml;
use MagmaCore\DataObjectLayer\DataLayerFactory;
use MagmaCore\DataObjectLayer\DataLayerEnvironment;
use MagmaCore\DataObjectLayer\DataLayerConfiguration;
use MagmaCore\DataObjectLayer\DataRepository\DataRepositoryInterface;
use MagmaCore\DataObjectLayer\Exception\DataLayerUnexpectedValueException;

class DataRepositoryFactory
{

    /** @var string */
    protected string $tableSchema;

    /** @var string */
    protected string $tableSchemaID;

    /** @var string */
    protected string $crudIdentifier;

    /**
     * Main class constructor
     *
     * @param string $crudIdentifier
     * @param string $tableSchema
     * @param string $tableSchemaID
     */
    public function __construct(string $crudIdentifier, string $tableSchema, string $tableSchemaID)
    {
        $this->crudIdentifier = $crudIdentifier;
        $this->tableSchema = $tableSchema;
        $this->tableSchemaID = $tableSchemaID;
    }

    /**
     * Create the DataRepository Object. Which is the middle layer that interacts with
     * the application using this framework. The data repository object will have 
     * the required dependency injected by default. Which is the data layer facade object
     * which is simple passing in the entity manager object which expose the crud methods
     *
     * @param string $dataRepositoryString
     * @param array|null $dataLayerConfiguration
     * @return DataRepositoryInterface
     * @throws DataLayerUnexpectedValueException
     */
    public function create(string $dataRepositoryString, ?array $dataLayerConfiguration = null) : DataRepositoryInterface
    {
        
        $dataRepositoryObject = new $dataRepositoryString($this->buildEntityManager($dataLayerConfiguration));
        if (!$dataRepositoryObject instanceof DataRepositoryInterface ) {
            throw new DataLayerUnexpectedValueException($dataRepositoryString . ' is not a valid repository object');
        }
        return $dataRepositoryObject;
    }

    /**
     * Build entity manager which creates the data layer factory and passing in the
     * environment configuration array and symfony dotenv component. Which is used 
     * to set the database environment config.
     *
     * @param array|null $dataLayerConfiguration
     * @return Object
     */
    public function buildEntityManager(?array $dataLayerConfiguration = null) : Object
    {
        $dataLayerEnvironment = new DataLayerEnvironment(
            new DataLayerConfiguration(
            \Symfony\Component\Dotenv\Dotenv::class,
            ($dataLayerConfiguration !==null) ? $dataLayerConfiguration : NULL,
            ),
            Yaml::file('app')['database']['default_driver'] /* second argument */

        );
        $factory = new DataLayerFactory($dataLayerEnvironment, $this->tableSchema, $this->tableSchemaID);
        if ($factory) {
            return $factory->dataEntityManagerObject();
        }

    }

}
