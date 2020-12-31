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

class UserSchema extends DataObjectSchema implements DataObjectSchemaBuilderInterface
{

    /** */
    public function __construct()
    {
        parent::__construct(\App\Repository\UserRepository::class);
    }

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
                    [
                        'type' => 'int',
                        'name' => 'id',
                        'length' => 10,
                        'null' => false,
                        'attributes' => 'unsigned',
                        'index' => 'primary',
                        'auto_increment' => true
                    ]
                ]
            )
            ->row(
                [
                    StringSchema::class =>
                    [
                        'type' => 'varchar',
                        'name' => 'firstname',
                        'length' => 190,
                        'null' => false
                    ]
                ]
            )
            ->row(
                [
                    StringSchema::class =>
                    [
                        'type' => 'varchar',
                        'name' => 'lastname',
                        'length' => 190,
                        'null' => false
                    ]
                ]
            )
            ->row(
                [
                    StringSchema::class => ['type' => 'varchar', 'name' => 'email', 'length' => 190, 'null' => false]
                ]
            )
            ->row(
                [
                    StringSchema::class => ['type' => 'varchar', 'name' => 'gravatar', 'length' => 190, 'default' => 'NULL']
                ]
            )
            ->row(
                [
                    StringSchema::class => ['type' => 'varchar', 'name' => 'status', 'length' => 24, 'null' => false]
                ]
            )
            ->row(
                [
                    StringSchema::class => ['type' => 'varchar', 'name' => 'password_hash', 'length' => 190, 'null' => true]
                ]
            )
            ->row(
                [
                    StringSchema::class => ['type' => 'varchar', 'name' => 'password_reset_hash', 'length' => 64, 'null' => true]
                ]
            )
            ->row(
                [
                    DateTimeSchema::class => ['type' => 'datetime', 'name' => 'password_reset_expires_at', 'default' => 'NULL']
                ]
            )
            ->row(
                [
                    StringSchema::class => ['type' => 'varchar', 'name' => 'activation_hash', 'length' => 64, 'default' => 'null', 'null' => true]
                ]
            )
            ->row(
                [
                    NumericSchema::class => ['type' => 'int', 'name' => 'created_byid', 'length' => 10, 'null' => false, 'attributes' => 'unsigned']
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
                    StringSchema::class => ['type' => 'varchar', 'name' => 'remote_addr', 'length' => 64, 'null' => true, 'default' => 'null']
                ]
            )

            ->build(
                [
                    'primary_key' => 'id',
                    'unique_key' => 'email',
                    'constraint' => [
                        'permission' => [
                            'foreign_key' => 'permission_id',
                            'table_ref' => 'permissions',
                            'column_ref' => 'id',
                            'cascade_delete' => true,
                            'cascade_update' => true
                        ],
                    ]
                ]
            )->migrateOrIgnore();
    }
}
