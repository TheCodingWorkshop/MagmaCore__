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

use MagmaCore\UserManager\UserModel;
use MagmaCore\UserManager\Model\UserNoteModel;
use MagmaCore\DataSchema\DataSchema;
use MagmaCore\DataSchema\DataSchemaBlueprint;
use MagmaCore\DataSchema\DataSchemaBuilderInterface;

class UserNoteSchema implements DataSchemaBuilderInterface
{

    /** @var object - $schema for chaining the schema together */
    protected object $schema;
    /** @var object - provides helper function for quickly adding schema types */
    protected object $blueprint;
    /** @var object - the database model this schema is linked to */
    protected object $userNoteModel;

    /**
     * Main constructor class. Any typed hinted dependencies will be autowired. As this
     * class can be inserted inside a dependency container
     *
     * @param DataSchema $schema
     * @param DataSchemaBlueprint $blueprint
     * @param UserNoteModel $userNoteModel
     * @return void
     */
    public function __construct(DataSchema $schema, DataSchemaBlueprint $blueprint,
                                UserNoteModel $userNoteModel)
    {
        $this->schema = $schema;
        $this->blueprint = $blueprint;
        $this->userNoteModel = $userNoteModel;
    }

    /**
     * One to Many Relationship
     * @inheritdoc
     * @return string
     */
    public function createSchema(): string
    {
        return $this->schema
            ->schema()
            ->table($this->userNoteModel)
            ->row($this->blueprint->autoID())
            ->row($this->blueprint->int('user_id', 10))
            ->row($this->blueprint->longText('notes', false))
            ->row($this->blueprint->datetime('created_at', false))
            ->row($this->blueprint->datetime('modified_at', true, 'null', 'on update CURRENT_TIMESTAMP'))
            ->build(function ($schema) {
                return $schema
                    ->addPrimaryKey($this->blueprint->getPrimaryKey())
                    ->setUniqueKey(['user_id'])
                    ->setConstraints(
                        function ($trait) {
                            return $trait->addModel(UserModel::class)->foreignKey('user_id')
                                ->on($trait->getModel()->getSchema())->reference($trait->getModel()->getSchemaID())
                                ->cascade(true, true)->add();
                        }
                    );
            });
    }
}
