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

namespace MagmaCore\CommanderBar;

use Exception;

class CommanderFactory
{

    /**
     * Create the command bar object and pass the required object arguments
     *
     * @param object|null $controller
     * @return CommanderBar|CommanderBarInterface
     * @throws Exception
     */
    public function create(?object $controller = null): CommanderBar|CommanderBarInterface
    {
        return new CommanderBar($controller);
    }

}