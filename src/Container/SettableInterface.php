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

namespace MagmaCore\Container;

/** PSR-11 Container */
interface SettableInterface
{

    /**
     * Explicitly set one or more dependency. Dependencies are autoset when 
     * using the get() method to fetch a unset dependency
     *
     * @param string $id Identifier of the entry to look for.
     * @param \Closure $concrete
     * @return void
     */
    public function set(string $id, \Closure $concrete = null): void;
}
