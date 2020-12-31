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

interface DataMapperInterface
{

    /**
     * Prepare the query string
     * 
     * @param string $sqlQuery
     * @return self
     */
    public function prepare(string $sqlQuery) : self;

    /**
     * Explicit dat type for the parameter usinmg the PDO::PARAM_* constants.
     * 
     * @param mixed $value
     * @return mixed
     */
    public function bind($value);

    /**
     * Combination method which combines both methods above. One of which  is
     * optimized for binding search queries. Once the second argument $type
     * is set to search
     * 
     * @param array $fields
     * @param bool $isSearch
     * @return mixed
     */
    public function bindParameters(array $fields, bool $isSearch = false) : self;

    /**
     * returns the number of rows affected by a DELETE, INSERT, or UPDATE statement.
     * 
     * @return int|null
     */
    public function numRows() : int;

    /**
     * Execute function which will execute the prepared statement
     * 
     * @return void
     */
    public function execute();

    /**
     * Returns a single database row as an object
     * 
     * @return Object
     */
    public function result() : Object;

    /**
     * Returns all the rows within the database as an array
     * 
     * @return array
     */
    public function results() : array;

    /**
     * Returns a database column
     * 
     * @return array
     */
    public function column();

    /**
     * Returns the last inserted row ID from database table
     * 
     * @return int
     * @throws Throwable
     */
    public function getLastId() : int;


}
