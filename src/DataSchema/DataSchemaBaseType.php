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

use MagmaCore\DataSchema\Traits\DataSchemaTypesTrait;
use MagmaCore\DataSchema\Exception\DataSchemaInvalidArgumentException;

class DataSchemaBaseType implements DataSchemaTypeInterface
{

    use DataSchemaTypesTrait;

    /** @var array */
    protected const SCHEMA_COLUMNS = [
        'name',
        'type',
        'length',
        'collation',
        'attributes',
        'null',
        'default',
        'index',
        'auto_increment',
        'comments'
    ];

    /** @var array */
    protected array $types = [];
    /** @var array */
    protected array $row = [];

    /**
     * Main class constructor
     *
     * @param array $row
     * @param array $types
     * @return void
     */
    public function __construct(array $row, array $types)
    {
        if ($row) {
            $attr = array_merge($row, self::SCHEMA_COLUMNS);
        } else {
            $attr = self::SCHEMA_COLUMNS;
        }
        $this->row = $attr;
        if (is_array($attr)) {
            foreach ($attr as $key => $value) {
                if (!$this->validateSchemaColumns($key, $value)) {
                    $this->validateSchemaColumns($key, self::SCHEMA_COLUMNS[$key]);
                }
            }
        }
    }

    /**
     * @inheritdoc
     * @return array
     */
    public function getSchemaTypes(): array
    {
        return $this->types;
    }

    /**
     * @inheritdoc
     * @return array
     */
    public function getSchemaColumns(): array
    {
        return self::SCHEMA_COLUMNS;
    }

    /**
     * @inheritdoc
     * @return array
     */
    public function getRow(): array
    {
        return $this->row;
    }

    /**
     * @inheritdoc
     * @return string
     */
    public function render(): string
    {
        extract($this->getRow());
        if (empty($type) || empty($name)) {
            throw new DataSchemaInvalidArgumentException('Invalid argument no schema type or name specified.');
        }
        $segment = '';

        /* print the type and name is using length/value concat that in brackets and list specified attribute for row */
        $segment .= $this->_name() . $this->_length() . $this->_attributes();
        /* auto_increment comes under extra apply this is necessary */
        $segment .= $this->_extra();
        /* specify null values to field */
        $segment .= $this->_null();
        /* specify the default for the row */
        $segment .= $this->_default();
        /* comma for next line. we will remove the comma at the end of the query string in the constraint if using or build */
        $segment .= ',';

        return sprintf('%s', $segment);
    }
}
