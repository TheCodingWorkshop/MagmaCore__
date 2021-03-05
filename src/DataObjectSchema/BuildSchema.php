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

namespace MagmaCore\DataObjectSchema;

use MagmaCore\DataObjectSchema\Type\NumericSchema;
use MagmaCore\DataObjectSchema\BuildSchemaInterface;

class BuildSchema implements BuildSchemaInterface
{

    /**
     * Renders a auto generated ID row as 99% of table created will have a ID row. This method
     * makes it easy to return that row without returning it each time when creating a new
     * table schema
     *
     * @param string|null $name
     * @param integer $length
     * @return array
     */
    public function autoID(string|null $name = null, int $length = 10): array
    {
        $this->schemaID = ($name !==null) ? $name : 'id';
        $idSchema = [
            'type' => 'int', 
            'name' => $this->schemaID, 
            'length' => $length, 
            'null' => false, 
            'attributes' => 'unsigned', 
            'index' => 'primary', 
            'auto_increment' => true
        ];
        return [
            NumericSchema::class => $idSchema

        ];
    }

    public function varchar(string $name, int $length = 190, mixed $nullable, mixed $default = null): array
    {
        $arr = [
            'type' => 'varchar', 
            'name' => $name, 
            'length' => $length, 
            'null' => $nullable
        ];
        return [
            StringSchema::class => $arr
        ];
    }

    public function timestamp(string $name)
    {

    }

    public function integre()
    {}

    /**
     * Return the schema ID for the current schema build
     *
     * @return void
     */
    public function getFromAutoID()
    {
        return $this->schemaID;
    }


}
