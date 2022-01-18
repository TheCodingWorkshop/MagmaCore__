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

namespace MagmaCore\DataObjectLayer\DatabaseTransaction;

use PDOException;

interface DatabaseTransactionInterface
{

    /**
     * Begin a transaction, turning off autocommit
     *
     * @return bool true on success or false on failure
     * @throws PDOException - if a transaction as already started or the driver does not support transaction
     */
    public function start() : bool;

    /**
     * Commits a transaction
     *
     * @return bool true on success or false on failure
     * @throws PDOException - If theres no active transaction
     */
    public function commit () : bool;

    /**
     * Rolls back a transaction
     *
     * @return bool true on success or false on failure
     * @throws PDOException - If theres no active transaction
     */
    public function revert() : bool;

}