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

use MagmaCore\DataObjectLayer\Exception\DataLayerException;
use MagmaCore\DataObjectLayer\Drivers\DatabaseDriverInterface;
use MagmaCore\DataObjectLayer\Exception\DataLayerNoValueException;
use MagmaCore\DataObjectLayer\DatabaseTransaction\DatabaseTransaction;
use MagmaCore\DataObjectLayer\Exception\DataLayerInvalidArgumentException;
use MagmaCore\Base\Exception\BaseInvalidArgumentException;
use PDOStatement;
use PDO;

class DataMapper extends DatabaseTransaction implements DataMapperInterface
{

    /** @var DatabaseDriverInterface */
    private DatabaseDriverInterface $dbh;

    /** @var PDOStatement */
    private PDOStatement $statement;

    /**
     * Main constructor class
     *
     * @param DatabaseDriverInterface $dbh
     */
    public function __construct(DatabaseDriverInterface $dbh)
    {
        $this->dbh = $dbh;
        parent::__construct($this->dbh); /* Pass to DatabaseTransaction class */
    }

    /**
     * Check the incoming $value isn't empty else throw an exception
     * 
     * @param mixed $value
     * @param string|null $errorMessage
     * @return void
     */
    private function isEmpty(mixed $value, string $errorMessage = null)
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

    public function getConnection(): DatabaseDriverInterface
    {
        return $this->dbh;
    }

    public function getTables()
    {
        $db = $this->dbh->open();
        $stmt = $db->prepare('show tables');
        $stmt->execute();
        $results = [];
        while ($rows = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $results[] = $rows;
        }

        return $results;

    }

    /**
     * @inheritDoc
     */
    public function prepare(string $sqlQuery): self
    {
        $this->isEmpty($sqlQuery, 'Invalid or empty query string passed.');
        $this->statement = $this->dbh->open()->prepare($sqlQuery);
        return $this;
    }

    /**
     * @inheritDoc
     *
     * @param mixed $value
     * @return int
     */
    public function bind(mixed $value): int
    {
        return match ($value) {
            is_bool($value) => PDO::PARAM_BOOL,
            intval($value) => PDO::PARAM_INT,
            is_null($value) => PDO::PARAM_NULL,
            default => PDO::PARAM_STR,
        };
    }

    /**
     * @inheritDoc
     *
     * @param array $fields
     * @param boolean $isSearch
     * @return self
     */
    public function bindParameters(array $fields, bool $isSearch = false): self
    {
        $this->isArray($fields);
        if (is_array($fields)) {
            $type = ($isSearch === false) ? $this->bindValues($fields) : $this->bindSearchValues($fields);
            if ($type) {
                return $this;
            }
        }
    }

    /**
     * Binds a value to a corresponding name or question mark placeholder in the SQL
     * statement that was used to prepare the statement
     * 
     * @param array $fields
     * @return PDOStatement
     * @throws BaseInvalidArgumentException
     */
    protected function bindValues(array $fields): PDOStatement
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
    protected function bindSearchValues(array $fields): PDOStatement
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
     * @return bool
     */
    public function execute(): bool
    {
        if ($this->statement)
            return $this->statement->execute();
    }
    /**
     * @inheritDoc
     *
     * @return integer
     */
    public function numRows(): int
    {
        if ($this->statement)
            return $this->statement->rowCount();
    }
    /**
     * @inheritDoc
     *
     * @return Object
     */
    public function result(): Object
    {
        if ($this->statement)
            return $this->statement->fetch(PDO::FETCH_OBJ);
    }
    /**
     * @inheritDoc
     * @return array
     */
    public function results(): array
    {
        if ($this->statement)
            return $this->statement->fetchAll();
    }

    /**
     * @inheritDoc
     * @return mixed
     */
    public function column()
    {
        if ($this->statement)
            return $this->statement->fetchColumn();
    }

    public function columns(): array
    {
        if ($this->statement)
            return $this->statement->fetchAll(PDO::FETCH_COLUMN);
    }

    /**
     * @inheritDoc
     * @return integer
     */
    public function getLastId(): int
    {
        if ($this->dbh->open()) {
            $lastID = $this->dbh->open()->lastInsertId();
            if (!empty($lastID)) {
                return intval($lastID);
            }
        }
    }

    /**
     * Returns the query condition merged with the query parameters
     * 
     * @param array $conditions
     * @param array $parameters
     * @return array
     */
    public function buildQueryParameters(array $conditions = [], array $parameters = []): array
    {
        return (!empty($parameters) || (!empty($conditions)) ? array_merge($conditions, $parameters) : $parameters);
    }

    /**
     * Persist queries to database
     *
     * @param string $sqlQuery
     * @param array $parameters
     * @return void
     * @throws DataLayerException
     */
    public function persist(string $sqlQuery, array $parameters): void
    {
        $this->start();
        try {
            $this->prepare($sqlQuery)->bindParameters($parameters)->execute();
           $this->commit();
        } catch (PDOException $e) {
            $this->revert();
           throw new PDOException('Data persistant error ' . $e->getMessage());
        }

    }

    /**
     * Quickly execute commands through command line.
     *
     * @param string $statement
     * @return void
     * @throws DataLayerException
     */
    public function exec(string $statement): void
    {
        $this->start();
        try {
            $this->dbh->open()->exec($statement);
            $this->commit();
        } catch (DataLayerException $e) {
           $this->revert();
           throw new DataLayerException('error ' . $e->getMessage());
        }
        
    }

}
