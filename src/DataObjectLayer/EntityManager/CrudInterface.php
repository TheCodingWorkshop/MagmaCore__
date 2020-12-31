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

namespace MagmaCore\DataObjectLayer\EntityManager;

interface CrudInterface
{

    /**
     * Returns the storage schema name as string
     * 
     * @return string
     */
    public function getSchema(): string;

    /**
     * Returns the primary key for the storage schema
     * 
     * @return string
     */
    public function getSchemaID(): string;

    /**
     * Returns the last inserted ID
     * 
     * @return int
     */
    public function lastID(): int;

    /**
     * Create method which inserts data within a storage table
     * 
     * @param array $fields
     * @return bool
     */
    public function create(array $fields = []): bool;

    /**
     * Returns a an array of database rows based on the individual supplied arguments
     * 
     * @param array $selectors = []
     * @param array $conditions = []
     * @param array $parameters = []
     * @param array $optional = []
     * @return array
     */
    public function read(array $selectors = [], array $conditions = [], array $parameters = [], array $optional = []): array;

    /**
     * Update method which update 1 or more rows of data with in the storage table
     * 
     * @param array $fields
     * @param string $primaryKey
     * @return bool
     */
    public function update(array $fields = [], string $primaryKey): bool;

    /**
     * Delete method which will permanently delete a row from the storage table
     * 
     * @param array $conditions
     * @return bool
     */
    public function delete(array $conditions = []): bool;

    /**
     * Search method which returns queried search results
     * 
     * @param array $selectors = []
     * @param array $conditions = []
     * @return null|array
     */
    public function search(array $selectors = [], array $conditions = []): ?array;

    /**
     * Returns a custom query string. The second argument can assign and associate array
     * of conditions for the query string
     * 
     * @param string $rawQuery
     * @param array|null $conditions
     * @param string $resultType
     * @return mixed
     */
    public function rawQuery(string $rawQuery, ?array $conditions = [], string $resultType = 'column');

    /**
     * Returns a single table row as an object
     * 
     * @param array $selectors = []
     * @param array $conditions = []
     * @return null|Object
     */
    public function get(array $selectors = [], array $conditions = []): ?Object;

    /**
     * @param string $type
     * @param string $field
     * @param array|null $conditions
     * @return mixed
     */
    public function aggregate(string $type, ?string $field = 'id', array $conditions = []);

    /**
     * Returns the total number of records based on the method arguments
     * @param array $conditions
     * @param string|null $field
     * @return int
     */
    public function countRecords(array $conditions = [], ?string $field = 'id'): int;
}
