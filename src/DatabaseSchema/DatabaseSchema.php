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

namespace MagmaCore\DatabaseSchema;

use MagmaCore\DatabaseSchema\AbstractDatabaseSchema;
use MagmaCore\DatabaseSchema\DatabaseSchemaConstraint;
use MagmaCore\DataObjectSchema\Exception\DataObjectSchemaUnexpectedValueException;

class DatabaseSchema extends AbstractDatabaseSchema
{

    /**
     * Undocumented function
     *
     * @param string|null $model
     * @param array|null $attributes
     * @return void
     */
    public function __construct(string|null $model = null, array|null $attributes = [])
    {
        $this->model = $model;
        if (!$this->model) {
            throw new DataObjectSchemaUnexpectedValueException('You need to specify a user model repository.');
        }
        parent::__construct($this->model, $attributes);
        
    }

    /**
     * Undocumented function
     *
     * @param array $schema
     * @return self
     */
    public function schema(array $schema = []): self
    {
        if ($schema) {
            $attr = array_merge(self::SCHEMA, $schema);
        } else {
            $attr = self::SCHEMA;
        }
        if (is_array($attr)) {
            foreach ($attr as $key => $value) {
                if (!$this->validateSchema($key, $value)) {
                    $this->validateSchema($key, self::SCHEMA[$key]);
                }
            }
        }
        return $this;
    }

    /**
     * Undocumented function
     *
     * @param array $args
     * @return self
     */
    public function row(array $args = []): self
    {
        if ($args) {
            foreach ($args as $schemaTypeObject => $schemaObjectOptions) {
                $schemaType = new $schemaTypeObject($schemaObjectOptions);
                if (!$schemaType instanceof DatabaseSchemaTypeInterface) {
                    throw new DataObjectSchemaUnexpectedValueException('Invalid database schema. ' . $schemaType . ' does not implements [DatabaseSchemaTypeInterface]');
                }
                $this->schemaObject[] = $schemaType;
            }
        }
        return $this;
    }

    /**
     * Undocumented function
     *
     * @param array $args
     * @return mixed
     */
    public function table(array $args = []): mixed
    {
        if (is_array($this->schemaObject) && count($this->schemaObject) > 0) {
            $this->element .= "CREATE TABLE IF NOT EXISTS `{$this->model->tableSchema()}`.`{$this->model->tableName()}` (\n";
            foreach ($this->schemaObject as $schema) {
                $this->element .= $schema->build() . ',';
            }
            $this->element .= (new DatabaseSchemaConstraint($schema, $args))->render();
            $this->element .= ")\n";
            if (isset($this->element)) {
                return $this->element;
            }
        }
        return false;
    }

    /**
     * Undocumented function
     *
     * @param string $primaryKey
     * @return self
     */
    public function key(string $primaryKey): self
    { 
        if ($primaryKey) {
            $this->primaryKey = $primaryKey;
        }
        return $this;
    }

    /**
     * Undocumented function
     *
     * @param mixed $unique
     * @return self
     */
    public function unique(mixed $uniqueKey): self
    {
        if (is_array($uniqueKey)) {
            $this->uniqueKey = implode(', ', $uniqueKey);
        }
        $this->uniqueKey = $uniqueKey;
        return $this;
    }

    /**
     * Undocumented function
     *
     * @return string
     */
    public function getPrimaryKey(): string
    {
        return $this->primaryKey;
    }

    /**
     * Undocumented function
     *
     * @return mixed
     */
    public function getUniqueKey(): mixed
    {
        return $this->uniqueKey;
    }

    /**
     * Undocumented function
     *
     * @param string $identifier
     * @param Callable $callback
     * @return void
     */
    public function constraint(string $identifier, Callable $callback = null)
    {
        if ($identifier) {
            if (is_callable($callback) && $callback !==null) {
                call_user_func_array($callback, [$this, DatabaseSchemaConstraint::class]);
            }
        }
        return $this;
    }

    /**
     * Undocumented function
     *
     * @param array $args
     * @return void
     */
    /*public function cascade(array $args = [])
    {
        $allowed = ['onDelete', 'onUpdate'];
        if (count($args) > 0) {
            foreach ($args as $key => $value) {
                if (in_array($key, $allowed, true)) {
                    if ($value === true) {
                        switch ($key) {
                            case 'onDelete' :
                                return " ON CASCADE DELETE";
                                break;
                            case 'onUpdate' :
                                return " ON CASCADE UPDATE";
                                break;
                        }
                    }
                }
            }
        }
    }*/

}