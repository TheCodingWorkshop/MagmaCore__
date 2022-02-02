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
use MagmaCore\DataObjectLayer\Query\Exception\QueryBuilderInvalidArgumentException;

class QueryBuilderFactory
{

    public function create(string $QueryDriverString, array $databaseProps = []): QueryBuilderInterface {
        $QueryDriverObject = new $QueryDriverString($databaseProps);
        if (!$QueryDriverObject instanceof QueryBuilderDriverInterface) {
            throw new QueryBuilderInvalidArgumentException(
                $QueryDriverString . ' is not a valid session storage object.'
            );
        }

        return new QueryBuilder($QueryDriverObject);
    }
    
}