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

namespace MagmaCore\Auth\Schema;

use MagmaCore\DataObjectSchema\DataObjectSchema;
use MagmaCore\DataObjectSchema\DataObjectSchemaBuilderInterface;
use MagmaCore\DataObjectSchema\Type\StringSchema;
use MagmaCore\DataObjectSchema\Type\NumericSchema;
use MagmaCore\DataObjectSchema\Type\DateTimeSchema;

class PermissionSchema extends DataObjectSchema implements DataObjectSchemaBuilderInterface
{

    /** @return void */
    public function __construct()
    { }

    /**
     * @inheritdoc
     * @return string
     */
    public function createSchema(): string
    {

        return $this
            ->schema()
            ->row(
                [
                    NumericSchema::class =>
                    ['type' => 'int', 'name' => 'id', 'length' => 10, 'null' => false, 'attributes' => 'unsigned', 'index' => 'primary', 'auto_increment' => true]
                ]
            )
            ->row(
                [
                    StringSchema::class => ['type' => 'varchar', 'name' => 'permission_name', 'length' => 64, 'null' => false]
                ]
            )
            ->row(
                [
                    StringSchema::class => ['type' => 'text', 'name' => 'permission_description', 'null' => false]
                ]
            )
            ->row(
                [
                    DateTimeSchema::class => ['type' => 'datetime', 'name' => 'created_at', 'null' => false, 'default' => 'ct']
                ]
            )
            ->row(
                [
                    DateTimeSchema::class => ['type' => 'datetime', 'name' => 'modified_at', 'default' => 'null', 'null' => true, 'attributes' => 'on update current_timestamp']
                ]
            )
            ->row(
                [
                    NumericSchema::class => ['type' => 'int', 'name' => 'created_byid', 'length' => 10, 'null' => false, 'attributes' => 'unsigned']
                ]
            )
            ->build(['primary_key' => 'id', 'unique_key' => 'permission_name'])
            ->migrateOrIgnore();
    }
}
