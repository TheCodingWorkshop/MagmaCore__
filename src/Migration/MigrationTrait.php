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

    public function fileStart($className)
    {
        $file = '<?php' . PHP_EOL;
        $file .= '/*' . PHP_EOL;
        $file .= '* This file is part of the MagmaCore package.' . PHP_EOL;
        $file .= '*' . PHP_EOL;
        $file .= '* (c) Ricardo Miller <ricardomiller@lava-studio.co.uk>' . PHP_EOL;
        $file .= '*' . PHP_EOL;
        $file .= '* For the full copyright and license information, please view the LICENSE' . PHP_EOL;
        $file .= '* file that was distributed with this source code.' . PHP_EOL;
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
    public function writeClass(string $className, string $schemaContent, mixed $direction = null): string
    {
        $func = $this->fileStart($className) . PHP_EOL;
        $func .= "\t" . '/**' . PHP_EOL;
        $func .= "\t" . '* Migrate the query statement to the database' . PHP_EOL;
        $func .= "\t" . '*' . PHP_EOL;
        $func .= "\t" . '* @return string' . PHP_EOL;
        $func .= "\t" . '*/' . PHP_EOL;
        $func .= "\t" . 'public function ' . (($direction !==null) ? $direction : 'up') . '(): string' . PHP_EOL;
        $func .= "\t" . '{' . PHP_EOL;
        $func .= "\t\t" . 'return "' . PHP_EOL;
        $func .= "\t\t\t" . "{$schemaContent}";
        $func .= "\t\t" . '";' . PHP_EOL;
        $func .= "\t" . '}' . PHP_EOL;

        $func .= $this->fileEnd();

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
}
