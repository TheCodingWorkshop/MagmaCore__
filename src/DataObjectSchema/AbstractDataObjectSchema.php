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
use MagmaCore\DataObjectSchema\DataObjectSchemaInterface;

abstract class AbstractDataObjectSchema implements DataObjectSchemaInterface
{ 
    
    /** @var Object */
    protected Object $dataModel;
    /** @var array */
    protected array $attributes;
    /** @var array */
    protected array $schemaAttr = [];
    /** @var array */
    protected const SCHEMA = [
        'collate' => 'utf8mb4_unicode_ci',
        'engine' => 'innoDB',
        'charset' => 'utf8mb4',
        'row_format' => 'dynamic'
    ];

    /**
     * Undocumented function
     *
     * @param Object|null $dataModel
     * @param array|null $attributes
     */
    public function __construct(?Object $dataModel, ?array $attributes = null)
    { 
        $this->dataModel = $dataModel;
        $this->attributes = $attributes;
    }

    /**
     * Undocumented function
     *
     * @param mixed $key
     * @return boolean
     */
    protected function isEmptyThrowException($key)
    {
        if (empty($key)) {
            throw new DataObjectSchemaInvalidArgumentException('Invalid or empty schema. Ensure the schema is not empty and is valid.');
        }
    }

    /**
     * Validate the schema
     * 
     * @param string $key
     * @param mixed $value
     * @return bool
     * @throws DataObjectSchemaInvalidArgumentException
     */
    protected function validateSchema(string $key, $value) : bool
    {
        $this->isEmptyThrowException($key);
        switch ($key) {
            case 'collate' :
                if (!in_array($value, ['utf8mb4_unicode_ci'])) {
                    throw new DataObjectSchemaInvalidArgumentException('Invalid collate within schema');
                }
                break;
            case 'engine' :
                if (!in_array($value, ['innoDB'])) {
                    throw new DataObjectSchemaInvalidArgumentException('Invalid engine within schema');
                }
                break;
            case 'charset' :
                if (!in_array($value, ['utf8mb4'])) {
                    throw new DataObjectSchemaInvalidArgumentException('Invalid charset within schema');
                }
                break;
            case 'row_format' :
                if (!in_array($value, ['dynamic'])) {
                    throw new DataObjectSchemaInvalidArgumentException('Invalid row format within schema');
                }
                break;
        }
        $this->schemaAttr[$key] = $value;
        return true;
    }


}