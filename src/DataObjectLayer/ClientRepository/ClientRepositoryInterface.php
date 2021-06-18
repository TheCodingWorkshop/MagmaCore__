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

namespace MagmaCore\DataObjectLayer\ClientRepository;

interface ClientRepositoryInterface
{

    /**
     * Client repository method which either updates or insert a record within the database
     * if a primary key is provided then the method will run in update mode else if the key
     * is omitted then it will assume you're adding a record to the database.
     *
     * @param array $fields
     * @param string|null $primaryKey
     * @return boolean
     */
    public function save(array $fields = [], ?string $primaryKey=null) : bool;

    /**
     * Drop and item from the database/storage. Based on the argument conditions
     *
     * @param array $condition
     * @return boolean
     */
    public function drop(array $condition) : bool;

    /**
     * Get all the results from the specified database or return specific results
     * based on the method argument. Multiple conditions can be set within the
     * argument array. ie array('selectors' => [], 'condition' => []). Omitting
     * either of the selector or condition key will simple just the default which 
     * is an empty array.
     *
     * @param array $conditions
     * @return array
     */
    public function get(array $conditions = []) : array;

    /**
     * Perform validation within the save method before insert or updating
     * a database record.
     *
     * @return void
     */
    public function validate() : void;


}