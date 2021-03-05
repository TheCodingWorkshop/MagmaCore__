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

namespace MagmaCore\DatabaseSchema;

use MagmaCore\DatabaseSchema\DatabaseSchemaInterface;

abstract class AbstractDatabaseSchema implements DatabaseSchemaInterface
{

    protected const SCHEMA = [];

    public function __construct(object $model, array $attributes)
    {
        
    }

    public function validateSchema()
    {
        
    }

}