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

interface QueryBuilderInterface
{

    /**
     * Insert query string
     *
     * @return string
     * @throws QueryBuilderException
     */
    public function insertQuery() : string;

    /**
     * Select query string
     *
     * @return string
     * @throws QueryBuilderException
     */
    public function selectQuery() : string;

    /**
     * Update query string
     *
     * @return string
     * @throws QueryBuilderException
     */
    public function updateQuery() : string;

    /**
     * Delete query string
     *
     * @return string
     * @throws QueryBuilderException
     */
    public function deleteQuery() : string;

    /**
     * Search|Select query string
     *
     * @return string
     * @throws QueryBuilderException
     */
    public function searchQuery() : string;

    /**
     * Raw query string
     *
     * @return string
     * @throws QueryBuilderException
     */
    public function rawQuery() : string;

}
