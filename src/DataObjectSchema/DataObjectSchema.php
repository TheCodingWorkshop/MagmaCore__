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

use MagmaCore\DataObjectSchema\AbstractDataObjectSchema;
use MagmaCore\DataObjectSchema\DataObjectSchemaConstraints;
use MagmaCore\DataObjectSchema\DataObjectSchemaTypeInterface;
use MagmaCore\DataObjectSchema\Exception\DataObjectSchemaUnexpectedValueException;

class DataObjectSchema extends AbstractDataObjectSchema
{

    /** @var array - stores and array of schema type object */
    protected array $schemaObject = [];

    /** @var string */
    protected string $element = '';

    protected ?Object $dataModelObject = null;

    /**
     * Undocumented function
     *
     * @param Object|null $dataModel
     * @param array|null $attributes
     */
    public function __construct(?string $dataModel = null, ?array $attributes = null)
    {
        $this->dataModelObject = new $dataModel();
        if (!$this->dataModelObject) {
            throw new DataObjectSchemaUnexpectedValueException('You need to specify a user model repository.');
        }
        parent::__construct($this->dataModelObject, $attributes);
    }

    /**
     * Undocumented function
     *
     * @param array $schema
     * @return void
     */
    public function schema(array $schema = []): self
    {
        if ($schema) {
            $attr = array_merge(self::SCHEMA, $schema);
        } else {
            $attr = self::SCHEMA;
        }
        if (is_array($attr)) {
            /* Validate the schema */
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
        if (is_array($args)) {
            foreach ($args as $schemaObjectType => $schemaObjectOptions) {
                $newSchema = new $schemaObjectType($schemaObjectOptions);
                if (!$newSchema instanceof DataObjectSchemaTypeInterface) {
                    throw new DataObjectSchemaUnexpectedValueException('Invalid database schema. ' . $newSchema . ' does not implements [DatabaseSchemaTypeInterface]');
                }
                /* Capture the schema type as an array within this property */
                $this->schemaObject[] = $newSchema;
                return $this;
            }
        }
    }

    /**
     * Undocumented function
     *
     * @param array $args
     * @return void
     */
    public function table(array $args = [])
    {
        if (is_array($this->schemaObject) && count($this->schemaObject) > 0) {
            $this->element .= "CREATE TABLE IF NOT EXISTS `{$this->dataModel->tableSchema()}`.`{$this->dataModel->databaseName()}` (\n";
            foreach ($this->schemaObject as $schema) {
                $this->element .= $schema->build() . ',';
            }
            $this->element .= (new DataObjectSchemaConstraints(
                    $schema,
                    $args
                ))
                ->getConstraints();
            $this->element .= ")\n";
        }
        if (isset($this->element) && !empty($this->element)) {
            return $this->element;
        }
        return false;
    }

}
