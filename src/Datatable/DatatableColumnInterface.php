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

namespace MagmaCore\Datatable;

interface DatatableColumnInterface
{

    /**
     * Returns an array of database columns that matches the its entity and schema
     *
     * @param array $dbColumns
     * @param object|null $callingController
     * @return array
     */
    public function columns(array $dbColumns = [], object|null $callingController = null) : array;

}