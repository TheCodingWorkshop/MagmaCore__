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

use MagmaCore\DataObjectLayer\Query\Exception\QueryBuilderOutOfRangeException;

abstract class AbstractQueryBuilderDriver implements QueryBuilderDriverInterface
{

    /** @var array */
    protected array $key = [];

    /** @var string */
    protected string $sqlQuery = '';

    protected array $databaseProps = [];

    /** @var array */
    protected const SQL_DEFAULT = [
        'conditions' => [],
        'selectors' => [],
        'replace' => false,
        'distinct' => false,
        'from' => [],
        'where' => null,
        'and' => [],
        'or' => [],
        'orderby' => [],
        'fields' => [],
        'primary_key' => '',
        'table' => '',
        'type' => '',
        'raw' => '',
        'limit' => '',
        'join_to' => '',
        'join_to_selectors' => [],
        'join_type' => '',
    ];
    /** @var array */
    protected const QUERY_TYPES = [
        'insert', 
        'select', 
        'update', 
        'delete', 
        'raw', 
        'search', 
        'join'
    ];


    public function __construct(array $databaseProps = [])
    {
        $this->databaseProps = $databaseProps;
    }

    public function getDatabaseProps(): array
    {
        return $this->databaseProps;
    }

    /**
     * Undocumented function
     *
     * @param array $args
     * @return void
     */
    public function buildQuery(array $args = []): self
    {
        if (count($args) > 0) {
            throw new QueryBuilderOutOfRangeException('Your BuildQuery method has no defined argument. Please fix this');
        }
        $arg = array_merge(self::SQL_DEFAULT, $args);
        $this->key = $arg;
        return $this;
    }

    public function in(): self
    {
        if (isset($this->key['table']) && $this->key['table'] !=='')
            $this->sqlQuery .= $this->key['table'];
            return $this;
    }

    public function where(): self
    {
        if (empty($this->key['conditions'])) {
            $this->sqlQuery = " WHERE 1";
        }

        if (isset($this->key['conditions']) && $this->key['conditions'] != '') {
            if (is_array($this->key['conditions'])) {
                $sort = [];
                foreach ($this->key['conditions'] as $where) {
                    if (isset($where) && $where !='') {
                        $sort[] = $where . " = :" . $where;
                    }
                }

                if ($this->hasElements($this->conditions)) {
                    $this->sqlQuery .= " WHERE " . implode(" AND ", $sort);
                }

                return $this;
            }
        }
    }

    public function limitOffset(): self
    {
        if (isset($this->key['limit']) && isset($this->key['offset']) !=='') {
            $this->sqlQuery .= " LIMIT :offset, :limit";
            return $this;
        }
    }

    public function limit()
    {
        if (isset($this->key['limit']) && !isset($this->key['offset'])) {
            $this->sqlQuery .= " LIMIT " . $this->key['limit'];
            return $this;
        }
    }

    public function order(): self
    {
        if (isset($this->key['orderby']) && $this->key['orderby'] !=='') {
            $this->sqlQuery .= " ORDER BY " . $this->key['orderby'];
            return $this;
        }
    }

    public function build(): string
    {
        if (!empty($this->sqlQuery))
            return $this->sqlQuery;
    }

    protected function isQueryTypeValid(string $type) : bool
    {
        if (in_array($type, self::QUERY_TYPES)) {
            return true;
        }
        return false;
    }

        /**
     * Checks whether a key is set. returns true or false if not set
     * 
     * @param string $key
     * @return bool
     */
    protected function has(string $key): bool
    {
        return isset($this->key[$key]);
    }

    public function getSqlDefaults(): array
    {
        return self::SQL_DEFAULT;
    }

    public function getQueryTypes(): array
    {
        return self::QUERY_TYPES;
    }

    public function aliasSelectors(string $parent, array $selectors, $default = ['*']): array
    {
        $filter = array_map(
            fn ($selector): string => $parent . '.' . $selector,
            $selectors
        );
        return (empty($filter)) ? $default : $filter;
    }

    public function hasElements(array $elements = [], ?int $counter = 0): bool
    {
        return (count($elements) > $counter);
    }

}