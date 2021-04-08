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
use MagmaCore\DataSchema\AbstractDataSchema;
use MagmaCore\DataSchema\DataSchemaBlueprint;
use MagmaCore\DataSchema\Exception\DataSchemaUnexpectedValueException;

class DataSchema extends AbstractDataSchema
{

    /** @var traits */
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
    protected string $tableSchema = '';

    /**
     * Main class constructor
     *
     * @param DataSchemaBlueprint $blueprint
     * @return void
     */
    public function __construct()
    {
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
            $args = (array)$args;
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
    public function build(callable $callback): string
    {
        if (is_array($this->schemaObject) && count($this->schemaObject) > 0) {
            $this->element .= "CREATE TABLE IF NOT EXISTS `{$_ENV['DB_NAME']}`.`{$this->dataModel->getSchema()}` (\n";
            foreach ($this->schemaObject as $schema) {
                $this->element .= $schema->render();
            }
            if (is_callable($callback)) {
                $this->element .= call_user_func_array($callback, [$this]);
            }
            $this->element .= ')';
            $this->element .= $this->getSchemaAttr();
            $this->element .= "\n";
        }
        return $this->element;
    }

    public function drop(): string
    {
        return "DROP TABLE " . $this->dataModel->getSchema();
    }
}
