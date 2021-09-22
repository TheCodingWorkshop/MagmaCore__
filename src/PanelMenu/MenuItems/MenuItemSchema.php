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

namespace MagmaCore\PanelMenu;

use MagmaCore\Auth\Model\MenuItemModel;
use MagmaCore\DataSchema\DataSchema;
use MagmaCore\DataSchema\DataSchemaBlueprint;
use MagmaCore\DataSchema\DataSchemaBuilderInterface;

class MenuItemSchema implements DataSchemaBuilderInterface
{

    /** @var object - $schema for chaining the schema together */
    protected object $schema;
    /** @var object - provides helper function for quickly adding schema types */
    protected object $blueprint;
    /** @var object - the database model this schema is linked to */
    protected object $menuItemModel;

    /**
     * Main constructor class. Any typed hinted dependencies will be autowired. As this
     * class can be inserted inside a dependency container
     *
     * @param DataSchema $schema
     * @param DataSchemaBlueprint $blueprint
     * @param MenuItemModel $menuItemModel
     * @return void
     */
    public function __construct(DataSchema $schema, DataSchemaBlueprint $blueprint, MenuItemModel $menuItemModel)
    {
        $this->schema = $schema;
        $this->blueprint = $blueprint;
        $this->menuItemModel = $menuItemModel;
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
            ->row($this->blueprint->int('menu_id', 10, true, 'unsigned'))
            ->row($this->blueprint->int('item_original_id', 10, true, 'unsigned'))
            ->row($this->blueprint->varchar('item_original_label', 100))
            ->row($this->blueprint->varchar('item_label', 100))
            ->row($this->blueprint->varchar('item_type', 24))
            ->row($this->blueprint->varchar('item_url', 190))
            ->row($this->blueprint->int('item_order', 10))
            ->row($this->blueprint->varchar('item_children', 190))
            ->row($this->blueprint->datetime('created_at', false))
            ->row($this->blueprint->datetime('modified_at', true, 'null', 'on update CURRENT_TIMESTAMP'))
            ->build(function ($schema) {
                return $schema
                    ->addPrimaryKey($this->blueprint->getPrimaryKey())
                    ->setUniqueKey(['menu_id'])
                    ->addKeys();
            });
    }
}

