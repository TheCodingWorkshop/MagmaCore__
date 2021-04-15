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

use MagmaCore\DataSchema\Types\StringType;
use MagmaCore\DataSchema\Types\NumericType;
use MagmaCore\DataSchema\Types\DatetimeType;
use MagmaCore\DataSchema\DataSchemaBlueprintInterface;

class DataSchemaBlueprint implements DataSchemaBlueprintInterface
{

    protected string $primaryKey;
    protected array $attributes = array();

    /**
     * Set the table primary key
     *
     * @param string $primaryKey
     * @return void
     */
    private function setPrimaryKey(string $primaryKey): void
    {
        $this->primaryKey = $primaryKey;
    }

    /**
     * Automatically generated the auto increment id column for each table
     * if no default name is set this method will assume your primary key
     * field will be called generic `id`.
     *
     * @param string|null $name
     * @param integer $length
     * @return array
     */
    public function autoID(string $name = 'id', int $length = 10): array
    {
        $this->setPrimaryKey($name);
        return $this->int($name, $length, false, 'unsigned', 'none', true);
    }

    /**
     * Return the auto generated primary key field which allows us to use 
     * else where within the class
     *
     * @return string
     */
    public function getPrimaryKey(): string
    {
        return $this->primaryKey;
    }

    /**
     * create an varchar based row. with optiona length/value assignment
     *
     * @param string $name
     * @param integer $length
     * @param boolean $null
     * @param mixed $default
     * @return array
     */
    public function varchar(string $name, int $length = 196, bool $null = false, mixed $default = null): array
    {
        return [
            StringType::class => ['name' => $name, 'type' => 'varchar', 'length' => $length, 'null' => $null, 'default' => $default],
        ];
    }

    /**
     * create an text based row.
     *
     * @param string $name
     * @param boolean $null
     * @param mixed $default
     * @return array
     */
    public function text(string $name, bool $null = false, mixed $default = null): array
    {
        return [
            StringType::class => ['name' => $name, 'type' => 'text', 'null' => $null, 'default' => $default],
        ];
    }

    /**
     * create an longtext based row.
     *
     * @param string $name
     * @param boolean $null
     * @param mixed $default
     * @return array
     */
    public function longText(string $name, bool $null = false, mixed $default = null): array
    {
        return [
            StringType::class => ['name' => $name, 'type' => 'longtext', 'null' => $null, 'default' => $default],
        ];
    }

    /**
     * create an tinytext based row.
     *
     * @param string $name
     * @param boolean $null
     * @param mixed $default
     * @return array
     */
    public function tinyText(string $name, bool $null = false, mixed $default = null): array
    {
        return [
            StringType::class => ['name' => $name, 'type' => 'tinytext', 'null' => $null, 'default' => $default],
        ];
    }

    /**
     * Return the json field type
     *
     * @param string $name
     * @return array
     */
    public function json(string $name): array
    {
        return [
            JsonType::class => ['name' => $name, 'type' => 'json']
        ];
    }

    /**
     * create an integre based row. Length field is set to null, so this is a required
     * argument which must be set within the class which is using this method
     *
     * @param string $name
     * @param integer $length
     * @param boolean $null
     * @param string $attributes
     * @param mixed $default
     * @param boolean $autoIncrement - defautls to false
     * @return void
     */
    public function int(string $name, int $length = null, bool $null = true, string $attributes = 'unsigned', mixed $default = null, bool $autoIncrement = false): array
    {
        return [
            NumericType::class => ['name' => $name, 'type' => 'int', 'length' => $length, 'null' => $null, 'default' => $default, 'attributes' => $attributes, 'auto_increment' => $autoIncrement],
        ];

    }

    /**
     * Undocumented function
     *
     * @param string $name
     * @param boolean $null
     * @param string $default
     * @param string $attributes
     * @return array
     */
    public function datetime(string $name, bool $null = false, string $default = 'ct', string $attributes = ''): array
    {
        return [
            DatetimeType::class => ['type' => 'datetime', 'name' => $name, 'null' => $null, 'default' => $default, 'attributes' => $attributes]        
        ];

    }

    /**
     * Undocumented function
     *
     * @return array
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

}
