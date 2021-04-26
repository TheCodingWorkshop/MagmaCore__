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

class PluginFactory
{

    public function create(string $pluginName, array $options = [])
    {
        $plugin = (new Plugin())->register($pluginName);
        return $this;
    }

    public function registerForServices(string $pluginHandlerClass)
    {

    }

    public function servicesAvailable(): bool
    {
        return true;
    }
}