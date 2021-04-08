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

use MagmaCore\DataSchema\DataSchemaInterface;
use MagmaCore\DataSchema\Exception\DataSchemaInvalidArgumentException;
use MagmaCore\DataObjectSchema\Exception\DataObjectSchemaInvalidArgumentException;

abstract class AbstractDataSchema implements DataSchemaInterface
{

    /** @var array - database schema settings */
    protected const DEFAULT_SCHEMA = [
        'collate' => 'utf8mb4_unicode_ci',
        'engine' => 'innoDB',
        'charset' => 'utf8mb4',
        'row_format' => 'dynamic'
    ];

    /**
     * Main constructor method
     *
     * @return void
     */
    public function __construct()
    {
    }   

    /**
     * Throw an exception of the key is empty
     *
     * @param mixed $key
     * @return boolean
     */
    protected function isEmptyThrowException($key)
    {
        if (empty($key)) {
            throw new DataSchemaInvalidArgumentException('Invalid or empty schema. Ensure the schema is not empty and is valid.');
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
                    throw new DataSchemaInvalidArgumentException('Invalid collate within schema');
                }
                break;
            case 'engine' :
                if (!in_array($value, ['innoDB', 'MyISAM', 'XtraDB', 'Falcon', 'TokuDB', 'Aria'])) {
                    throw new DataSchemaInvalidArgumentException('Invalid engine within schema');
                }
                break;
            case 'charset' :
                if (!in_array($value, ['utf8mb4'])) {
                    throw new DataSchemaInvalidArgumentException('Invalid charset within schema');
                }
                break;
            case 'row_format' :
                if (!in_array($value, ['dynamic', 'compact', 'redundant', 'compressed'])) {
                    throw new DataSchemaInvalidArgumentException('Invalid row format within schema');
                }
                break;
        }
        $this->schemaAttr[$key] = $value;
        return true;
    }

    /**
     * Return the database engine schema
     *
     * @return string
     */
    public function getSchemaAttr(): string
    {
        return "ENGINE={$this->schemaAttr['engine']} DEFAULT CHARSET={$this->schemaAttr['charset']} COLLATE={$this->schemaAttr['collate']} ROW_FORMAT=" . strtoupper($this->schemaAttr['row_format']);
    }


}