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

namespace MagmaCore\DataObjectLayer\DatabaseConnection;

use MagmaCore\DataObjectLayer\DatabaseConnection\DatabaseTransactionInterface;
use MagmaCore\DataObjectLayer\DatabaseConnection\DatabaseConnection;
use LogicException;
use PDOException;

class DatabaseTransaction implements DatabaseTransactionInterface
{

    private DatabaseConnection $db;
    private int $transactionCounter = 0;

    /**
     * Main class constructor method which accepts the database connection object
     * which is then pipe to the class property (db)
     *
     * @param DatabaseConnection $db
     * @return void
     * @throws LogicException - if there's no database connection object
     */
    public function __construct(DatabaseConnection $db)
    {
        $this->db = $db;
        if (!$this->db) {
            throw new LogicException('No Database connection was detected.');
        }
    }

    /**
     * @inheritdoc
     * @return bool true on success or false on failure
     * @throws PDOException - if a transaction as already started or the driver does not support transaction
     */
    public function start() : bool
    {
        if ($this->db) {
            if (!$this->transactionCounter++) {
                return $this->db->open()->beginTransaction();
            }
            return $this->db->open()->beginTransaction();
        }
    }

    /**
     * @inheritdoc
     * @return bool true on success or false on failure
     * @throws PDOException - If theres no active transaction
     */
    public function commit () : bool
    {
        if ($this->db) {
            if (!$this->transactionCounter) {
                return $this->db->open()->commit();
            }
            return $this->transactionCounter >= 0;
        }
    }

    /**
     * @inheritdoc
     * @return bool true on success or false on failure
     * @throws PDOException - If theres no active transaction
     */
    public function revert() : bool
    {
        if ($this->db) {
            if ($this->transactionCounter >= 0) {
                $this->transactionCounter = 0;
                return $this->db->open()->rollBack();
            }
            $this->transactionCounter = 0;
            return false;
        }
    }

}