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

namespace MagmaCore\DataObjectSchema\Type;

use MagmaCore\DataObjectSchema\DataObjectSchemaTypeInterface;
use MagmaCore\DataObjectSchema\Exception\DataObjectSchemaInvalidArgumentException;
use Throwable;

class BaseSchema implements DataObjectSchemaTypeInterface
{

    /** @var array */
    protected const SCHEMA_COLUMNS = [
        'name',
        'type',
        'length',
        'default',
        'attributes',
        'nullable',
        'index',
        'auto_increment',
        'comments'
    ];

    /** @var array */
    protected const NULLABLE_VALUES = ['not'];

    /** @var array */
    protected array $types = [];
    /** @var array */
    protected array $row = [];

    /**
     * Main constructor class
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
        if (is_array($attr) && !empty($attr)) {
            foreach ($attr as $key => $value) {
                if (!$this->validateSchemaColumns($key, $value)) {
                $this->validateSchemaColumns($key, self::SCHEMA_COLUMNS[$key]);
                }
            }
        }
        $this->types = $types;

    }

    /**
     * Validate the schema
     * 
     * @param string $key
     * @param mixed $value
     * @return bool
     * @throws DataObjectSchemaInvalidArgumentException
     */
    private function validateSchemaColumns($key, $value)
    {
        switch ($key) {
            case 'name' :
            case 'comments' :
                if (!is_string($value)) {
                    throw new DataObjectSchemaInvalidArgumentException('test');
                }
                break;
            case 'type' :
                if (!in_array($value, $this->types)) {
                    throw new DataObjectSchemaInvalidArgumentException('Invalid argument type in method <strong>' . $value . '</strong>' );
                }
                break;
            case 'length' :
                if (!is_int($value)) {
                    throw new DataObjectSchemaInvalidArgumentException();
                }
                break;
            case 'index' :
                if (!in_array($value, ['primary', 'unique', 'index', 'fulltext', 'spatial'])) {
                    throw new DataObjectSchemaInvalidArgumentException();
                }
                break;
            case 'auto_increment' :
                if (!is_bool($value)) {
                    throw new DataObjectSchemaInvalidArgumentException();
                }
                break;
            case 'nullable' :
                if (!in_array($value, self::NULLABLE_VALUES)) {
                    throw new DataObjectSchemaInvalidArgumentException();
                }
                break;
            case 'attributes' :
                if (!in_array($value, ['binary', 'unsigned', 'unsigned zerofill', 'on update current_timestamp'])) {
                    throw new DataObjectSchemaInvalidArgumentException();
                }
                break;
        }
        $this->row[$key] = $value;
        return true;
    }

    /**
     * Return the string schema types array
     * @return array
     */
    public function getSchemaTypes()
    {
        return $this->types;
    }

    /**
     * Returns all the columns which makes up each DataObject row
     * @return array
     */
    public function getSchemaColumns()
    {
        return self::SCHEMA_COLUMNS;
    }

    /**
     * Get the validated row specified with the schema classes
     * @return array
     */
    public function getRow()
    {
        return $this->row;
    }

    protected function getNameAttr() : string
    {
        extract ($this->getRow());
        if ($name) {
            return "`{$name}` {$type}";
        }
    }

    protected function getLengthAttr() : string
    {
        extract ($this->getRow());
        return (isset($length) && $length !==0) ? "({$length})" : "";
    }

    protected function getAutoIncrementAttr() : string
    {
        extract ($this->getRow());
        return (isset($auto_increment) && $auto_increment === true) ? ' AUTO_INCREMENT' : '';
    }
    
    protected function getAttributesAttr() : string
    {
        extract ($this->getRow());
        return (isset($attributes) && $attributes !=='') ? ' ' . strtoupper($attributes) . ' ' : '';
    }

    protected function getNullableAttr() : ?string
    {
        extract ($this->getRow());
        if (isset($nullable) && is_string($nullable)) {
            switch ($nullable) {
                case 'null' :
                    return ' NULL';
                    break;
                case 'not' :
                    return ' NOT NULL';
                    break;
                default :
                    return '';
                    break;
            }
        } else {
            return null;
        }
    }

    protected function getDefaultAttr() : ?string
    {
        extract ($this->getRow());
        if (isset($default) && $default !== 'none') {
            return $default;
        } else {
            return null;
        }
    }

    /**
     * @inheritdoc
     */
    public function build() : string
    {
        try {
            extract ($this->getRow());
            /* type and name is absolutely required */
            if (empty($type) || empty($name)) {
                throw new DataObjectSchemaInvalidArgumentException('Invalid Argument no type or name set for');
            }
            $segment = '';
            $segment .= $this->getNameAttr();
            $segment .= $this->getLengthAttr();
            $segment .= $this->getAttributesAttr();
            $segment .= $this->getAutoIncrementAttr();
            if ($this->getDefaultAttr()) {
                switch ($this->getDefaultAttr()) {
                    case 'null' :
                        $segment .= ' DEFAULT NULL';
                        break;
                    case 'ct' :
                        $segment .= ' DEFAULT CURRENT_TIMESTAMP';
                        break;
                    default :
                        $segment .= ' DEFAULT ' . $this->getDefaultAttr();
                        break;
                }
            }
            $segment .= $this->getNullableAttr();

            return sprintf('%s', $segment);

        } catch(Throwable $th) {
            throw $th;   
        }
    }

}