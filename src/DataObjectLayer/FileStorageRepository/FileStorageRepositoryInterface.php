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

namespace MagmaCore\DataObjectLayer\FileStorageRepository;

interface FileStorageRepositoryInterface
{

    /**
     * @param array $fields
     * @return bool
     */
    public function persist(array $fields);

    /**
     * Returns all the results from a file
     *
     * @param string $sort
     * @param int $limit
     * @return array|object
     */
    public function findAll(string $sort = 'datetime', int $limit = 10);

    /**
     * Returns results based on a particular condition. Conditions are define as 
     * arguments in the method
     * 
     * @param string $key
     * @param string $operator - options are (=, ==, !=, !==, <, >)
     * @param mixed $value- could be any data type
     * @param string $sort
     * @param int $limit
     * @return array|object
     */
    public function findBy(
        string $key, 
        string $operator, 
        $value, 
        string $sort = 'created_at', 
        int $limit = 10
    );
    
    /**
     * Returns the first record within a file
     * 
     * @return array|object
     */
    public function findFirst();

    /**
     * Skip the first $skip amount of records, then return the next $limit of records
     *
     * @param int $skip
     * @param int $limit
     * @return array|object
     */
    public function findSkip($skip = 5, $limit = 10);

    /**
     * @param array $fields
     * @param string $key
     * @param string $operator - options are (=, ==, !=, !==, <, >)
     * @param mixed $value- could be any data type
     * @return bool
     */
    public function save(array $fields, string $key, string $operator, $value);

    /**
     * @param string $key
     * @param string $operator - options are (=, ==, !=, !==, <, >)
     * @param mixed $value- could be any data type
     * @return bool
     */
    public function delete(string $key, string $operator, $value);

    /**
     * @param array $args
     * @param $request
     */
    public function findPaperResultsAndPaginate(array $args, $request);

    /**
     * Return the request query string. We use this to query the database
     * for more specific results based on the status column
     *
     * @param object $request - Symfony http-foundation
     * @param $query
     * @return array
     */
    public function totalQueryStatusCount($request, $query);

    /**
     * Returns the amount of data within the file
     * 
     * @return int
     */
    public function numRows();

    /**
     * Returns the amount of data within the file
     *
     * @param string $key
     * @param string $operator
     * @param $value
     * @return int
     */
    public function numRowsWith(string $key, string $operator, $value);


}