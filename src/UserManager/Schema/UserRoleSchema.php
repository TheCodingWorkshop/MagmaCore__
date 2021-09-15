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

namespace MagmaCore\UserManager\Schema;

use MagmaCore\UserManager\Model\RoleModel;
use MagmaCore\UserManager\UserModel;
use MagmaCore\UserManager\Model\UserRoleModel;
use MagmaCore\DataSchema\DataSchema;
use MagmaCore\DataSchema\DataSchemaBlueprint;
use MagmaCore\DataSchema\DataSchemaBuilderInterface;

class UserRoleSchema implements DataSchemaBuilderInterface
{

    /** @var object - $schema for chaining the schema together */
    protected object $schema;
    /** @var object - provides helper function for quickly adding schema types */
    protected object $blueprint;
    /** @var object - the database model this schema is linked to */
    protected object $userRoleModel;
    /** @var string */
    private const FIRST_COLUMN = 'user_id';
    /** @var string */
    private const SECOND_COLUMN = 'role_id';

    /**
     * Main constructor class. Any typed hinted dependencies will be autowired. As this
     * class can be inserted inside a dependency container
     *
     * @param DataSchema $schema
     * @param DataSchemaBlueprint $blueprint
     * @param UserRoleModel $userRoleModel
     * @return void
     */
    public function __construct(DataSchema $schema, DataSchemaBlueprint $blueprint, UserRoleModel $userRoleModel)
    {
        $this->schema = $schema;
        $this->blueprint = $blueprint;
        $this->userRoleModel = $userRoleModel;
    }

    /**
     * @inheritdoc
     * @return string
     */
    public function createSchema(): string
    {
        return $this->schema
            ->schema()
            ->table($this->userRoleModel)
            ->row($this->blueprint->int(self::FIRST_COLUMN, 10))
            ->row($this->blueprint->int(self::SECOND_COLUMN, 10))
            ->build(function ($schema) {
                return $schema
                    ->setKey([self::FIRST_COLUMN, self::SECOND_COLUMN])
                    ->setConstraints(
                        function ($trait) {
                            $constraint = $trait->addModel(UserModel::class)
                                ->foreignKey(self::FIRST_COLUMN)
                                ->on($trait->getModel()->getSchema())
                                ->reference($trait->getModel()->getSchemaID())
                                ->cascade(true, true)->add();
                            $constraint .= $trait->addModel(RoleModel::class)
                                ->foreignKey(self::SECOND_COLUMN)
                                ->on($trait->getModel()->getSchema())
                                ->reference($trait->getModel()->getSchemaID())
                                ->cascade(true, true)->add();
                            return $constraint;
                        }
                    );
            });
    }
}
