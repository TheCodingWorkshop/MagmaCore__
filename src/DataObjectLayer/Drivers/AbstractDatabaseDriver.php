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

namespace MagmaCore\DataObjectLayer\Drivers;

use PDO;

abstract class AbstractDatabaseDriver implements DatabaseDriverInterface
{

    /** @var array $params - PDO Parameters */
    protected array $params = [
        PDO::ATTR_EMULATE_PREPARES => false,
        PDO::ATTR_PERSISTENT => true,
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ];
    /**
     * @var object|null
     */
    private ?object $dbh;

    public function PdoParams(): array
    {
        return $this->params;
    }

    /**
     * Close the database connection
     *
     * @return void
     */
    public function close()
    {
        $this->dbh = null;
    }
}
