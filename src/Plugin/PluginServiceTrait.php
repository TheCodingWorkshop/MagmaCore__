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

use ReflectionClass;
use MagmaCore\Plugin\PluginBuilderInterface;
use MagmaCore\Plugin\Exception\PluginException;

trait PluginServiceTrait
{

    /**
     * Return an array of registered plugins from the database plugins table. Use the
     * argument to get a specific plugin based on some conditions.
     *
     * @param object $newPlugin
     * @param array $selectors
     * @param array $conditions
     * @return array|null
     */
    private function getPlugin(object $newPlugin, array $selectors = [], array $conditions = []): array|null
    {
        try {
            return $newPlugin->getClientRepo()->getClientRepository()->get($selectors, $conditions);
        } catch (PluginException $e) {
            throw new PluginException($e->getMessage());
        }
    }

    /**
     * Get the queried plugin name
     *
     * @return string
     */
    public function getPluginName(): string
    {
        $reflection = new ReflectionClass($this->pluginName);
        $name = $reflection->getName();
        $pieces = explode('\\', $name);
        return array_pop($pieces);
    }

    /**
     * Return the current queried plugin data array
     *
     * @param object $newPlugin
     * @return array
     */
    public function getQueriedPlugin(object $newPlugin): array
    {
        return $this->getPlugin($newPlugin, ['name', 'uri', 'description', 'author', 'homepage', 'version'], ['name' => $this->getPluginName()]);
    }

    /**
     * Add the plugin data to the database if it doesn't already exists
     *
     * @param PluginBuilderInterface $newPlugin
     * @return bool
     */
    public function executePlugin(PluginBuilderInterface $newPlugin): bool
    {
        $pluginOptions = [];
        $pluginOptions[] = array_change_key_case($this->plugin->getOptions());
        foreach ($pluginOptions as $pluginOption) {
            $options = array_map('trim', $pluginOption);
            $queriedPlugins = $this->getQueriedPlugin($newPlugin);
            if (!empty($queriedPlugins)) {
                foreach ($queriedPlugins as $queriedPlugin) {
                    $compute = array_diff_assoc($queriedPlugin, $options);
                    if (empty($compute)) {
                        return false;
                    } else {
                        $save = $newPlugin->getClientRepo()->getClientRepository()->save($compute);
                        if ($save) {
                            return $save;
                        }
                    }
                }
            } else {
                $save = $newPlugin->getClientRepo()->getClientRepository()->save($options);
                if ($save) {
                    return $save;
                }
            }
        }
        return false;
    }
    
    /**
     * Undocumented function
     *
     * @param object $newPlugin
     * @return void
     */
    public function getPluginStatus(object $newPlugin)
    {
        $pluginStatus = $this->getPlugin($newPlugin,['status'], ['name' =>$this->getPluginName()]);
        if (is_array($pluginStatus) && count($pluginStatus) > 0) {
            foreach ($pluginStatus as $pluginStat) {
                return $pluginStat;
            }
        }

    }
}
