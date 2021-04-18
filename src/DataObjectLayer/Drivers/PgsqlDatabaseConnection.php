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
use MagmaCore\DataObjectLayer\Drivers\AbstractDatabaseDriver;
use MagmaCore\DataObjectLayer\DatabaseConnection\DatabaseConnectionInterface;

class PgsqlDatabaseConnection extends AbstractDatabaseDriver
{

    /** @var DatabaseConnectionInterface $dbh */
    protected DatabaseConnectionInterface $dbh;
    /** @var string $driver */
    protected const PDO_DRIVER = 'pgsql';

    /**
     * Main class constructor
     *
     * @param DatabaseConnectionInterface $dbh
     */
    public function __construct(DatabaseConnectionInterface $dbh)
    {
        $this->dbh = $dbh;
        parent::__construct($dbh);
    }

    public function open()
    {
        try {
            $this->dbh = new PDO(
                $this->credential->getDsn($this->driver),
                $this->credential->getDbUsername(),
                $this->credential->getDbPassword(),
                $this->params
            );
        } catch(PDOException $expection) {
            throw new DataLayerException($expection->getMessage(), (int)$expection->getCode());
        }

        return $this->dbh;

    }

}