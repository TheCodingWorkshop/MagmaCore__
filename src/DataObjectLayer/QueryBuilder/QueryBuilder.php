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

namespace MagmaCore\DataObjectLayer\QueryBuilder;

use MagmaCore\DataObjectLayer\Exception\DataLayerInvalidArgumentException;

class QueryBuilder extends AbstractQueryBuilder
{

    /**
     * Main constructor class
     * 
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function buildQuery(array $args = []): self
    {
        if (count($args) < 0) {
            throw new DataLayerInvalidArgumentException('Your BuildQuery method has no defined argument. Please fix this');
        }
        $arg = array_merge(self::SQL_DEFAULT, $args);
        $this->key = $arg;
        return $this;
    }

    public function insertQuery(): string
    {
        if ($this->isQueryTypeValid('insert')) {
            if (is_array($this->key['fields']) && count($this->key['fields']) > 0) {
                $index = array_keys($this->key['fields']);
                $value = array(implode(', ', $index), ":" . implode(', :', $index));
                $this->sqlQuery = "INSERT INTO {$this->key['table']} ({$value[0]}) VALUES({$value[1]})";
                return $this->sqlQuery;
            }
        }
    }

    public function selectQuery(): string
    {
        if ($this->isQueryTypeValid('select')) {
            $selectors = (!empty($this->key['selectors'])) ? implode(", ", $this->key['selectors']) : '*';
            if (isset($this->key['aggregate']) && $this->key['aggregate']) {
                $this->sqlQuery = "SELECT {$this->key['aggregate']}({$this->key['aggregate_field']}) FROM {$this->key['table']}";
            } else {
                $this->sqlQuery = "SELECT {$selectors} FROM {$this->key['table']}";
                // if (array_key_exists('extras', $this->key)) {
                //     $this->sqlQuery .= $this->orderByQuery();
                // }
            }
            $this->sqlQuery = $this->hasConditions();
            return $this->sqlQuery;
        }
    }

    public function joinQuery(): string
    {
        $selectors = $joinSelectors = '';
        if ($this->isQueryTypeValid('join')) {
            if (
                isset($this->key['selectors']) &&
                count($this->key['selectors']) > 0
            ) {
                $selectors = implode(', ', $this->aliasSelectors($this->key['table'], $this->key['selectors']));
            }
            if (
                isset($this->key['join_to_selectors']) &&
                count($this->key['join_to_selectors']) > 0
            ) {
                $joinSelectors = implode(', ', $this->aliasSelectors('', $this->key['join_to_selectors']));
            }
            $this->sqlQuery = "SELECT 
            {$selectors}
            AS
            {$this->key['table']},
            " . str_replace('.', '', $joinSelectors) . "
            AS
            {$this->key['join_to']}
            FROM
            {$this->key['table']},
            {$this->key['join_to']},
            user_role
            WHERE 
            users.id = user_role.user_id
            AND roles.id = user_role.role_id
            ";
            $this->sqlQuery = $this->hasConditions();

            return $this->sqlQuery;
        }

    }


    public function updateQuery(): string
    {
        if ($this->isQueryTypeValid('update')) {
            if (is_array($this->key['fields']) && count($this->key['fields']) > 0) {
                $values = '';
                foreach (array_keys($this->key['fields']) as $field) {
                    if ($field !== $this->key['primary_key']) {
                        $values .= $field . " = :" . $field . ", ";
                    }
                }
                $values = substr_replace($values, '', -2);
                if (count($this->key['fields']) > 0) {
                    $this->sqlQuery = "UPDATE {$this->key['table']} SET {$values} WHERE {$this->key['primary_key']} = :{$this->key['primary_key']} LIMIT 1";
                    if (isset($this->key['primary_key']) && $this->key['primary_key'] === '0') {
                        unset($this->key['primary_key']);
                        $this->sqlQuery = "UPDATE {$this->key['table']} SET {$values}";
                    }
                }
                return $this->sqlQuery;
            }
        }
    }

    public function deleteQuery(): string
    {
        if ($this->isQueryTypeValid('delete')) {
            $index = array_keys($this->key['conditions']);
            $this->sqlQuery = "DELETE FROM {$this->key['table']} WHERE {$index[0]} = :{$index[0]}";
            if (isset($this->key['conditions']) && count($this->key['conditions']) > 1) {
                $this->sqlQuery .= " AND {$index[1]} = :{$index[1]}";
            }
            $this->sqlQuery .= ' LIMIT 1';
//            $bulkDelete = array_values($this->key['fields']);
//            if (is_array($bulkDelete) && count($bulkDelete) > 1) {
//                for ($i = 0; $i < count($bulkDelete); $i++) {
//                    $this->sqlQuery = "DELETE FROM {$this->key['table']} WHERE {$index[0]} = :{$index[0]}";
//                }
//            }

            return $this->sqlQuery;
        }
    }

    public function searchQuery(): string
    {
        if ($this->isQueryTypeValid('search')) {
            if (is_array($this->key['selectors']) && $this->key['selectors'] != '') {
                $this->sqlQuery = "SELECT * FROM {$this->key['table']} WHERE ";
                if ($this->has('selectors')) {
                    $values = [];
                    foreach ($this->key['selectors'] as $selector) {
                        $values[] = $selector . " LIKE " . ":{$selector}";
                    }
                    if (count($this->key['selectors']) >= 1) {
                        $this->sqlQuery .= implode(" OR ", $values);
                    }
                }
                $this->sqlQuery .= $this->orderByQuery();
                $this->sqlQuery .= $this->queryOffset();
            }
            return $this->sqlQuery;
        }
    }

    public function rawQuery(): string
    {
        if ($this->isQueryTypeValid('raw')) {
            $this->sqlQuery = $this->key['raw'];
            return $this->sqlQuery;
        }
    }

    private function hasConditions(): string
    {
        if (isset($this->key['conditions']) && $this->key['conditions'] != '') {
            if (is_array($this->key['conditions'])) {
                $sort = [];
                foreach (array_keys($this->key['conditions']) as $where) {
                    if (isset($where) && $where != '') {
                        $sort[] = $where . " = :" . $where;
                    }
                }
                if (count($this->key['conditions']) > 0) {
                    $this->sqlQuery .= " WHERE " . implode(" AND ", $sort);
                }
            }
        } else if (empty($this->key['conditions'])) {
            $this->sqlQuery = " WHERE 1";
        }
        $this->sqlQuery .= $this->orderByQuery();
        $this->sqlQuery .= $this->queryOffset();
        

        return $this->sqlQuery;
    }
}
