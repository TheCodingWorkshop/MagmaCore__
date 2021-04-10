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

trait MigrationTrait
{

    public function fileStart(string $className, string $originalClassName)
    {
        $file = '<?php' . PHP_EOL;
        $file .= '/*' . PHP_EOL;
        $file .= '* This file is part of the MagmaCore package.' . PHP_EOL;
        $file .= '*' . PHP_EOL;
        $file .= '* (c) Ricardo Miller <ricardomiller@lava-studio.co.uk>' . PHP_EOL;
        $file .= '*' . PHP_EOL;
        $file .= '* For the full copyright and license information, please view the LICENSE' . PHP_EOL;
        $file .= '* file that was distributed with this source code.' . PHP_EOL;
        $file .= '* =====================================================' .PHP_EOL;
        $file .= '* File generated from ' . $originalClassName . PHP_EOL;
        $file .= '*/' . PHP_EOL;
        $file .= PHP_EOL;
        $file .= 'declare(strict_types=1);' . PHP_EOL;
        $file .= PHP_EOL;
        $file .= 'namespace App\Migrations;' . PHP_EOL;
        $file .= PHP_EOL;
        $file .= 'use MagmaCore\Migration\MigrateInterface;' . PHP_EOL;
        $file .= PHP_EOL;
        $file .= "class {$className} implements MigrateInterface" . PHP_EOL;
        $file .= '{' . PHP_EOL;

        return $file;
    }

    public function fileEnd()
    {
        return '}' . PHP_EOL;
    }

    /**
     * Undocumented function
     *
     * @param string $className
     * @param string $schemaContent
     * @param mixed $direction
     * @return string
     */
    public function writeClass(string $className, string $originalClassName, string $schemaContent, mixed $direction = null): string
    {
        $func = $this->fileStart($className, $originalClassName) . PHP_EOL;
        if ($direction) {
            switch($direction) {
                case 'up' :
                    $func .= $this->method($schemaContent, $originalClassName);
                    $func .= PHP_EOL;
                    $func .= PHP_EOL;
                    $func .= $this->method(null, $originalClassName, 'down');
                    $func .= PHP_EOL;
                    $func .= PHP_EOL;
                    $func .= $this->method(null, $originalClassName, 'change');
                    break;
                case 'down' :
                    $func .= $this->method(null, $originalClassName);
                    $func .= PHP_EOL;
                    $func .= PHP_EOL;
                    $func .= $this->method($schemaContent, $originalClassName, 'down');
                    $func .= PHP_EOL;
                    $func .= PHP_EOL;
                    $func .= $this->method(null, $originalClassName, 'change');
                    break;
                case 'change' :
                    $func .= $this->method(null, $originalClassName);
                    $func .= PHP_EOL;
                    $func .= PHP_EOL;
                    $func .= $this->method(null, $originalClassName, 'down');
                    $func .= PHP_EOL;
                    $func .= PHP_EOL;
                    $func .= $this->method($schemaContent, $originalClassName, 'change');

                    break;
    
            }
        }
        $func .= $this->fileEnd();

        return $func;
    }
    
    
    public function method($schemaContent, string $originalClassName, string $name = 'up')
    {
        $func = "\t" . '/**' . PHP_EOL;
        $func .= "\t" . '* Migrate the query statement to the database' . PHP_EOL;
        $func .= "\t" . '*' . PHP_EOL;
        $func .= "\t" . '* @uses ' . $originalClassName . PHP_EOL;
        $func .= "\t" . '* @return string' . PHP_EOL;
        $func .= "\t" . '*/' . PHP_EOL;
        $func .= "\t" . 'public function ' . $name . '(): string' . PHP_EOL;
        $func .= "\t" . '{' . PHP_EOL;
        $func .= "\t\t" . 'return "' . PHP_EOL;
        $func .= "\t\t\t" . ($schemaContent !==null) ? $schemaContent : '';
        $func .= "\t\t" . '";' . PHP_EOL;
        $func .= "\t" . '}' . PHP_EOL;
        return $func;

    }

    /**
     * Undocumented function
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
                case 'up';
                    $msg = 'Starting migration....';
                    break;
                case 'down';
                    $msg = 'Dropping table...';
                    break;
                case 'change';
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
                case 'up';
                case 'down';
                case 'change';
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
     * @param string $className
     * @return string
     */
    private function resolveMigrationClass(string $className): string
    {
        foreach (['Drop', 'Change', 'Modify', 'Add'] as $fs) {
            if (str_contains($className, $fs)) {
                return 'change';
            }    
        }
        if (str_contains($className, 'Destroy')) {
            return 'down';
        }

        return 'up';
    }

    
    public function filterUnwantedElements(array $files)
    {
        return array_values(array_filter($files, fn($value) => !is_null($value) && $value !=='' && $value !=='.' && $value !=='..'));
    }

    public function isFile($file)
    {
        if ($file === '.' || $file === '..') {
          return;
        }

    }


}
