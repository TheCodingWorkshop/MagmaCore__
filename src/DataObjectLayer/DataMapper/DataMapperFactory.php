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

namespace MagmaCore\DataObjectLayer\DataMapper;

use MagmaCore\DataObjectLayer\Exception\DataLayerUnexpectedValueException;
use MagmaCore\DataObjectLayer\DatabaseConnection\DatabaseConnectionInterface;
use MagmaCore\DataObjectLayer\DataLayerEnvironment;

class DataMapperFactory
{

    /**
     * Main constructor class
     * 
     * @return void
     */
    public function __construct()
    { }

    /**
     * Creates the data mapper object and inject the dependency for this object. We are also
     * creating the DatabaseConnection Object and injecting the environment object. Which will
     * expose the environment methods with the database connection class.
     *
     * @param string $databaseConnectionString
     * @param Object $dataMapperEnvironmentConfiguration
     * @return DataMapperInterface
     * @throws DataLayerUnexpectedValueException
     */
    public function create(string $databaseConnectionString, DataLayerEnvironment $environment) : DataMapperInterface
    {
        $databaseConnectionObject = new $databaseConnectionString($environment);
        if (!$databaseConnectionObject instanceof DatabaseConnectionInterface) {
            throw new DataLayerUnexpectedValueException($databaseConnectionString . ' is not a valid database connection object');
        }
        return new DataMapper($databaseConnectionObject);
    }


}
