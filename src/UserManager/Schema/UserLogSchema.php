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

use MagmaCore\UserManager\Model\UserLogModel;
use MagmaCore\DataSchema\DataSchema;
use MagmaCore\DataSchema\DataSchemaBlueprint;
use MagmaCore\DataSchema\DataSchemaBuilderInterface;

class UserLogSchema implements DataSchemaBuilderInterface
{

    /** @var object - $schema for chaining the schema together */
    protected object $schema;
    /** @var object - provides helper function for quickly adding schema types */
    protected object $blueprint;
    /** @var object - the database model this schema is linked to */
    protected object $userLogModel;

    /**
     * Main constructor class. Any typed hinted dependencies will be autowired. As this
     * class can be inserted inside a dependency container
     *
     * @param DataSchema $schema
     * @param DataSchemaBlueprint $blueprint
     * @param UserLogModel $userLogModel
     * @return void
     */
    public function __construct(DataSchema $schema, DataSchemaBlueprint $blueprint,
                                UserLogModel $userLogModel)
    {
        $this->schema = $schema;
        $this->blueprint = $blueprint;
        $this->userLogModel = $userLogModel;
    }

    /**
     * @inheritdoc
     * @return string
     */
    public function createSchema(): string
    {
        return $this->schema
            ->schema()
            ->table($this->userLogModel)
            ->row($this->blueprint->autoID())
            ->row($this->blueprint->int('user_id', 10))
            ->row($this->blueprint->int('level', 10, false))
            ->row($this->blueprint->varchar('level_name', 100))
            ->row($this->blueprint->text('message'))
            ->row($this->blueprint->longText('context'))
            ->row($this->blueprint->datetime('created_at', false))
            ->build(function ($schema) {
                return $schema
                    ->addPrimaryKey($this->blueprint->getPrimaryKey())
                    ->addKeys();
            });
    }
}
