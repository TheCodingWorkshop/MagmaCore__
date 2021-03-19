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

use MagmaCore\DataSchema\DataSchema;

interface DataSchemaTypeInterface
{

    /**
     * Return an array of the available schema type for the given schema object
     *
     * @return array
     */
    public function getSchemaTypes(): array;

    /**
     * Return an array of the available schema columns for the given schema object
     *
     * @return array
     */
    public function getSchemaColumns(): array;

    /**
     * Return an array of the available schema rows for the given schema object
     *
     * @return array
     */
    public function getRow(): array;

    /**
     * Render the schema rows as a large concat string
     *
     * @return string
     */
    public function render(): string;

}