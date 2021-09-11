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

namespace MagmaCore\Settings;

use MagmaCore\DataSchema\DataSchema;
use MagmaCore\DataSchema\DataSchemaBlueprint;
use MagmaCore\DataSchema\DataSchemaBuilderInterface;

class SettingsSchema implements DataSchemaBuilderInterface
{

    /** @var object - $schema for chaining the schema together */
    protected object $schema;
    /** @var object - provides helper function for quickly adding schema types */
    protected object $blueprint;
    /** @var object - the database model this schema is linked to */
    protected object $settingModel;

    /**
     * Main constructor class. Any typed hinted dependencies will be autowired. As this
     * class can be inserted inside a dependency container
     *
     * @param DataSchema $schema
     * @param DataSchemaBlueprint $blueprint
     * @param SettingModel $settingModel
     * @return void
     */
    public function __construct(DataSchema $schema, DataSchemaBlueprint $blueprint, SettingModel $settingModel)
    {
        $this->schema = $schema;
        $this->blueprint = $blueprint;
        $this->settingModel = $settingModel;
    }

    /**
     * @inheritdoc
     * @return string
     */
    public function createSchema(): string
    {
        return $this->schema
            ->schema()
            ->table($this->settingModel)
            ->row($this->blueprint->autoID())
            ->row($this->blueprint->varchar('setting_name', 65))
            ->row($this->blueprint->longText('setting_value'))
            ->build(function ($schema) {
                return $schema
                    ->addPrimaryKey($this->blueprint->getPrimaryKey())
                    ->setUniqueKey(['setting_name'])
                    ->addKeys();
            });
    }
}
