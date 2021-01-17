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

use MagmaCore\DataObjectLayer\DatabaseConnection\DatabaseConnection;
use MagmaCore\DataObjectLayer\Exception\DataLayerInvalidArgumentException;
use MagmaCore\DataObjectLayer\Exception\DataLayerUnexpectedValueException;
use MagmaCore\DataObjectLayer\Exception\DataLayerNoValueException;
use MagmaCore\DataObjectLayer\Exception\DataLayerException;
use MagmaCore\DataObjectLayer\DatabaseConnection\DatabaseConnectionInterface;
use MagmaCore\DataObjectLayer\DatabaseConnection\DatabaseTransaction;

use PDOStatement;
use Throwable;
use PDO;
use PDOException;

class DataMapper extends DatabaseTransaction implements DataMapperInterface
{

    /** @var DatabaseConnectionInterface */
    private DatabaseConnectionInterface $dbh;

    /** @var PDOStatement */
    private PDOStatement $statement;

    /**
     * Main constructor class
     * 
     * @param DatabaseConnectionInterface
     * @return void
     */
    public function __construct(DatabaseConnectionInterface $dbh)
    {
        $this->dbh = $dbh;
        parent::__construct($this->dbh); /* Pass to DatabaseTransaction class */
    }

    /**
     * Check the incoming $valis isn't empty else throw an exception
     * 
     * @param mixed $value
     * @param string|null $errorMessage
     * @return void
     * @throws DataMapperException
     */
    private function isEmpty($value, string $errorMessage = null)
    {
        if (empty($value)) {
            throw new DataLayerNoValueException($errorMessage);
        }
    }

    /**
     * Check the incoming argument $value is an array else throw an exception
     * 
     * @param array $value
     * @return void
     * @throws BaseInvalidArgumentException
     */
    private function isArray(array $value)
    {
        if (!is_array($value)) {
            throw new DataLayerInvalidArgumentException('Your argument needs to be an array');
        }
    }

    /**
     * @inheritDoc
     */
    public function prepare(string $sqlQuery) : self
    {
        $this->isEmpty($sqlQuery, 'Invalid or empty query string passed.');
        $this->statement = $this->dbh->open()->prepare($sqlQuery);
        return $this;
    }

    /**
     * @inheritDoc
     *
     * @param [type] $value
     * @return void
     */
    public function bind($value)
    {
        try {
            switch($value) {
                case is_bool($value) :
                case intval($value) :
                    $dataType = PDO::PARAM_INT;
                    break;
                case is_null($value) :
                    $dataType = PDO::PARAM_NULL;
                    break;
                default :
                    $dataType = PDO::PARAM_STR;
                    break;
            }
            return $dataType;
        } catch(DataLayerException $exception) {
            throw $exception;
        }
    }

    /**
     * @inheritDoc
     *
     * @param array $fields
     * @param boolean $isSearch
     * @return self
     */
    public function bindParameters(array $fields, bool $isSearch = false) : self
    {
        $this->isArray($fields);
        if (is_array($fields)) {
            $type = ($isSearch === false) ? $this->bindValues($fields) : $this->bindSearchValues($fields);
            if ($type) {
                return $this;
            }
        }
        return false;
    }

    /**
     * Binds a value to a corresponding name or question mark placeholder in the SQL
     * statement that was used to prepare the statement
     * 
     * @param array $fields
     * @return PDOStatement
     * @throws BaseInvalidArgumentException
     */
    protected function bindValues(array $fields) : PDOStatement
    {
        $this->isArray($fields); // don't need
        foreach ($fields as $key => $value) {
            $this->statement->bindValue(':' . $key, $value, $this->bind($value));
        }
        return $this->statement;
    }

    /**
     * Binds a value to a corresponding name or question mark placeholder
     * in the SQL statement that was used to prepare the statement. Similar to
     * above but optimised for search queries
     * 
     * @param array $fields
     * @return mixed
     * @throws BaseInvalidArgumentException
     */
    protected function bindSearchValues(array $fields) :  PDOStatement
    {
        $this->isArray($fields); // don't need
        foreach ($fields as $key => $value) {
            $this->statement->bindValue(':' . $key,  '%' . $value . '%', $this->bind($value));
        }
        return $this->statement;
    }

    /**
     * @inheritDoc
     *
     * @return void
     */
    public function execute()
    {
        if ($this->statement) 
            return $this->statement->execute();
    }
    /**
     * @inheritDoc
     *
     * @return integer
     */
    public function numRows() : int
    {
        if ($this->statement) return $this->statement->rowCount();
    }
    /**
     * @inheritDoc
     *
     * @return Object
     */
    public function result() : Object
    {
        try {
            if ($this->statement) return $this->statement->fetch(PDO::FETCH_OBJ);
        } catch(DataLayerUnexpectedValueException $ex) {
            throw $ex->getMessage();
        }
    }
    /**
     * @inheritDoc
     * @return array
     */
    public function results() : array
    {
        if ($this->statement) return $this->statement->fetchAll();
    }

    /**
     * @inheritDoc
     * @return mixed
     */
    public function column()
    {
        if ($this->statement) return $this->statement->fetchColumn();
    }

    /**
     * @inheritDoc
     * @return integer
     */
    public function getLastId() : int
    {
        try {
            if ($this->dbh->open()) {
                $lastID = $this->dbh->open()->lastInsertId();
                if (!empty($lastID)) {
                    return intval($lastID);
                }
            }
        }catch(Throwable $throwable) {
            throw $throwable;
        }
    }

    /**
     * Returns the query condition merged with the query parameters
     * 
     * @param array $conditions
     * @param array $parameters
     * @return array
     */
    public function buildQueryParameters(array $conditions = [], array $parameters = []) : array
    {
        return (!empty($parameters) || (!empty($conditions)) ? array_merge($conditions, $parameters) : $parameters);
    }

    /**
     * Persist queries to database
     * 
     * @param string $query
     * @param array $parameters
     * @return mixed
     * @throws Throwable
     */
    public function persist(string $sqlQuery, array $parameters)
    {
        try {
            $this->start();
            $this->prepare($sqlQuery)->bindParameters($parameters)->execute();
            $this->commit();
        } catch(PDOException $pdoex){
            $this->revert();
            throw $pdoex;
        }
    }
}
