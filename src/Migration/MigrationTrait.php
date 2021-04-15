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

trait MigrationTrait
{
    
    public function buildMigrationFile(string $hashClassName, string $schemaContent, string $newClassName, string $table = 'tests'): void
    {
        $commonPattern = ['{{ className }}', '{{ classGeneratedFrom }}'];
        $commonReplacement = [$hashClassName, $newClassName];

        $content = file_get_contents($this->rootPath . 'Build/ExampleMigration.txt');
        $pattern = array_merge($commonPattern, ['{{ up }}', '{{ down }}']);
        $replacement = array_merge($commonReplacement, [$schemaContent, $this->downMethod($table)]);
        $content = str_replace($pattern, $replacement, $content);

        file_put_contents(
            $this->rootPath . $this->migrationFiles . $hashClassName . '.php',
            trim($content),
            LOCK_EX
        );

    }

    public function buildMigrationChangeFile(string $hashClassName, string $schemaContent, string $newClassName): void
    {

        $parts = explode('_', $newClassName);
        if (isset($parts[1]) && $parts[1] !=='') {
            $parentClass = $parts[1];
            $content = $content = file_get_contents($this->rootPath . 'Build/ExampleMigrationChange.txt');
            $commonPattern = ['{{ className }}', '{{ classGeneratedFrom }}'];
            $commonReplacement = [$hashClassName, $newClassName];
            $pattern = array_merge($commonPattern, ['{{ extendClass }}', '{{ change }}']);
            $replacement = array_merge($commonReplacement, [$parentClass, $schemaContent]);
            $content = str_replace($pattern, $replacement, $content);
        file_put_contents(
            $this->rootPath . $this->migrationFiles . $hashClassName . '.php',
            trim($content),
            LOCK_EX
        );
    }

    }

    private function downMethod(string $table)
    {
        return "Drop table {$table}";
    }

    /**
     * Terminal logging message with date and time stamp
     *
     * @param string $string
     */
    public function migrateLog(string $string)
    {
        echo "[" . date("Y-m-d H:i:s") . "] - " . $string . PHP_EOL;
    }

    public function logBefore(string $direction)
    {
        $msg ='';
        if ($direction) {
            $this->direction = $direction;
            switch($direction) {
                case Migrate::MIGRATE_UP;
                    $msg = 'Starting migration....';
                    break;
                case Migrate::MIGRATE_DOWN;
                    $msg = 'Dropping table...';
                    break;
                case Migrate::MIGRATE_CHANGE;
                    $msg = 'Making changes...';
                    break;

            }
            echo $this->migrateLog($msg);
        }
    }

    public function logAfter()
    {
        $msg ='';
            switch($this->direction) {
                case Migrate::MIGRATE_UP;
                case Migrate::MIGRATE_DOWN;
                case Migrate::MIGRATE_CHANGE;
                    $msg = 'OK...';
                    break;

            }
            echo $this->migrateLog($msg);
    }


    /**
     * Get the name of a file pass as the argument parameter. Without the 
     * file extension.
     *
     * @param string $file
     * @return string
     */
    public function getFileName(string $file): string
    {
        return pathinfo($file, PATHINFO_FILENAME);
    }

    /**
     * Returns an array of files within the specified directory path
     *
     * @param string $dir
     * @return array
     */
    public function scan(string $dir): array
    {
        return scandir(ROOT_PATH . '/' . $dir);
    }

    /**
     * Resolves the class name and figure out if the class contains certain
     * string. either Drop or change. And will always return up as default.
     * This helper method is used to determin the direction of which method 
     * gets executed.
     *
     * @param array $filePrefixArray
     * @param string $className
     * @return string
     */
    private function resolveMigrationClass(array $filePrefixArray, string $className): string
    {
        foreach ($filePrefixArray as $fs) {
            if (str_contains($className, $fs)) {
                return Migrate::MIGRATE_CHANGE;
            }    
        }
        if (str_contains($className, 'Destroy')) {
            return Migrate::MIGRATE_DOWN;
        }

        return Migrate::MIGRATE_UP;
    }

    /**
     * Filter unwanted elements from an array. The function is wrapped with
     * array_value which will reset the array index
     *
     * @param array $files
     * @return array
     */
    public function filterUnwantedElements(array $files): array
    {
        return array_values(
            array_filter(
                $files, 
                fn($value) => !is_null($value) && $value !=='' && $value !=='.' && $value !=='..'
            )
        );
    }

    public function isFile($file)
    {
        if ($file === '.' || $file === '..') {
          return;
        }

    }


}
