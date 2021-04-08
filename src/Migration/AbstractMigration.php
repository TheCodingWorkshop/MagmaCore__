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
use MagmaCore\DataSchema\DataSchemaBuilderInterface;
use MagmaCore\Base\Exception\BaseBadFunctionCallException;
use MagmaCore\Base\Exception\BaseInvalidArgumentException;
use MagmaCore\Migration\Exception\MigrationInvalidArgumentException;

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
    protected string $up;
    protected string $down;
    protected string $change;
    protected ?object $currentObject;

    protected const NEED_MIGRATION = 'There is no migration files detected. Run php magma/create.php first';
    protected const CREATE_MIGRATION = 'Creating migraton file...';
    protected const END_CREATE_MIGRATION = 'file created successfully.';
    protected const START_MIGRATION = 'Starting migration...';
    protected const END_MIGRATION = 'Migration completed successfully.';

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
     * Create the SQL query from the generated schema object
     *
     * @return void
     */
    public function createMigrationFromSchema(): void
    {
        $files = scandir(ROOT_PATH . '/' . 'App/Schema/');
        if (is_array($files) && count($files) > 0) {
            foreach ($files as $file) {
                if ($file === '.' || $file === '..') {
                    continue;
                }
                $className = pathinfo($file, PATHINFO_FILENAME);
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

    public function getCurrentObject()
    {
        return $this->currentObject;
    }

    private function resolveMigrationClass($className): string
    {
        if (str_contains($className, 'Drop')) {
            return 'down';
        }
        if (str_contains($className, 'Change')) {
            return 'change';
        }
        
        return 'up';
    }


    /**
     * Return an array of all the migration files within the app/migration directory
     *
     * @return array
     */
    public function locateMigrationFiles(): array
    {
        $files = scandir(ROOT_PATH . '/' . $this->migrationFiles);
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
        return array_diff(
            $this->locateMigrationFiles(),
            $this->getMigrations() 
        );
    }

    public function dropMigration(): void
    {
        $files = scandir(ROOT_PATH . '/' . 'App/Schema/');
        if (is_array($files)) {
            foreach ($files as $file) {
                if ($file === '.' || $file === '..'){
                    continue;
                }
                 if (str_contains($file, 'Drop')) {
                    if (class_exists($newObject = '\App\Schema\\' . pathinfo($file, PATHINFO_FILENAME))) {
                        $object = BaseApplication::diGet($newObject);
                        if (!$object) {
                            throw new \Exception();
                        }
                        // if (!method_exists($object, 'down')) {
                        //     throw new BaseBadFunctionCallException('down method does not exists within ' . get_class($object));
                        // }
                        $str = $object->createSchema();

                        $this->migrateLog('Migration dropping');
                        $this->dataRepository
                            ->getClientRepository()
                            ->getClientCrud()
                            ->getMapping()
                            ->exec($str);
                        $this->migrateLog($file . ' drop successfully');
                
                    }
                }
            }
        }
    }


    /**
     * Execute the migration. Creating the migration table if not already
     * exists and running the proper method (up(), down(), change())
     *
     * @param string|null $position
     * @return void
     */
    public function migrate(string|null $position = null): void
    {

        $this->createMigrationTable();
        foreach ($this->migrationDifferences() as $migrate) {
            if ($migrate === '.' || $migrate === '..') {
                continue;
            }
            $this->migrateClass = pathinfo($migrate, PATHINFO_FILENAME);
            if (class_exists($newClass = '\App\Migrations\\' . $this->migrateClass)) {
                $newMigrateObject = BaseApplication::diGet($newClass);
                if (!$newMigrateObject instanceof MigrateInterface) {
                    throw new MigrationInvalidArgumentException($newClass . ' is not a valid Migration object. You will need to implement the MigrateInterface');
                }
                $this->executeMigrationCommand($newMigrateObject, $this->migrateClass, $position);
            }
        }
        $this->executeMigration();
    }

    /**
     * Execute the migration command using the editor terminal
     *
     * @param MigrateInterface $migrateObject
     * @param mixed $migrate
     * @return void
     */
    private function executeMigrationCommand(MigrateInterface $migrateObject, string $migrateClass, string $position)
    {
        $this->migrations[] = $migrateClass;
        $direction = (!empty($position) ? $position : 'up');
        if (!method_exists($migrateObject, $direction)) {
            throw new BaseBadFunctionCallException($direction . ' method does not exists within ' . get_class($migrateObject));
        }
        $this->migrateLog(self::START_MIGRATION);
        $this->dataRepository
            ->getClientRepository()
            ->getClientCrud()
            ->getMapping()
            ->exec(

                $migrateObject->$direction()
            );
        $this->migrateLog(self::END_MIGRATION);
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
