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

use MagmaCore\DataSchema\DataSchemaTypeInterface;
use MagmaCore\DataSchema\Exception\DataSchemaInvalidArgumentException;

class DataSchemaBaseType implements DataSchemaTypeInterface
{

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

    protected array $types = [];
    protected array $row = [];

    /**
     * Undocumented function
     *
     * @param array $row
     * @param array $types
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

    public function validateSchemaColumns(mixed $key, mixed $value)
    {
        switch ($key) {
            case 'name' :
                if ($value === '') {
                    throw new DataSchemaInvalidArgumentException('');
                }
                break;
            case 'type' :
                if (!in_array($value, $this->types)) {
                    throw new DataSchemaInvalidArgumentException('');
                }
                break;
            case 'length' :
                if (!is_int($value)) {
                    throw new DataSchemaInvalidArgumentException('');
                }
                break;
            case 'index' :
                if (!in_array($value, ['primary', 'unique', 'index', 'fulltext'])) {
                    throw new DataSchemaInvalidArgumentException('');
                }
                break;
            case 'auto_increment' :
                if (!is_bool($value)) {
                    throw new DataSchemaInvalidArgumentException('');
                }
                break;
            case 'null' :
                if (!is_bool($value)) {
                    throw new DataSchemaInvalidArgumentException('');
                }
                break;
            case 'default' :
                if (!in_array($value, ['none', 'null', 'CURRENT_TIMESTAMP', $value])) {
                    throw new DataSchemaInvalidArgumentException('');
                }
                break;
            case 'attributes' :
                if (!in_array($value, ['binary', 'unsigned', 'unsigned zerofill', 'on update CURRENT_TIMESTAMP'])) {
                    throw new DataSchemaInvalidArgumentException('');
                }
                break;
        }
        $this->row[$key] = $value;
        return true;

    }

    /**
     * Undocumented function
     *
     * @return array
     */
    public function getSchemaTypes(): array
    {
        return $this->types;
    }

    /**
     * Undocumented function
     *
     * @return array
     */
    public function getSchemaColumns(): array
    {
        return self::SCHEMA_COLUMNS;
    }

    /**
     * Undocumented function
     *
     * @return array
     */
    public function getRow(): array
    {
        return $this->row;
    }

    public function _name(): string
    {
        extract ($this->getRow());
        if ($name)
            return "`{$name}` {$type}";
    }

    public function _length(): string
    {
        extract ($this->getRow());
        return (isset($length) && $length !==0) ? "({$length})" : '';
    }

    public function _extra()
    {
        extract($this->getRow());
        return (isset($auto_increment) && $auto_increment === true) ? ' AUTO_INCREMENT' : '';

    }

    public function _attributes()
    {
        extract($this->getRow());
        return (isset($attributes) && $attributes !=='') ? ' ' . strtoupper($attributes) . ' ' : '';
    }

    public function _null()
    {
        extract($this->getRow());
        return (isset($null) && $null === false) ? ' NOT NULL' : '';
    }

    public function _default()
    {
        extract($this->getRow());
        if (isset($default)) {
            switch ($default) {
                case 'none' :
                case 'null' :
                    return ' DEFAULT NULL';
                    break;
                case 'ct' :
                    return ' DEFAULT CURRENT_TIMESTAMP';
                    break;
                default :
                    return ' DEFAULT ' . $default;
                    break;
            }
        }
    }

    public function render(): string
    {
        extract ($this->getRow());
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