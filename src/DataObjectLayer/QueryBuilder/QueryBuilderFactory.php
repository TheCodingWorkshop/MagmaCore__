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

use MagmaCore\DataObjectLayer\Exception\DataLayerUnexpectedValueException;
use MagmaCore\DataObjectLayer\QueryBuilder\QueryBuilderInterface;

class QueryBuilderFactory
{

    /**
     * Main constructor method
     * 
     * @return void
     */
    public function __construct()
    { }

    public function __create(string $queryBuilderString) : QueryBuilderInterface
    {
        $queryBuilderObject = new $queryBuilderString();
        if (!$queryBuilderString instanceof QueryBuilderInterface) {
            throw new DataLayerUnexpectedValueException($queryBuilderString . ' is not a valid Query builder object.');
        }
        return $queryBuilderObject;
    }

    /**
     * Create the QueryBuilder object
     *
     * @param string $queryBuilderString
     * @return QueryBuilderInterface
     */
    public function create(string $queryBuilderString) : QueryBuilderInterface
    {
        $queryBuilderObject = new $queryBuilderString();
        if (!$queryBuilderObject instanceof QueryBuilderInterface) {
            throw new DataLayerUnexpectedValueException($queryBuilderString . ' is not a valid Query builder object.');
        }
        return $queryBuilderObject;
    }


}
