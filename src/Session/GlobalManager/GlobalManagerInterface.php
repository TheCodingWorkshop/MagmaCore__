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

namespace MagmaCore\Session\GlobalManager;

interface GlobalManagerInterface
{

    /**
     * Set the global variable
     * 
     * @param string $name
     * @param mixed $context
     * @return void
     * @throws GlobalManagerException
     */
    public static function set(string $name, mixed $context): void;

    /**
     * Get the value/context of the set global variable
     * 
     * @param string $name
     * @return mixed
     * @throws GlobalManagerException
     */
    public static function get(string $name): mixed;
}
