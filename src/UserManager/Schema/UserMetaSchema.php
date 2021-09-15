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

use MagmaCore\UserManager\Model\UserMetaDataModel;
use MagmaCore\DataSchema\DataSchema;
use MagmaCore\DataSchema\DataSchemaBlueprint;
use MagmaCore\DataSchema\DataSchemaBuilderInterface;

class UserMetaSchema implements DataSchemaBuilderInterface
{

    /** @var object - $schema for chaining the schema together */
    protected object $schema;
    /** @var object - provides helper function for quickly adding schema types */
    protected object $blueprint;
    /** @var object - the database model this schema is linked to */
    protected object $userMetaModel;

    /**
     * Main constructor class. Any typed hinted dependencies will be autowired. As this
     * class can be inserted inside a dependency container
     *
     * @param DataSchema $schema
     * @param DataSchemaBlueprint $blueprint
     * @param UserMetaDataModel $userMetaModel
     * @return void
     */
    public function __construct(DataSchema $schema, DataSchemaBlueprint $blueprint,
                                UserMetaDataModel $userMetaModel)
    {
        $this->schema = $schema;
        $this->blueprint = $blueprint;
        $this->userMetaModel = $userMetaModel;
    }

    /**
     * @inheritdoc
     * @return string
     */
    public function createSchema(): string
    {
        return $this->schema
            ->schema()
            ->table($this->userMetaModel)
            ->row($this->blueprint->autoID())
            ->row($this->blueprint->int('user_id', 10))
            ->row($this->blueprint->int('failed_logins', 10, false))
            ->row($this->blueprint->datetime('failed_login_timestamp', false))
            ->row($this->blueprint->datetime('modified_at', true, 'null', 'on update CURRENT_TIMESTAMP'))
            ->row($this->blueprint->varchar('remote_addr', 64, true, 'null'))
            ->build(function ($schema) {
                return $schema
                    ->addPrimaryKey($this->blueprint->getPrimaryKey())
                    ->setUniqueKey(['user_id'])
                    ->addKeys();
            });
    }
}
