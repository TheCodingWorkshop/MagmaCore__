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

use MagmaCore\DataObjectLayer\Exception\DataLayerException;

final class DataLayerConfiguration
{

    /** @var array */
    protected ?array $dataLayerConfiguration = null;
    /** @var Object */
    protected Object $dotEnv;
    /** @var array */
    protected const SUPPORTED_DRIVERS = ['mysql', 'pgsql', 'sqlite'];
    /** @var string */
    protected const DEFAULT_DRIVER = 'mysql';

    /** */
    public function __construct(?string $dotEnvString = null, ?array $dataLayerConfiguration = null)
    {
        $this->dataLayerConfiguration = $dataLayerConfiguration;
        if ($dotEnvString !==null) {
            if (defined('ROOT_PATH')) {
                (new $dotEnvString())->load(ROOT_PATH . '/.env');
            } else {
                throw new DataLayerException('Cannot read .env file. Ensure you\'ve set the ROOT_PATH constant.');
            }
        }

    }

    /**
     * Returns an array of dataLayer database configurations. Various drivers are
     * supported. Please see supported drivers list. Values are feed from the document
     * root .env file if that file exists. and will load those environment values
     * else revert to the default settings which is setup for development environment
     * 
     * @return array
     */
    public function baseConfiguration() : array
    {

        $baseConfigurations = [

            'driver' => [

                'mysql' => [
                    'dsn'           => "mysql:host={$_ENV['DB_HOST']};dbname={$_ENV['DB_NAME']};charset={$_ENV['DB_CHARSET']}",
                    'host'          => $_ENV['DB_HOST'] ? $_ENV['DB_HOST'] : '127.1.1.0',
                    'database'      => $_ENV['DB_NAME'] ? $_ENV['DB_NAME'] : 'lavacms',
                    'username'      => $_ENV['DB_USER'] ? $_ENV['DB_USER'] : 'root',
                    'password'      => $_ENV['DB_PASSWORD'] ? $_ENV['DB_PASSWORD'] : '',
                    'port'          => $_ENV['DB_PORT'],
                    'charset'       => $_ENV['DB_CHARSET'],
                    'collate'       => $_ENV['DB_COLLATE'],
                    'prefix'        => $_ENV['DB_PREFIX'],
                    'engine'        => $_ENV['DB_ENGINE'],
                    'row_format'    => $_ENV['DB_FORMAT']
                ]
            ]

        ];
    
        if (is_array($this->dataLayerConfiguration) && ($this->dataLayerConfiguration !==null) && count($this->dataLayerConfiguration) > 0) {
            return $this->dataLayerConfiguration;
        } else {
            return $baseConfigurations;
        }
    }
}