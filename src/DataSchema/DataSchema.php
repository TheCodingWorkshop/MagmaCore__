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

namespace MagmaCore\DataSchema;

use MagmaCore\DataSchema\Traits\DataSchemaTrait;
use MagmaCore\DataSchema\Exception\DataSchemaUnexpectedValueException;

class DataSchema extends AbstractDataSchema
{

    /** @var DataSchemaTrait */
    use DataSchemaTrait;

    /** @var array - contains an array of schema type objects */
    protected array $schemaObject = [];
    /** @var string */
    protected string $element = '';
    /** @var string */
    protected string $primaryKey;
    /** @var string */
    protected mixed $uniqueKey = null;
    /** @var string */
    protected mixed $ukey = null;
    /** @var string */
    protected string $tableSchema = '';
    
    protected const MODIFY = 'modify';
    protected const ADD = 'add';
    protected const CHANGE = 'change';
    protected const DROP = 'drop';
    private object $dataModel;

    /**
     * Main class constructor
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @inheritdoc
     * @param array $overridingSchema
     * @return static
     */
    public function schema(array $overridingSchema = []): static
    {
        if ($overridingSchema) {
            $attr = array_merge(self::DEFAULT_SCHEMA, $overridingSchema);
        } else {
            $attr = self::DEFAULT_SCHEMA;
        }
        if (is_array($attr)) {
            foreach ($attr as $key => $value) {
                if (!$this->validateSchema($key, $value)) {
                    $this->validateSchema($key, self::DEFAULT_SCHEMA[$key]);
                }
            }
        }
        return $this;
    }

    /**
     * @inheritdoc
     * @param object $dataModel
     * @return static
     */
    public function table(object $dataModel): static
    {
        $this->dataModel = $dataModel;
        return $this;
    }

    /**
     * @inheritdoc
     * @param mixed $args
     * @return static
     */
    public function row(mixed $args = null): static
    {
        if (is_array($args)) {
            $args = $args;
            foreach ($args as $schemaObjectType => $schemaObjectOptions) {
                $newSchemaObject = new $schemaObjectType($schemaObjectOptions);
                if (!$newSchemaObject instanceof DataSchemaTypeInterface) {
                    throw new DataSchemaUnexpectedValueException('Invalid dataSchema type supplied. It does not implement the DataSchemaTypeInterface.');
                }
                $this->schemaObject[] = $newSchemaObject;
                return $this;
            }
        }
    }

    /**
     * @inheritdoc
     * @param Callable $callback
     * @return string
     */
    public function build(callable $callback = null): string
    {
        $bt4 = "\t\t\t\t";
        $bt3 = "\t\t\t";
        $eol = PHP_EOL;

        if (is_array($this->schemaObject) && count($this->schemaObject) > 0) {
            $this->element .= "{$bt3}CREATE TABLE IF NOT EXISTS `{$_ENV['DB_NAME']}`.`{$this->dataModel->getSchema()}`" . $eol;
            $this->element .= "{$bt3}(" . $eol;
            foreach ($this->schemaObject as $schema) {
                $this->element .= $bt4 . $schema->render() . $eol;
            }
            if (is_callable($callback)) {
                $this->element .= $bt4 . call_user_func_array($callback, [$this]) . $eol;
            }
            $this->element .= $bt3 . ')' . $eol;
            $this->element .= $bt3 . $this->getSchemaAttr() . $eol;
            $this->element .= $eol;
        }
        return $this->element;
    }

    public function drop(): string
    {
        return "DROP TABLE " . $this->dataModel->getSchema();
    }

    /**
     * The alter statement is used to add, drop or modify an existing database
     * table. It can also be used drop and add constraints on an existing
     * table as well
     *
     * @param string $type
     * @param Callable $callback
     * @return string
     */
    public function alter(string $type, Callable $callback): string
    {
        if (!is_callable($callback)) {
            /* do something */
        }
        if (!in_array($type, ['add', 'drop', 'modify'])) {
            /* throw exception */
        }
        $element = "\t\t\t" . 'ALTER TABLE ' . "`{$this->dataModel->getSchema()}`" . PHP_EOL;
        switch ($type) {
            case 'modify' :
                $element .= "\t\tMODIFY COLUMN " . $callback($this);
                $element = substr_replace($element, '', -3);
                break;
            case 'change' :
                $element .= "\t\tCHANGE COLUMN " . $callback($this);
                $element = substr_replace($element, '', -3);
                break;    
            case 'add' :
                $element .= "\t\t\tADD COLUMN" . $callback($this);
                $element = substr_replace($element, '', -3);
                break;
            case 'drop' :
                $element .= "\t\t\tDROP COLUMN " . $callback($this);
                break;
        }
        $element .= ';';
        $element .= PHP_EOL;
        return $element;
    }

    /**
     * Add a column to an existing database table
     *
     * @return string
     */
    public function addColumn(): string
    {
        foreach ($this->schemaObject as $schema) {
            return $schema->render() . PHP_EOL;
        }

    }

    /**
     * Drop a column from an existing database table
     *
     * @param string $columnName
     * @return string
     */
    public function dropColumn(string $columnName): string
    {
        $dropColumn = $columnName;
        return $dropColumn;
    }

    /**
     * Modify an existing column datatype or column constraints
     *
     * @return string
     */
    public function modifyColumn(): string
    {
        return $this->addColumn();
    }

    /**
     * Change the name of an existing database table column We can also change
     * the datatype and constraints
     *
     * @param string $oldColumnName
     * @return string
     */
    public function changeColumn(string $oldColumnName): string
    {
        return "`{$oldColumnName}`" . ' ' . $this->addColumn();
    }

    /**
     * Drop the specified table from the database
     *
     * @return string
     */
    public function destroy(): string
    {
        return "\t\tDROP TABLE " . $this->dataModel->getSchema() . PHP_EOL;
    }
}
