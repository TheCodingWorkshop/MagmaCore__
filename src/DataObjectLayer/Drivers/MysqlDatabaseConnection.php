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

namespace MagmaCore\DataObjectLayer\Drivers;

use PDO;
use PDOException;
use MagmaCore\DataObjectLayer\Exception\DataLayerException;
use MagmaCore\DataObjectLayer\Exception\DataLayerInvalidArgumentException;

class MysqlDatabaseConnection extends AbstractDatabaseDriver
{

    /** @var string $pdoDriver */
    protected string $pdoDriver;

    /** @var string $driver */
    protected const PDO_DRIVER = 'mysql';
    private object $environment;

    /**
     * Class constructor. piping the class properties to the constructor
     * method. The constructor will throw an exception if the database driver
     * doesn't match the class database driver.
     *
     * @param object $environment
     * @param string $pdoDriver
     * @return void
     */
    public function __construct(object $environment, string $pdoDriver)
    {
        $this->environment = $environment;
        $this->pdoDriver = $pdoDriver;
        if (self::PDO_DRIVER !== $this->pdoDriver) {
            throw new DataLayerInvalidArgumentException($pdoDriver . ' Invalid database driver pass requires ' . self::PDO_DRIVER . ' driver option to make your connection.');
        }
    }

    /**
     * Opens a new Mysql database connection
     *
     * @return PDO
     * @throws DataLayerException
     */
    public function open(): PDO
    {
        try {
            return new PDO(
                $this->environment->getDsn(),
                $this->environment->getDbUsername(),
                $this->environment->getDbPassword(),
                $this->params
            );
        } catch (PDOException $e) {
            throw new DataLayerException($e->getMessage(), (int)$e->getCode());
        }
    }
}
