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

interface PluginInterface
{

    /**
     * Register a new plugin which will get added to an array of registered
     * plugins.
     *
     * @param string $plugin
     * @return void
     */
    public function register(string $plugin): void;

    /**
     * Unregister a single registered or default plugin
     *
     * @param mixed $pluginName
     * @return void
     */
    public function unregister(mixed $pluginName): void;

    /**
     * Unregister all registered plugins
     *
     * @param array $pluginNames
     * @return void
     */
    public function unregisterAll(array $pluginNames = []): void;

    /**
     * Return an array of all default plugins
     *
     * @return array
     */
    public function getDefaultPlugins(): array;

    /**
     * Return an array of all registered plugins
     *
     * @return array
     */
    public function getPlugins(): array;

    /**
     * Return an array of all plugins default and registered plugins
     *
     * @return array
     */
    public function getAllPlugins(): array;

    /**
     * Check whether if we have a specific plugin by passing the name of the 
     * plugin.
     *
     * @param string $pluginName
     * @return boolean
     */
    public function hasPlugin(string $pluginName): bool;


}