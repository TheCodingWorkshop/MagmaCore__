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
use MagmaCore\Plugin\Plugin;
use MagmaCore\Plugin\PluginServices;
use MagmaCore\Plugin\PluginServiceTrait;
use MagmaCore\Plugin\PluginResolverTrait;
use MagmaCore\Plugin\PluginBuilderInterface;
use MagmaCore\Plugin\Exception\PluginInvalidArgumentException;

class PluginFactory
{

    /** @var trait */
    use PluginResolverTrait;
    use PluginServiceTrait;

    /** @var string $pluginServices */
    private string $pluginServices;
    /** @var string */
    private string $pluginName;
    /** @var array $services */
    private array $services = [];
    /** @var array $options */
    private array $options = [];
    /** @var array */
    private const UNRESOLVEABLES = ['clientRepository'];
    /** @var array */
    private const SUPPORTED_SERVICES = [
        'error',
        'clientRepository',
        'request',
        'response'
    ];
    /** @var array */
    private const SUPPORTED_PLUGIN_META = [
        'Name',
        'URI',
        'Description',
        'Author',
        'Homepage',
        'Version'
    ];
    private array $pluginMeta = [];

    /**
     * Register an external plugin and parse the comment into usable data
     *
     * @param string $pluginName
     * @param array $options
     * @return void
     */
    public function create(string $pluginName, array $options = []): void
    {
        $this->resolvePluginData($pluginName, self::SUPPORTED_PLUGIN_META);
        if ($this->plugin = (new Plugin())) {
            $this->pluginName = $pluginName;
            $this->plugin->register($pluginName, $this->pluginMeta);
        }
    }

    /**
     * Register the plugin and register for any services the plugin requires
     *
     * @param string $pluginServices
     * @param array $services
     * @return void
     */
    public function registerForServices(string $pluginServices, array $services = []): void
    {
        $this->throwException(self::SUPPORTED_SERVICES, $services);
        $this->pluginServices = $pluginServices;
        $this->services = $services;
    }

    /**
     * Checks whether the requested service exists and return true if it does or 
     * false otherwise
     *
     * @return boolean
     */
    public function hasServices(): bool
    {
        if (isset($this->services) && count($this->services) > 0) {
            foreach ($this->services as $service) {
                return in_array($service, array_keys(PluginServices::PLUGIN_SERVICES), true) ? true : false;
            }
        }
    }

    /**
     * 
     */
    public function run()
    {
        $reflection = new ReflectionClass($this->pluginServices);
        if ($reflection->isInstantiable()) {
            /* Get all resolvable services as an array */
            $availableServices = $this->resolvedServices(
                self::UNRESOLVEABLES,
                $this->services,
                $reflection
            );
            $newPlugin = $reflection->newInstance($availableServices);
            if (!$newPlugin instanceof PluginBuilderInterface) {
                throw new PluginInvalidArgumentException('Your plugin does not comply with the standards set by MagmaCore framework. Please ensure you are implementing [PluginBuilderInterface]');
            }
            return $this->pluginStatus($newPlugin);
        }
    }

    /**
     * Undocumented function
     *
     * @param object $newPlugin
     * @return boolean
     */
    private function pluginStatus(object $newPlugin)
    {
        $status = $this->getPluginStatus($newPlugin);
        if ($status === 'activate') {
            $this->executePlugin($newPlugin);
            return $newPlugin->pluginProcessor();
        } else {
            return;
        }
    }
}