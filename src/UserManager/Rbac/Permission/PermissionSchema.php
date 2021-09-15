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

namespace MagmaCore\UserManager\Rbac\Permission;

use MagmaCore\DataSchema\DataSchema;
use MagmaCore\DataSchema\DataSchemaBlueprint;
use MagmaCore\DataSchema\DataSchemaBuilderInterface;

class PermissionSchema implements DataSchemaBuilderInterface
{

    /** @var object - $schema for chaining the schema together */
    protected object $schema;
    /** @var object - provides helper function for quickly adding schema types */
    protected object $blueprint;
    /** @var object - the database model this schema is linked to */
    protected object $permissionModel;

    /**
     * Main constructor class. Any typed hinted dependencies will be autowired. As this
     * class can be inserted inside a dependency container
     *
     * @param DataSchema $schema
     * @param DataSchemaBlueprint $blueprint
     * @param PermissionModel $permissionModel
     * @return void
     */
    public function __construct(DataSchema $schema, DataSchemaBlueprint $blueprint, PermissionModel $permissionModel)
    {
        $this->schema = $schema;
        $this->blueprint = $blueprint;
        $this->permissionModel = $permissionModel;
    }

    /**
     * @inheritdoc
     * @return string
     */
    public function createSchema(): string
    {
        return $this->schema
            ->schema()
            ->table($this->permissionModel)
            ->row($this->blueprint->autoID())
            ->row($this->blueprint->varchar('permission_name', 64))
            ->row($this->blueprint->varchar('permission_description', 190))
            ->row($this->blueprint->int('created_byid', 10, false))
            ->row($this->blueprint->datetime('created_at', false))
            ->row($this->blueprint->datetime('modified_at', true, 'null', 'on update CURRENT_TIMESTAMP'))
            ->build(function ($schema) {
                return $schema
                    ->addPrimaryKey($this->blueprint->getPrimaryKey())
                    ->setUniqueKey(['permission_name'])
                    ->addKeys();
            });
    }
}
