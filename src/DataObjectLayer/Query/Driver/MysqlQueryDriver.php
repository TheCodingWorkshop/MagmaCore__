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

namespace MagmaCore\DataObjectLayer\Query\Driver;

class MysqlQueryDriver extends AbstractQueryBuilderDriver
{

    public function __construct(array $databaseProps = [])
    {
        parent::__construct($databaseProps);     
    }


    public function insertQueryDriver(): self
    {
        if ($this->isQueryTypeValid('insert')) {
            if (is_array($this->key['fields']) && count($this->key['fields']) > 0) {
                $index = array_keys($this->key['fields']);
                $value = array(implode(', ', $index), ":" . implode(', :', $index));
                $this->sqlQuery = "INSERT INTO {$this->key['table']} ({$value[0]}) VALUES({$value[1]})";

                return $this;
            }
        }
    }

    public function selectQueryDriver(): self
    {
        if ($this->isQueryTypeValid('select')) {
            $selectors = (!empty($this->key['selectors'])) ? implode(", ", $this->key['selectors']) : '*';
            if (isset($this->key['aggregate']) && $this->key['aggregate']) {
                $this->sqlQuery = "SELECT {$this->key['aggregate']}({$this->key['aggregate_field']}) FROM {$this->key['table']}";
            } else {
                $this->sqlQuery = "SELECT {$selectors} FROM {$this->key['table']}";
            }

            return $this;
        }
    }

    public function updateQueryDriver(): self
    {
        if ($this->isQueryTypeValid('update')) {
            if (is_array($this->key['fields']) && $this->hasElements($this->key['fields'])) {
                $values = '';
                foreach (array_keys($this->key['fields']) as $field) {
                    if ($field !== $this->key['primary_key']) {
                        $values .= $field . " = :" . $field . ", ";
                    }
                }

                $values = substr_replace($values, '', -2);
                if ($this->hasElements($this->key['fields'])) {
                    $this->sqlQuery = "UPDATE {$this->key['table']} SET {$values} WHERE {$this->key['primary_key']} = :{$this->key['primary_key']} LIMIT 1";
                    if (isset($this->key['primary_key']) && $this->key['primary_key'] === '0') {
                        unset($this->key['primary_key']);
                        $this->sqlQuery = "UPDATE {$this->key['table']} SET {$values}";
                    }
                }

                return $this;
            }
        }
    }

    public function deleteQueryDriver(): self
    {
        if ($this->isQueryTypeValid('delete')) {
            $index = array_keys($this->key['conditions']);
            $this->sqlQuery = "DELETE FROM {$this->key['table']} WHERE {$index[0]} = :{$index[1]}";
            if (isset($this->key['conditions']) && $this->hasElements($this->key['conditions'], 1)) {
                $this->sqlQuery .= " AND {$index[1]} = :{$index[1]}";
            }
            $this->sqlQuery .= " LIMIT 1";

            return $this;
        }
    }

    public function rawQueryDriver(): self
    {
        if ($this->isQueryTypeValid('raw')) {
            $this->sqlQuery = $this->key['raw'];

            return $this;
        }
    }

    
}