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

use MagmaCore\Base\BaseApplication;
use MagmaCore\CommanderBar\ApplicationCommanderInterface;

class CommanderFactory
{
    
    /**
     * Create the commande bar object and pass the required object arguments
     *
     * @param object $controller
     * @return CommanderBarInterface
     */
    public function create(?object $controller = null)
    {
        return new CommanderBar($controller);
    }

}