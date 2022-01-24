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

namespace MagmaCore\Ticket;

use MagmaCore\DataSchema\DataSchema;
use MagmaCore\DataSchema\DataSchemaBlueprint;
use MagmaCore\DataSchema\DataSchemaBuilderInterface;

class TicketSchema implements DataSchemaBuilderInterface
{

    /** @var object - $schema for chaining the schema together */
    protected object $schema;
    /** @var object - provides helper function for quickly adding schema types */
    protected object $blueprint;
    /** @var object - the database model this schema is linked to */
    protected object $ticketModel;

    /**
     * Main constructor class. Any typed hinted dependencies will be autowired. As this
     * class can be inserted inside a dependency container
     *
     * @param DataSchema $schema
     * @param DataSchemaBlueprint $blueprint
     * @param TicketModel $ticketModel
     * @return void
     */
    public function __construct(DataSchema $schema, DataSchemaBlueprint $blueprint, TicketModel $ticketModel)
    {
        $this->schema = $schema;
        $this->blueprint = $blueprint;
        $this->ticketModel = $ticketModel;
    }

    /**
     * @inheritdoc
     * @return string
     */
    public function createSchema(): string
    {
        return $this->schema
            ->schema()
            ->table($this->userModel)
            ->row($this->blueprint->autoID())
            ->row($this->blueprint->varchar('firstname', 190))
            ->row($this->blueprint->varchar('lastname', 190))
            ->row($this->blueprint->varchar('email', 190))
            ->row($this->blueprint->varchar('gravatar', 190, true, 'null'))
            ->row($this->blueprint->varchar('status', 24))
            ->row($this->blueprint->varchar('password_hash', 190))
            ->row($this->blueprint->varchar('password_reset_hash', 64, true, 'null'))
            ->row($this->blueprint->datetime('password_reset_expires_at', true, 'null'))
            ->row($this->blueprint->varchar('activation_token', 64, true, 'null'))
            ->row($this->blueprint->int('is_admin', 2, false))
            ->row($this->blueprint->int('created_byid', 10, false))
            ->row($this->blueprint->datetime('created_at', false))
            ->row($this->blueprint->datetime('modified_at', true, 'null', 'on update CURRENT_TIMESTAMP'))
            ->row($this->blueprint->varchar('remote_addr', 64, true, 'null'))
            ->build(function ($schema) {
                return $schema
                    ->addPrimaryKey($this->blueprint->getPrimaryKey())
                    ->setUniqueKey(['email', 'password_reset_hash', 'activation_token'])
                    ->addKeys();
            });
    }
}
