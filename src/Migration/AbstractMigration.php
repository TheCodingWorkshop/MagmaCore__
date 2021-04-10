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

abstract class AbstractMigration implements MigrationInterface, MigrationChangeInterface
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
    protected const CREATE_MIGRATION = 'Generating mirgation for...';
    protected const END_MIGRATION = ' ... OK';

    /**
     * Main class constructor
     *
     * @param object $dataAccess
     * @param string|null $dataSchema
     * @return void
     */
    public function __construct(object $dataRepository, string|null $dataSchema = null)
    {
        if ($dataSchema !== null) {
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
     * Returns difference between the Schema and migrated files and return an
     * array of the difference. If array returns empty this means there no 
     * difference between the files within the Schema and migration directory.
     * Ie Schema files class namespace is hash to match the generated migration
     * file class name
     *
     * @return array
     */
    public function fileDiff(): array
    {
        $schemas = $this->scan($this->schemaPath);
        $migrations = $this->scan($this->migrationFiles);
        $hashSchema = array_map(function ($value) {
            $filename = $this->getFileName($value);
            if (class_exists($className = '\App\Schema\\' . $filename)) {
                $object = BaseApplication::diGet($className);
                $namespace = get_class($object);
                $hashValue = hash('sha256', $namespace);
                return 'm' . $hashValue;
            }
        }, $schemas);
        return array_diff(
            $this->filterUnwantedElements($hashSchema),
            $this->filterUnwantedElements(
                str_replace('.php', '', $migrations)
            )


        );
    }

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

                        if (empty($this->fileDiff())) {
                            /** @todo ensure message only displays if theres nothing to generate */
                            $this->migrateLog('There is no new schema to generate.');
                            exit;
                        }
                        $hashClassNames = $this->fileDiff();
                        $this->counter = $hashClassNames;
                        foreach ($hashClassNames as $hashClass) {
                            $hashClassName = $hashClass;
                        }

                        $this->migrateLog(
                            self::CREATE_MIGRATION . " {$className} [#" . count($this->counter) . "]" . self::END_MIGRATION
                        );
                       // $this->migrateLog($className . " [#" . count($this->counter) . "]");
                        file_put_contents(
                            ROOT_PATH . '/' . $this->migrationFiles . $hashClassName . '.php',
                            trim(
                                $this->writeClass(
                                    $hashClassName,
                                    $newClassName,
                                    $schemaContent,
                                    $direction
                                )
                            ),
                            LOCK_EX
                        );
                    }
                }
            }
        }
    }

    public function migrationChange()
    {
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
    public function migrationDifferences(array $array1 = [], array $array2 = []): array
    {
        /* We can only compute the difference if the files are the same So we need to remove the file extension return from the $this->locateMigrationFiles() */
        $files = array_map(fn ($f): string => $this->getFileName($f), $this->locateMigrationFiles());
        return array_diff(
            !empty($array1) ? $array1 : $files,
            !empty($array2) ? $array2 : (($this->getMigrations() !== null) ? $this->getMigrations() : [])
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

        if (empty($migrations)) {
            $this->migrateLog('No migrations to apply.');
            exit;
        }

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

                $this->logBefore($direction);
                $this->executeMigrationCommand(
                    $newMigrateObject,
                    $direction
                );
                $this->migrateLog('Migrated ' . (count($this->migrations) > 0 ? count($this->migrations) : 0) . ' tables');
                $this->logAfter();
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
