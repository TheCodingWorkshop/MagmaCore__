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

namespace MagmaCore\Ash\Components\Uikit;

use MagmaCore\Utility\Yaml;
use App\Commander\UserCommander;
use MagmaCore\CommanderBar\CommanderBar;
use MagmaCore\CommanderBar\CommanderFactory;

class UikitCommanderBarExtension
{

    /** @var string */
    public const NAME = 'uikit_commander_bar';

    /**
     * Get the session flash messages on the fly.
     *
     * @param object $controller - the current controller object
     * @return string
     * @throws GlobalManager
     * @throws Exception
     * @throws GlobalManagerException
     */
    public function register(object $controller = null, ?string $header = null, ?string $headerIcon = null): mixed
    {
        if (!isset($controller->commander)) {
            return false;
        } else {
            return (new CommanderFactory())->create($controller);
        }
    }
}
