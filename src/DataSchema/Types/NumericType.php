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

namespace MagmaCore\DataSchema\Types;

use MagmaCore\DataSchema\DataSchemaBaseType;
use MagmaCore\DataSchema\DataSchemaTypeInterface;

class NumericType extends DataSchemaBaseType implements DataSchemaTypeInterface
{

    /** @var array - integre schema types */
    protected array $types = [
        'tinyint',
        'smallint',
        'mediumint',
        'bigint',
        'decimal',
        'float',
        'real',
        'double',
        'bit',
        'boolean',
        'serial',
        'int'
    ];

    /**
     * Undocumented function
     *
     * @param array $row
     */
    public function __construct(array $row = [])
    {
        parent::__construct($row, $this->types);
    }

    /**
     * Undocumented function
     *
     * @return string
     */
    public function render(): string
    {
        return parent::render();
    }


}