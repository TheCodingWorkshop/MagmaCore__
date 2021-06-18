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

namespace MagmaCore\DataObjectLayer\DataRepository;

use MagmaCore\DataObjectLayer\Exception\DataLayerInvalidArgumentException;

interface DataRepositoryInterface
{

    /**
     * Find and return an item by its ID
     * 
     * @param int $id
     * @return mixed
     */
    public function find(int $id) : array;

    /**
     * Find and return all table rows as an array
     * 
     * @return array
     */
    public function findAll() : array;

    /**
     * Find and return 1 or more rows by various argument which are optional by default
     * 
     * @param array $selectors = []
     * @param array $conditions = []
     * @param array $parameters = []
     * @param array $optional = []
     * @return array
     */
    public function findBy(array $selectors = [], array $conditions = [], array $parameters = [], array $optional = []) : array;

    /**
     * Find and Return 1 row by the method argument
     * 
     * @param array $conditions
     * @return array
     */
    public function findOneBy(array $conditions) : array;

    /**
     * Returns a single row from the storage table as an object
     * 
     * @param array $conditions
     * @param array $selectors
     * @return Object|null
     */
    public function findObjectBy(array $conditions = [], array $selectors = []) : ?Object;

    /**
     * Returns the search results based on the user search conditions and parameters
     * 
     * @param array $selectors = []
     * @param array $conditions = []
     * @param array $parameters = []
     * @param array $optional = []
     * @return array
     * @throws DataLayerInvalidArgumentException
     */
    public function findBySearch(array $selectors = [], array $conditions = [], array $parameters = [], array $optional = []) : array;

    /**
     * Delete bulk item from a database table by simple providing an array of IDs to
     * which you want to delete.
     *
     * @param array $items
     * @return boolean
     */
    public function findAndDelete(array $items = []) : bool;

    /**
     * Find and delete a row by its ID from storage device
     *
     * @param array $conditions
     * @return bool
     */
    public function findByIdAndDelete(array $conditions) : bool;

    /**
     * Update the queried row and return true on success. We can use the second argument
     * to specify which column to update by within the where clause
     * 
     * @param array $fields
     * @param int id
     * @return bool
     */
    public function findByIdAndUpdate(array $fields, int $id) : bool;

    /**
     * Returns the storage data as an array along with formatted paginated results. This method
     * will also returns queried search results
     *
     * @param Object $request
     * @param array $args
     * @param array $relationship
     * @return array|false
     */
    public function findWithSearchAndPaging(Object $request, array $args = [], array $relationship = []) : array|false;

    /**
     * Find and item by its ID and return the object row else return 404 with the or404 chaining method
     * 
     * @param int $id
     * @param array $selectors
     * @return self
     */
    public function findAndReturn(int $id, array $selectors = []) : self;

    /**
     * Returns 404 error page if the findAndReturn method or property returns empty or null
     *
     * @return Object|null
     */
    public function or404(): ?object;

}
