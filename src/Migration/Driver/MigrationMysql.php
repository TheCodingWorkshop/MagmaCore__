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

namespace MagmaCore\Migration\Driver;

use MagmaCore\Migration\AbstractMigration;
use MagmaCore\DataObjectLayer\DataLayerClientFacade;

class MigrationMysql extends AbstractMigration
{

    /** @var string */
    protected const TABLESCHEMA = 'migrations';
    /** @var string  */
    protected const TABLESCHEMAID = 'id';
    /** @var DataLayerClientFacade $clientRepository */
    protected DataLayerClientFacade $clientRepository;

    /**
     * Main class constructor
     *
     * @param string|null $dataSchema
     * @return void
     */
    public function __construct(string|null $dataSchema = null)
    {
        $this->clientRepository = (new DataLayerClientFacade(self::TABLESCHEMA, self::TABLESCHEMA, self::TABLESCHEMAID));
        parent::__construct(
            $this->clientRepository,
            $dataSchema
        );
    }

    /**
     * Create the migration table
     *
     * @return void
     */
    public function createMigrationTable(): void
    {
        $create = "
            CREATE TABLE IF NOT EXISTS `" . self::TABLESCHEMA . "`
            (
                `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                `migration_name` varchar(65) NOT NULL,
                `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
                `modified_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`),
                UNIQUE KEY `migration_name` (`migration_name`)
            ) ENGINE=InnoDB
            ";
            $this->clientRepository
                ->getClientRepository()
                    ->getClientCrud()
                        ->getMapping() /* access the dataMapper object */
                            ->exec($create);
    }

    /**
     * Persist the migration to the database
     *
     * @return boolean
     */
    public function saveMigration(array $migrationFields): bool
    {
        return $this->clientRepository
            ->getClientRepository()
                ->getClientCrud()
                    ->create($migrationFields);
    }

    /**
     * Returns an array of database migrations records
     *
     * @param array $conditions
     * @return array|null
     */
    public function getMigrations(array $conditions = []): array|null
    {
        return $this->clientRepository
            ->getClientRepository()
                ->getClientCrud()
                    ->rawQuery('SELECT `migration_name` FROM ' . self::TABLESCHEMA, 
                    $conditions, 
                    'columns' /* fetching the result columns */
                );
    }
}
