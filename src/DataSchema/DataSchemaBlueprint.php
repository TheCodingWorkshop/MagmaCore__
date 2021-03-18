<?php
/*
 * This file is part of the MagmaCore package.
 *
 * (c) Ricardo Miller <ricardomiller@lava-studio.co.uk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare (strict_types = 1);

namespace MagmaCore\DataSchema;

use MagmaCore\DataSchema\DataSchemaBlueprintInterface;
use MagmaCore\DataSchema\Types\NumericType;
use MagmaCore\DataSchema\Types\StringType;

class DataSchemaBlueprint implements DataSchemaBlueprintInterface
{

    /**
     * Undocumented function
     *
     * @param string $name
     * @param integer $length
     * @param boolean $null
     * @param mixed $default
     * @return array
     */
    public function varchar(string $name, int $length = 196, bool $null = true, mixed $default = null): array
    {
        return [
            StringType::class => ['name' => $name, 'type' => 'varchar', 'length' => $length, 'null', $null, 'default' => $default],
        ];
    }

    /**
     * Undocumented function
     *
     * @param string $name
     * @param integer $length
     * @param boolean $null
     * @param string $attributes
     * @param mixed $default
     * @return void
     */
    public function int(string $name, int $length = null, bool $null = true, string $attributes = 'unsigned', mixed $default = null): array
    {
        return [
            NumericType::class => ['name' => $name, 'type' => 'int', 'length' => $length, 'null' => $null, 'default' => $default, 'attributes' => $attributes],
        ];

    }

}
