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

namespace MagmaCore\System\App\Schema;

use MagmaCore\System\App\Model\EventModel;
use MagmaCore\DataSchema\DataSchema;
use MagmaCore\DataSchema\DataSchemaBlueprint;
use MagmaCore\DataSchema\DataSchemaBuilderInterface;

class EventSchema implements DataSchemaBuilderInterface
{

    /** @var object - $schema for chaining the schema together */
    protected object $schema;
    /** @var object - provides helper function for quickly adding schema types */
    protected object $blueprint;
    /** @var object - the database model this schema is linked to */
    protected object $eventModel;

    /**
     * Main constructor class. Any typed hinted dependencies will be autowired. As this
     * class can be inserted inside a dependency container
     *
     * @param DataSchema $schema
     * @param DataSchemaBlueprint $blueprint
     * @param EventModel $eventModel
     * @return void
     */
    public function __construct(DataSchema $schema, DataSchemaBlueprint $blueprint, EventModel $eventModel)
    {
        $this->schema = $schema;
        $this->blueprint = $blueprint;
        $this->eventModel = $eventModel;
    }

    /**
     * @inheritdoc
     * @return string
     */
    public function createSchema(): string
    {
        return $this->schema
            ->schema()
            ->table($this->eventModel)
            ->row($this->blueprint->autoID())
            ->row($this->blueprint->varchar('event_log_name', 10))
            ->row($this->blueprint->varchar('event_type', 100))
            ->row($this->blueprint->tinyText('source'))
            ->row($this->blueprint->int('user', 10))
            ->row($this->blueprint->varchar('method', 100))
            ->row($this->blueprint->longText('event_context'))
            ->row($this->blueprint->longText('event_browser'))
            ->row($this->blueprint->int('IP', 4))
            ->row($this->blueprint->datetime('created_at', false))
            ->build(function ($schema) {
                return $schema
                    ->addPrimaryKey($this->blueprint->getPrimaryKey())
                    ->addKeys();
            });
    }
}


