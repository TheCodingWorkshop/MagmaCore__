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

use JetBrains\PhpStorm\Pure;

final class DataLayerConfiguration
{

    /** @var array|null */
    private ?array $dataLayerConfiguration;

    /**
     * Main class constructor
     *
     * @param string|null $dotEnvString
     * @param array|null $dataLayerConfiguration
     * @return void
     */
    public function __construct(?string $dotEnvString = null, ?array $dataLayerConfiguration = null)
    {
        $this->dataLayerConfiguration = $dataLayerConfiguration;
        if ($dotEnvString !== null) {
            (new $dotEnvString())->load(ROOT_PATH . '/.env');
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
    #[Pure] public function baseConfiguration(): array
    {
        if (is_array($this->dataLayerConfiguration) && ($this->dataLayerConfiguration !== null) && count($this->dataLayerConfiguration) > 0) {
            return $this->dataLayerConfiguration;
        }

        return [

            'driver' => [

                'mysql' => [
                    'dsn' => "mysql:host={$_ENV['DB_HOST']};dbname={$_ENV['DB_NAME']};charset={$_ENV['DB_CHARSET']}",
                    'username' => $this->dbUsername(),
                    'password' => $this->dbPassword()
                ],
                'pgsql' => [
                    'dsn' => "pgsql:host={$_ENV['DB_HOST']};dbname={$_ENV['DB_NAME']};charset={$_ENV['DB_CHARSET']}",
                    'username' => $this->dbUsername(),
                    'password' => $this->dbPassword()
                ],
                'sqlite' => [
                    'dsn' => "pgsql:host={$_ENV['DB_HOST']};dbname={$_ENV['DB_NAME']};charset={$_ENV['DB_CHARSET']}",
                    'username' => $this->dbUsername(),
                    'password' => $this->dbPassword()
                ]
            ]

        ];
    }

    /**
     * Return the database environment username
     *
     * @return string
     */
    public function dbUsername(): string
    {
        return $_ENV['DB_USER'] ?: 'root';
    }

    /**
     * return the database environment password
     *
     * @return string
     */
    public function dbPassword(): string
    {
        return $_ENV['DB_PASSWORD'] ?: '';
    }
}
