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

namespace MagmaCore\DataSchema;

use MagmaCore\DataSchema\DataSchemaBlueprint;

interface DataSchemaBuilderInterface
{ 
    /**
     * Method which should be implemented when using this database schema component
     * We can call the database schema methods to build a table schema
     * 
     * @param BuildSchema $schema
     * @return string
     */
    public function createSchema() : string;

}