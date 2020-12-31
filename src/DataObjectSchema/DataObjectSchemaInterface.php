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

namespace MagmaCore\DataObjectSchema;

interface DataObjectSchemaInterface
{ 

    /**
     * Undocumented function
     *
     * @param array $schema
     * @return void
     */
    public function schema(array $schema = []) : self;

    /**
     * Undocumented function
     *
     * @param array $args
     * @return void
     */
    public function row(array $args = []) : self;

    /**
     * Undocumented function
     *
     * @param array $args
     * @return void
     */
    public function table(array $args = []);

}