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

namespace MagmaCore\DataObjectSchema\Type;

use MagmaCore\DataObjectSchema\DataObjectSchemaTypeInterface;

class SpatialSchema extends BaseSchema implements DataObjectSchemaTypeInterface
{

    /** @var array */
    protected array $types = [];
    
    /**
     * Main class constructor. Returns the parent construct and pass in the types
     * which belongs to this class schema.
     *
     * @param array $row
     * @return void
     */
    public function __construct(array $row)
    {
        parent::__construct($row, $this->types);
    }

    /**
     * @inheritdoc
     * @return string
     */
    public function build() : string
    {
        return parent::build();
    }

}