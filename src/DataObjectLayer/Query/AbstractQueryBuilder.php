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

namespace MagmaCore\DataObjectLayer\Query;

use MagmaCore\DataObjectLayer\Query\Driver\QueryBuilderDriverInterface;

abstract class AbstractQueryBuilder implements QueryBuilderInterface
{
    private object $queryDriver;

    private array $databaseProps = [];


    public function __construct(QueryBuilderDriverInterface $queryBuilderDriver = null, array $databaseProps = [])
    {
        $this->queryDriver = $queryBuilderDriver;
        $this->databaseProps = $databaseProps;
    }

    public function getDatabaseProps(): array
    {
        return $this->databaseProps;
    }

    public function getQueryDriver()
    {
        return $this->queryDriver;
    }

    
}