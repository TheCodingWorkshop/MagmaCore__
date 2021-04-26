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

use MagmaCore\Plugin\MyPlugin;
use MagmaCore\Plugin\PluginFactory;
use MagmaCore\Plugin\PluginManagerInterface;

class HelloDolly implements PluginManagerInterface
{

    private const PLUGIN_NAME = 'HelloDolly';
    private const PLUGIN_DESCRIPTION = 'This is a simple plugin which shows random';
    private const PLUGIN_VERSION = '1.0.0';
    private const PLUGIN_AUTHOR = ['Ricardo Miller', 'ricardo.nalio.miller@gmail.com'];

    /**
     * Register the name of the plugin
     *
     * @return string
     */
    public function pluginName(): string
    {
        return self::PLUGIN_NAME;
    }

    /**
     * Add a meta description which briefly describes the purpose of the plugin
     *
     * @return string
     */
    public function plugDescription(): string
    {
        return self::PLUGIN_DESCRIPTION;
    }

    /**
     * Declare the current stabler version of the plugin
     *
     * @return integer
     */
    public function pluginVersion(): int
    {
        return (int)self::PLUGIN_VERSION;
    }

    /**
     * Returns an array of author meta data. ie authors name, email address and 
     * Ensure the array is define in the correct order
     * ['name', 'email', 'etc']
     *
     * @return array*/
    public function pluginAuthor(): array
    {
        return self::PLUGIN_AUTHOR;
    }

    /**
     * Plugin execution
     *
     * @return mixed
     * @throws \Exception
     */
    public function pluginRegistration(): mixed
    {
        $factory = new PluginFactory();
        if ($factory) {
            $factory->create(HelloDolly::class);
            $services = $factory->registerForServices(MyPlugin::class);
            if ($factory->servicesAvailable()) {
                return (new MyPlugin($services))->pluginProcessor();
            }
            
        }
    }
}
