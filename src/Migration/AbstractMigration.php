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

namespace MagmaCore\Migration;

use MagmaCore\Base\BaseApplication;
use MagmaCore\Migration\MigrationTrait;
use MagmaCore\Migration\MigrateInterface;
use MagmaCore\DataSchema\DataSchemaBuilderInterface;
use MagmaCore\Base\Exception\BaseInvalidArgumentException;
use MagmaCore\Migration\Exception\MigrationInvalidArgumentException;
use ReflectionClass;

abstract class AbstractMigration implements MigrationInterface
{

    /** @var MigrationTrait */
    use MigrationTrait;

    /** @var object $dataSchema */
    protected object $dataSchema;
    /** @var object $dataAccess */
    protected object $dataRepository;
    /** @var array */
    protected array $migrations = [];
    /** @var string */
    protected string $migrationFiles = 'App/Migrations/';
    /** @var string */
    protected string $schemaPath = 'App/Schema/';

    protected const NEED_MIGRATION = 'You\'ve not created any migration files yet!';
    protected const CREATE_MIGRATION = 'Creating migraton file...';
    protected const END_CREATE_MIGRATION = 'file created successfully.';
    protected const START_MIGRATION = 'Starting migration...';
    protected const END_MIGRATION = 'Migration completed successfully.';

    protected const MIGRATE_UP = 'up';

    /**
     * Main class constructor
     *
     * @param object $dataAccess
     * @param string|null $dataSchema
     * @return void
     */
    public function __construct(object $dataRepository, string|null $dataSchema = null)
    {
        if ($dataSchema !==null) {
            $newSchema = BaseApplication::diGet($dataSchema);
            if (!$newSchema instanceof DataSchemaBuilderInterface) {
                throw new BaseInvalidArgumentException($dataSchema . ' is not a valid DataSchema object as it does not implement the DataSchemaBuilderInterface');
            }
            $this->dataSchema = $newSchema;    
        }
        $this->dataRepository = $dataRepository;
    }

    /**
     * Create the migration table is it doesn't already exists
     *
     * @return void
     */
    abstract public function createMigrationTable(): void;

    /**
     * Save the migration to the database migrations table returns true on success
     * or false on failure
     *
     * @param array $fields
     * @return boolean
     */
    abstract public function saveMigration(array $fields): bool;

    /**
     * Return all the database migration fields
     *
     * @param array $conditions
     * @return array
     */
    abstract public function getMigrations(array $conditions = []): array|null;

    /**
     * Create the migration files and placed them within the App/Migrations
     * directory
     *
     * @return void
     */
    public function createMigrationFromSchema(): void
    {
        $files = $this->scan($this->schemaPath);
        if (is_array($files) && count($files) > 0) {
            foreach ($files as $file) {
                if ($file === '.' || $file === '..') {
                    continue;
                }
                $className = $this->getFileName($file);
                if (class_exists($newClassName = '\App\Schema\\' . $className)) {
                    $direction = $this->resolveMigrationClass($className);
                    $object = BaseApplication::diGet($newClassName);
                    if (!$object) {
                        throw new MigrationInvalidArgumentException('');
                    }
                    $schemaContent = $object->createSchema();
                    if (!empty($schemaContent)) {
                        $calledClass = get_class($object);
                        $hashClassName = hash('sha256', $calledClass);
                        $hashClassName = 'm' . $hashClassName;
                        $this->migrateLog(self::CREATE_MIGRATION);
                        file_put_contents(
                            ROOT_PATH . '/' . $this->migrationFiles . $hashClassName . '.php',
                            trim($this->writeClass(
                                $hashClassName, 
                                $newClassName,
                                $schemaContent, 
                                $direction
                                )
                            ),
                            LOCK_EX
                        );
                        $this->migrateLog(self::END_CREATE_MIGRATION);
                    }
                }
            }
        }
    }

    /**
     * Return an array of all the migration files within the app/migration directory
     *
     * @return array
     */
    public function locateMigrationFiles(): array
    {
        $files = $this->scan($this->migrationFiles);
        if (is_array($files) && count($files) > 0) {
            return $files;
        }
    }

    /**
     * Compute the difference between migration files created and the already
     * created database migrations
     *
     * @return array
     */
    public function migrationDifferences(): array
    {
        /* We can only compute the difference if the files are the same So we need to remove the file extension return from the $this->locateMigrationFiles() */
        $files = array_map(fn($f): string => $this->getFileName($f), $this->locateMigrationFiles());
        return array_diff(
            $files,
            ($this->getMigrations() !==null) ? $this->getMigrations() : []
        );
    }

    /**
     * Execute the migration. Creating the migration table if not already
     * exists and running the proper method (up(), down(), change())
     *
     * @param string|null $position
     * @return void
     */
    public function migrate(string|null $direction = 'up'): void
    {
        $this->createMigrationTable();
        $migrations = $this->filterUnwantedElements($this->migrationDifferences());
        foreach ($migrations as $migrate) {
            if ($migrate === '.' || $migrate === '..') {
               continue;
            }
            $this->migrateClass = $this->getFileName($migrate);    
            $this->migrations[] = $this->migrateClass;
            if (class_exists($newClass = '\App\Migrations\\' . $this->migrateClass)) {
                $newMigrateObject = BaseApplication::diGet($newClass);
                if (!$newMigrateObject instanceof MigrateInterface) {
                    throw new MigrationInvalidArgumentException($newClass . ' is not a valid Migration object. You will need to implement the MigrateInterface');
                }
                // $reflect = new \ReflectionClass($newMigrateObject);
                // var_dump($reflect->getDocComment());
                // die;
                $this->migrateLog(self::START_MIGRATION);
                $this->executeMigrationCommand(
                    $newMigrateObject, 
                    $direction
                );
                $this->migrateLog(self::END_MIGRATION);
            }
    
        }
        $this->executeMigration();
    }

    /**
     * Execute the migration command using the editor terminal
     *
     * @param MigrateInterface $migrateObject
     * @param string $direction
     * @return void
     */
    private function executeMigrationCommand(MigrateInterface $migrateObject, string $direction)
    {

        $this->dataRepository
            ->getClientRepository()
            ->getClientCrud()
            ->getMapping()
            ->exec(
                $migrateObject->$direction()
            );
        
    }

    /**
     * Persist the migration command to the database if we have any migrations to apply.
     *
     * @return void
     */
    private function executeMigration(): void
    {
        if (empty($this->migrations))
            $this->migrateLog(self::NEED_MIGRATION);

        implode(
            ',',
            array_map(fn ($m) => $this->saveMigration(['migration_name' => $m]), $this->migrations)
        );
    }

}