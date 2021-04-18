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

use MagmaCore\DataObjectLayer\Exception\DataLayerInvalidArgumentException;

class DataLayerEnvironment
{
    
    /** @var Object */
    protected Object $environmentConfiguration;

    /**
     * Main construct class
     * 
     * @param array $credentials
     * @return void
     */
    public function __construct(Object $environmentConfiguration, ?string $defaultDriver = null)
    {
        $this->environmentConfiguration = $environmentConfiguration;
        if (empty($defaultDriver) || is_null($defaultDriver)) {
            throw new DataLayerInvalidArgumentException('Please specify your default database driver within the app.yml file under the database settings');
        }
        $this->currentDriver  = $defaultDriver;
    }

    /**
     * Returns the base configuration as an array
     * 
     * @return array
     */
    public function getConfig() : array
    {
        return $this->environmentConfiguration->baseConfiguration();
    }

    /**
     * Get the user defined database connection array
     * 
     * @param string $driver
     * @return array
     * @throws DataLayerInvalidArgumentException
     */
    public function getDatabaseCredentials() : array
    {
        $connectionArray = [];
        foreach ($this->getConfig() as $credential) {    
            if (!array_key_exists($this->currentDriver, $credential)) {
                throw new DataLayerInvalidArgumentException('Unsupported database driver. ' . $this->currentDriver);
            } else {
                $connectionArray = $credential[$this->currentDriver];
            }
        }
        return $connectionArray;
    }

    /**
     * Returns the currently selected database dsn connection string
     * 
     * @return string
     */
    public function getDsn() : string
    {
        return $this->getDatabaseCredentials()['dsn'];
    }

    /**
     * Returns the currently selected database username from the connection string
     * 
     * @return string
     */
    public function getDbUsername() : string
    {
        return $this->getDatabaseCredentials()['username'];
    }

    /**
     * Returns the currently selected database password from the connection string
     * 
     * @return string
     */
    public function getDbPassword() : string
    {
        return $this->getDatabaseCredentials()['password'];
    }


}
