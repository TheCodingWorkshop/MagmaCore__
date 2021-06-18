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

use Exception;

interface QueryBuilderInterface
{

    /**
     * Insert query string
     *
     * @return string
     * @throws Exception
     */
    public function insertQuery() : string;

    /**
     * Select query string
     *
     * @return string
     * @throws Exception
     */
    public function selectQuery() : string;

    /**
     * Update query string
     *
     * @return string
     * @throws Exception
     */
    public function updateQuery() : string;

    /**
     * Delete query string
     *
     * @return string
     * @throws Exception
     */
    public function deleteQuery() : string;

    /**
     * Search|Select query string
     *
     * @return string
     * @throws Exception
     */
    public function searchQuery() : string;

    /**
     * Raw query string
     *
     * @return string
     * @throws Exception
     */
    public function rawQuery() : string;

}
