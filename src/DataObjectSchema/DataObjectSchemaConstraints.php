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

use MagmaCore\DataObjectSchema\Exception\DataObjectSchemaInvalidArgumentException;

class DataObjectSchemaConstraints
{ 

    protected ?Object $schema = null;
    protected array $args;
    protected const CONSTRAINTS = [
        'foreign_key', 
        'table_ref', 
        'column_ref', 
        'cascade_delete', 
        'cascade_update'
    ];

    /**
     * Undocumented function
     *
     * @param Object $schema
     * @param array $args
     */
    public function __construct(Object $schema = null, array $args)
    {
        $this->schema = $schema;
        $this->args = $args;
    }

    public function getConstraints()
    {
        if (!empty($this->args) && count($this->args) > 0) {    
            $segment = '';
            foreach ($this->args as $key => $value) {
                if (isset($key) && $key !=='') {
                    if ($key === 'primary_key') {
                        $segment .= $this->getPrimaryKey($value);
                    }
                    if ($key === 'unique_key') {
                        $segment .= $this->getUniqueKey($value);
                    }
                    if ($key === 'constraint') {
                        $segment .= $this->getConstraint($value);
                    }
                }
            }
        }

        if (isset($segment) && $segment !=='') {
            return $segment;
        }
    }

    protected function isKeyEmpty($value) : void
    {
        if (empty($value)) {
            throw new DataObjectSchemaInvalidArgumentException();
        }
    }

    /**
     * Undocumented function
     *
     * @param string $value
     * @return string
     */
    public function getPrimaryKey(string $value) : string
    {
        return "PRIMARY KEY (`{$value}`)";
    }

    /**
     * Undocumented function
     *
     * @param mixed $value
     * @return string
     */
    public function getUniqueKey($value) : string
    {
        $this->isKeyEmpty($value);
        $output = '';
        if (is_array($value)) {
            foreach ($value as $val) {
                $output .= "UNIQUE KEY `{$val}` (`$val`)";
            }
        } else {
            $output .= "UNIQUE KEY `{$value}` (`$value`)";
        }

        return $output;
    }

    /**
     * Undocumented function
     *
     * @param mixed $value
     * @return string
     */
    public function getConstraint($value) : string
    {
        $this->isKeyEmpty($value);
        $output = '';
        if (is_array($value) && count($value) > 0) {
            foreach ($value as $key => $val) {
                if (isset($key) && $key !=='') {
                    foreach (self::CONSTRAINTS as $constraint) {
                        switch ($constraint) {
                            case 'foreign_key' :
                                $output .= " FOREIGN KEY (``{$val['foreign_key']})";
                                break;
                            case 'table_ref' :
                                $output .= " REFERENCES `{$val['table_ref']}`";
                                break;
                            case 'column_ref' :
                                $output .= "\t(`{$val['column_ref']}`)";
                                break;
                            case 'cascade_delete' :
                                $output .= " ON CASCADE DELETE";
                                break;
                            case 'cascade_update' :
                                $output .= " ON CASCADE UPDATE";
                                break;
                        }
                    }
                }
            }
            return $output;
        }

    }

}