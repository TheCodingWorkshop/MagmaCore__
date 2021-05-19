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

namespace MagmaCore\DataObjectLayer\DataRelationship;

use MagmaCore\Base\BaseApplication;
use MagmaCore\Collection\Collection;
use MagmaCore\DataObjectLayer\DataRelationship\Exception\DataRelationshipInvalidArgumentException;

/**
 * Each record in both tables can relate to none or any number of records 
 * in the other table. These relationships require a third table, 
 * called an associate, pivot or linking table, because relational systems cannot 
 * directly accommodate the relationship.
 */
class ManyToMany extends AbstractDataRelationship
{

    /** @var string $query */
    private string $query = '';

    /**
     * Return an container object for the belongsToMany class
     *
     * @param string $belongsToMany
     * @return void
     */
    public function belongsToMany(string $belongsToMany): static
    {
        if ($belongsToMany)
            $this->belongsToMany = BaseApplication::diGet($belongsToMany);

        return $this;
    }

    /**
     * Returns the schema name for the belongsToMany method
     *
     * @return string
     */
    public function getSchema(): string
    {
        return $this->belongsToMany->getSchema();
    }

    /**
     * Returns the schema ID name for the belongsToMany method
     *
     * @return string
     */
    public function getSchemaID()
    {
        return $this->belongsToMany->getSchemaID();
    }

    /**
     * Build the relationship query joining two tables together using the 
     * pivot model generated for both table
     *
     * @param string $childSelector
     * @param string $manyToManySelector
     * @param array $selectors
     * @return static
     */
    public function manyToMany(
        string $childSelector, /* Mostly the primary key from the right table */
        string $manyToManySelector, /* the field from the right table to join to the left */
        array $selectors = [] /* all the field which we want to return */
    ): static {

        $t1 = $this->getHasOneSchema();
        $t2 = $this->getSchema();
        $columnPivot = $this->tablePivot->getColumns($this->schemaObject);

        $this->query .= "SELECT " . implode(', ', $this->filterSelection($t1, $selectors)) . ", ";
        $this->query .= $str = $t2 . '.' . $this->getSchemaID() ?? $childSelector;
        $this->query .= " AS ";
        $this->query .= str_replace('.', '_', $str) . ", ";
        $this->query .= $str = $t2 . '.' . $manyToManySelector;
        $this->query .= " AS ";
        $this->query .= $manyToManySelector;
        $this->query .= " FROM ";
        $this->query .= $t1;
        $this->query .= " JOIN " . $this->tablePivot->getSchema() . " ON ";
        $this->query .= "(" . $this->getHasOneSchema() . '.' . $this->hasOne->getSchemaID() . " = ";
        $this->query .= "" . $this->tablePivot->getSchema() . '.' . $columnPivot[0] . ")";
        $this->query .= " JOIN " . $t2 . " ON (";
        $this->query .= $t2 . '.' . $this->belongsToMany->getSchemaID() . " = ";
        $this->query .= $this->tablePivot->getSchema() . '.' . $columnPivot[1];
        $this->query .= ")";
        return $this;
    }

    /**
     * Specifies a where clause for the related query string. If required
     *
     * @param string $key
     * @return static
     */
    public function where(array $conditions = []): static
    {

        if (count($conditions) <= 0) {
            throw new DataRelationshipInvalidArgumentException('');
        }
        $this->conditions = $conditions;
        $primaryKey = array_keys($this->conditions);
        $this->query .= " WHERE " . $this->tablePivot->getSchema() . '.' . $primaryKey[0] . ' = :' . $primaryKey[0];
        return $this;
    }

    /**
     * Return the query results as an collection
     *
     * @return Collection
     */
    public function fetchAsCollection(): Collection
    {
        $results = $this->tablePivot
            ->getRepo()
            ->getEm()
            ->getCrud()
            ->rawQuery($this->query, $this->conditions ?? [], 'fetch_all');

        return new Collection($results);
    }

    /**
     * Return the raw relationship query.
     *
     * @return string
     */
    public function getQuery(): string
    {
        return $this->query;
    }



}
        // $query = "SELECT users.id, users.email, users.firstname, users.lastname, roles.id AS role_id, roles.role_name AS role_name FROM users
        // JOIN user_role on (users.id = user_role.user_id)
        // JOIN roles on (roles.id = user_role.role_id) WHERE roles.id = 1";

