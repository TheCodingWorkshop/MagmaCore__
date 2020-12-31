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
use MagmaCore\DataObjectSchema\Type\NumericSchema;

class RolePermissionSchema extends DataObjectSchema implements DataObjectSchemaBuilderInterface
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
                    NumericSchema::class => ['type' => 'int', 'name' => 'id', 'length' => 10, 'null' => false, 'attributes' => 'unsigned', 'index' => 'primary', 'auto_increment' => true]
                ]
            )
            ->row(
                [
                    NumericSchema::class => ['type' => 'int', 'name' => 'role_id', 'length' => 10, 'null' => false, 'attributes' => 'unsigned']
                ]
            )
            ->row(
                [
                    NumericSchema::class => ['type' => 'int', 'name' => 'permission_id', 'length' => 10, 'null' => false, 'attributes' => 'unsigned']
                ]
            )
            ->build(['primary_key' => 'id', 'unique_key' => ['role_id', 'permission_id']])->migrateOrIgnore();
    }
}
