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

namespace MagmaCore\PluginPanel;

use MagmaCore\PluginPanel\PluginModel;
use MagmaCore\DataSchema\DataSchema;
use MagmaCore\DataSchema\DataSchemaBlueprint;
use MagmaCore\DataSchema\DataSchemaBuilderInterface;

class PluginSchema implements DataSchemaBuilderInterface
{

    /** @var object - $schema for chaining the schema together */
    protected object $schema;
    /** @var object - provides helper function for quickly adding schema types */
    protected object $blueprint;
    /** @var object - the database model this schema is linked to */
    protected object $pluginModel;

    /**
     * Main constructor class. Any typed hinted dependencies will be autowired. As this
     * class can be inserted inside a dependency container
     *
     * @param DataSchema $schema
     * @param DataSchemaBlueprint $blueprint
     * @param ProjectModel $pluginModel
     * @return void
     */
    public function __construct(DataSchema $schema, DataSchemaBlueprint $blueprint, PluginModel $pluginModel)
    {
        $this->schema = $schema;
        $this->blueprint = $blueprint;
        $this->pluginModel = $pluginModel;
    }

    /**
     * @inheritdoc
     * @return string
     */
    public function createSchema(): string
    {
        // return $this->schema
        //     ->schema()
        //     ->table($this->projectModel)
        //     ->row($this->blueprint->autoID())
        //     ->row($this->blueprint->varchar('name', 64))
        //     ->row($this->blueprint->varchar('client', 190))
        //     ->row($this->blueprint->varchar('coordinator', 64))
        //     ->row($this->blueprint->longtext('description'))
        //     ->row($this->blueprint->varchar('duration', 20))
        //     ->row($this->blueprint->varchar('cost', 10))
        //     ->row($this->blueprint->varchar('location', 60))
        //     ->row($this->blueprint->varchar('type', 60))
        //     ->row($this->blueprint->varchar('status', 10))/* open or close */
        //     ->row($this->blueprint->int('created_byid', 10, false))
        //     ->row($this->blueprint->datetime('created_at', false))
        //     ->row($this->blueprint->datetime('modified_at', true, 'null', 'on update CURRENT_TIMESTAMP'))
        //     ->build(function ($schema) {
        //         return $schema
        //             ->addPrimaryKey($this->blueprint->getPrimaryKey())
        //             ->setUniqueKey(['name', 'type'])
        //             ->addKeys();
        //     });
    }
}
