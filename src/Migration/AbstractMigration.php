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

use MagmaCore\Migration\Migrate;
use MagmaCore\Base\BaseApplication;
use MagmaCore\Migration\MigrationTrait;
use MagmaCore\Migration\MigrateInterface;
use MagmaCore\DataSchema\DataSchemaBuilderInterface;
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
    /** @var string */
    protected string $schemaPath = 'App/Schema/';
    /** @var string */
    protected string $rootPath;
    
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
        $this->rootPath = ROOT_PATH . '/';
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
        $hashSchema = array_map(
            function ($value) {
            $filename = $this->getFileName($value);
            if (class_exists($className = '\App\Schema\\' . $filename)) {
                $object = BaseApplication::diGet($className);
                $hashValue = hash('sha256', str_replace(['\\', 'AppSchema'],'',get_class($object)));
                return 'm' . $hashValue;
            }
        }, $schemas);
        return array_diff(
            $this->filterUnwantedElements($hashSchema),
            $this->filterUnwantedElements(str_replace('.php', '', $migrations))
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
                    $direction = $this->resolveMigrationClass(Migrate::FILES_ALTERING, $className);
                    $object = BaseApplication::diGet($newClassName);
                    if (!$object) {
                        throw new MigrationInvalidArgumentException('');
                    }
                    $schemaContent = $object->createSchema();
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
                    /**
                     * @todo - create migration based on folder structure with anything
                     * in pivot directory to be loaded after main directory migration
                     */
                    $this->migrateLog(
                        Migrate::CREATE_MIGRATION . " {$className} [#" . count($this->counter) . "]" . Migrate::END_MIGRATION
                    );
                    if (!str_contains($newClassName, '_')) {
                    $this->buildMigrationFile($hashClassName, $schemaContent, $newClassName);
                    } else {
                        $this->buildMigrationChangeFile($hashClassName, $schemaContent, $newClassName);
                    }
                }
            }
        }
    }

    /**
     * Helper method which relocated files from one specified directory to 
     * another specified directory. The method works in conjuction with the 
     * createMigrationFromSchema() and attemps to move specific files define
     * by an array of file prefix which is checked agains the current $file
     * using PHP str_contains() method.
     * 
     * @param string $file
     * @param string $currentPath
     * @param string $relocationPath
     * @return void
     */
    public function fileRelocation(string $file, string $currentPath, string|null $relocationPath = null): void
    {
        foreach (Migrate::FILES_ALTERING as $type) {
            if (str_contains($file, $type)) {
                rename(
                    $this->rootPath . $currentPath . $file, 
                    $this->rootPath . $relocationPath . $file
                );

                // if ($handle = opendir($this->rootPath . $this->schemaPath)) {
                //     while (false !== ($filename = readdir($handle))) {
                //         rename(
                //             $this->rootPath . $currentPath . $filename, 
                //             $this->rootPath . $relocationPath . $filename
                //         );
                //             }
                //     closedir($handle);
                // }
            }
        }
    }

    public function createDir($pathName)
    {
        if (!is_dir($pathName)) {
            mkdir($pathName, 0777);
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
    public function migrate(string|null $direction = Migrate::MIGRATE_UP): void
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
                if ($direction === Migrate::MIGRATE_UP) {
                    $this->migrateLog('Migrated ' . (count($this->migrations) > 0 ? count($this->migrations) : 0) . ' tables');
                }
                $this->logAfter();
            }
        }
        $this->executeMigration();
    }

    /**
     * Using PHP ReflectionClass to get a reflection of the $migrateObject variable
     * then retrive an array of all the public methods within that class. We then
     * use array_map to iterate over the $methods and return a new array of just the 
     * method names.
     * We will iterate over each method name and use $migrateObject variable along with
     * the value of the foreach loop $migrateObject->$fill() to check whether is method
     * null if not null then we do an equal comparison between the $fill and $direction
     * 
     * Only then if it matches it will exeucte 
     *
     * @param MigrateInterface $migrateObject
     * @param string $direction
     * @return void
     */
    private function executeMigrationCommand(MigrateInterface $migrateObject, string $direction)
    {   
        $reflect = new \ReflectionClass($migrateObject);
        $methods = $reflect->getMethods(\ReflectionMethod::IS_PUBLIC);
        $migrateMethods = array_map(fn($method) => $method->name, $methods);
        if (is_array($migrateMethods) && sizeof($migrateMethods) > 0) {
            if (in_array($direction, $migrateMethods)) {
                $this->dataRepository
                ->getClientRepository()
                ->getClientCrud()
                ->getMapping()
                ->exec(
                    $migrateObject->$direction() /* Should either be up(), down() or change() */
                );

            }
        }
    }

    /**
     * Persist the migration command to the database if we have any migrations to apply.
     *
     * @return void
     */
    private function executeMigration(): void
    {
        if (empty($this->migrations))
            $this->migrateLog(Migrate::NEED_MIGRATION);

        implode(
            ',',
            array_map(fn ($m) => $this->saveMigration(['migration_name' => $m]), $this->migrations)
        );
    }
}
