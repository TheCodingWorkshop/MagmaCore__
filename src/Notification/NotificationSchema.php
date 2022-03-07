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

namespace MagmaCore\Notification;

use MagmaCore\DataSchema\DataSchema;
use MagmaCore\DataSchema\DataSchemaBlueprint;
use MagmaCore\DataSchema\DataSchemaBuilderInterface;

class NotificationSchema implements DataSchemaBuilderInterface
{

    /** @var object - $schema for chaining the schema together */
    protected object $schema;
    /** @var object - provides helper function for quickly adding schema types */
    protected object $blueprint;
    /** @var object - the database model this schema is linked to */
    protected object $model;

    /**
     * Main constructor class. Any typed hinted dependencies will be autowired. As this
     * class can be inserted inside a dependency container
     *
     * @param DataSchema $schema
     * @param DataSchemaBlueprint $blueprint
     * @param NotificationModel $model
     * @return void
     */
    public function __construct(DataSchema $schema, DataSchemaBlueprint $blueprint, NotificationModel $model)
    {
        $this->schema = $schema;
        $this->blueprint = $blueprint;
        $this->model = $model;
    }

    /**
     * @inheritdoc
     * @return string
     */
    public function createSchema(): string
    {
        return $this->schema
            ->schema()
            ->table($this->model)
            ->row($this->blueprint->autoID())
            ->row($this->blueprint->varchar('menu_name', 100))
            ->row($this->blueprint->text('menu_description'))
            ->row($this->blueprint->text('menu_description'))
            ->row($this->blueprint->datetime('created_at', false))
            ->row($this->blueprint->datetime('modified_at', true, 'null', 'on update CURRENT_TIMESTAMP'))
            ->build(function ($schema) {
                return $schema
                    ->addPrimaryKey($this->blueprint->getPrimaryKey())
                    ->setUniqueKey(['menu_name'])
                    ->addKeys();
            });
    }
}


