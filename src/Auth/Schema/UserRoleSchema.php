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

use MagmaCore\DataObjectSchema\DataObjectSchemaBuilderInterface;
use MagmaCore\DataObjectSchema\Type\NumericSchema;
use MagmaCore\DataObjectSchema\DataObjectSchema;

class UserRoleSchema extends DataObjectSchema implements DataObjectSchemaBuilderInterface
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
                    NumericSchema::class => ['type' => 'int', 'name' => 'user_id', 'length' => 10, 'null' => false,'attributes' => 'unsigned']
                ]
            )
            ->row(
                [
                    NumericSchema::class => ['type' => 'int', 'name' => 'role_id', 'length' => 10, 'null' => false, 'attributes' => 'unsigned']
                ]
            )

            ->build(
                [
                    'unique_key' => ['user_id', 'role_id'],
                    'constraint' => [
                        'user_id' => [
                            'foreign_key' => 'user_id',
                            'table_ref' => 'users',
                            'column_ref' => 'id',
                            'cascade_delete' => true,
                            'cascade_update' => true
                        ],
                        'user_role_id' => [
                            'foreign_key' => 'role_id',
                            'table_ref' => 'roles',
                            'column_ref' => 'id',
                            'cascade_delete' => true,
                            'cascade_update' => true

                        ]
                    ]
                ]
            )->migrateOrIgnore();
    }
}
