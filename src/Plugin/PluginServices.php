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

namespace MagmaCore\Plugin;

class PluginServices
{

    /**
     * Services available for plugin developement
     * @return array
     */
    public const PLUGIN_SERVICES = [
        'error' => \MagmaCore\Error\Error::class,
        'request' => \MagmaCore\Http\RequestHandler::class,
        'response'  => \MagmaCore\Http\ResponseHandler::class,
        'clientRepository' => '' /* left empty */
    ];
}
