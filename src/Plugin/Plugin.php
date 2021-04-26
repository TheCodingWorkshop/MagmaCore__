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

use MagmaCore\Base\BaseApplication;
use MagmaCore\Plugin\PluginInterface;
use MagmaCore\Plugin\Exception\PluginInvalidArgumentException;
use MagmaCore\Plugin\Exception\PluginUnexpectedValueException;

class Plugin implements PluginInterface
{

    /** @var array $defaultPlugins - contains all framework default plugins */
    private array $defaultPlugins = [];

    public function __construct(private array $plugin = [])
    {
    }

    /**
     * Undocumented function
     *
     * @param string $method
     * @param mixed $args
     * @return void
     */
    public function __call(string $method, mixed $args)
    {
        throw new PluginUnexpectedValueException('Your new plugin class ' . implode(' ', $this->plugin) . ' is trying to call an unknown method ' . $method);
    }

    /**
     * Register a new plugin which will get added to an array of registered
     * plugins.
     *
     * @param string $plugin
     * @return void
     */
    public function register(string $plugin): void
    {
        $this->plugin[$plugin] = $this->resolvePluginRegistration($plugin);
    }

    /**
     * Unregister a single registered or default plugin
     *
     * @param mixed $pluginName
     * @return void
     */
    public function unregister(mixed $pluginName): void
    {
        if (isset($this->plugin[$pluginName]))
            unset($this->plugin[$pluginName]);
    }

    /**
     * Unregister all registered plugins
     *
     * @param array $pluginNames
     * @return void
     */
    public function unregisterAll(array $pluginNames = []): void
    {
        $defaultPlugins = array_merge($pluginNames, $this->defaultPlugins);
        foreach ($defaultPlugins as $defaultPluginName) {
            $this->unregister($defaultPluginName);
        }
    }

    /**
     * Return an array of all default plugins
     *
     * @return array
     */
    public function getDefaultPlugins(): array
    {
        return $this->defaultPlugins;
    }

    /**
     * Return an array of all registered plugins
     *
     * @return array
     */
    public function getPlugins(): array
    {
        return $this->plugin;
    }

    /**
     * Return an array of all plugins default and registered plugins
     *
     * @return array
     */
    public function getAllPlugins(): array
    {
        return array_merge($this->getPlugins(), $this->getDefaultPlugins());
    }

    /**
     * Check whether if we have a specific plugin by passing the name of the 
     * plugin.
     *
     * @param string $pluginName
     * @return boolean
     */
    public function hasPlugin(string $pluginName): bool
    {
        return array_key_exists($pluginName, $this->getAllPlugins()) ? true : false;
    }

    /**
     * Return an integer count of all the defaults plugins
     *
     * @return integer
     */
    public function countDefaultPlugins(): int
    {
        return isset($this->defaultPlugins) ? count($this->defaultPlugins) : 0;
    }

    /**
     * Return an integer count of all the registered plugins
     *
     * @return integer
     */
    public function countRegisteredPlugins(): int
    {
        return isset($this->plugin) ? count($this->plugin) : 0;
    }

    /**
     * Return an integer of all the plugins combine
     *
     * @return integer
     */
    public function countPlugins(): int
    {
        $combine = array_merge($this->countDefaultPlugins(), $this->countRegisteredPlugins());
        return isset($combine) ? count($combine) : 0;
    }

    /**
     * Resolve the plugin. Buy creating a new container object
     *
     * @param string $pluginName
     * @return PluginManagerInterface
     */
    private function resolvePluginRegistration(string $pluginName): PluginManagerInterface
    {
        if ($pluginName) {
            $newPluginObject = BaseApplication::diGet($pluginName);
            if (!$newPluginObject instanceof PluginManagerInterface) {
                throw new PluginInvalidArgumentException('');
            }
            return $newPluginObject;
        }
    }
}
