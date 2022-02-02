<?php
/*
 * This file is part of the MagmaCore package.
 *
 * (c) Ricardo Miller <ricardomiller@lava-studio.co.uk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MagmaCore\Bundler;

use MagmaCore\Container\ContainerInterface;
use MagmaCore\Blank\Exception\BlankUnexpectedValueException;
use MagmaCore\Contracts\FactoryInterface;
use MagmaCore\Utility\Yaml;
use LogicException;
use function array_key_exists;
use function count;

class Bundler
{

    /* @var ContainerInterface $container */
    private ContainerInterface $container;
    private string $appPath;
    private array $bundler = [];
    private array $registeredBundle = [];
    private array $quickOverride = [];

    /**
     * @param ContainerInterface $container
     * @param string|null $appPaths
     */
    public function __construct(ContainerInterface $container, ?string $appPath = null)
    {
        $this->container = $container;
        $this->appPath = $appPath;
    }

    /**
     * Add a component bundle to the registrar
     * @param array $configBundler
     * @return $this
     */
    public function addBundle(array $configBundler): self
    {
        $this->bundler[] = $configBundler;
        return $this;
    }

    /**
     * Returns an array of the registered bundles
     * @return array
     */
    public function getBundles(): array
    {
        return $this->bundler;
    }

    /**
     * Return an component object registered via the bundler. The key represent the accessor property
     * within the component configuration class factory array [accessor => 'your_accessor_key']
     *
     * @param string $key
     * @return object
     */
    public function getBundle(string $key): object
    {
        return $this->registeredBundle[$key];
    }

    /**
     * Returns an array of bundle keys from all the registered component bundles
     * @return array
     */
    public function getBundleKeys(): array
    {
        return array_keys($this->registeredBundle);
    }

    /**
     * Returns the size of the regsitered bundles
     * @return int
     */
    public function bundleCount(): int
    {
        return count($this->registeredBundle);
    }

    /**
     * Boot the component. Each component must use the factory pattern in order for it to be registered
     * The bootloader will attemp to instantiate the factory and add all the necessary dependencies
     * which is required for the component. All factory is loaded via dependency injection
     *
     * @param object $bundlerObject
     * @return object
     */
    public function boot(object $bundlerObject): object
    {
        $newInstance = new \StdClass;
        if (is_array($this->bundler) && count($this->bundler) > 0) {
            foreach ($this->bundler as $bundle) {
                /* the component name */
                $bundleName = array_key_exists('name', $bundle) ? $bundle['name'] : '';
                if ($bundleName !=='') {
                    if (array_key_exists('version', $bundle) && $bundle['version'] !=='') {
                        /* the component version */
                        $bundleVersion = array_key_exists('min_php_version', $bundle) ? $bundle['min_php_version'] : '';
                        if (version_compare(PHP_VERSION, $bundleVersion) < 0) {
                            throw new LogicException($bundleName . ' Is atleast ' . $bundleVersion . ' The current installed PHP version is ' . PHP_VERSION . ' Your PHP environment is outdated');
                        }
                        $overridables = $this->resolveComponentBundleOverridableOptions($bundle, $bundleName);

                        $bundleClass = array_key_exists('factory', $bundle) ? $bundle['factory'] : [];
                        /* Classes should be design to be loaded via the container meaning only type-hinted classes are allowed */
                        $factory = $this->container->get($bundleClass['class']);
                        if (!$factory instanceof  FactoryInterface) {
                            throw new BlankUnexpectedValueException(
                                sprintf(
                                    '[ %s ] is not a valid object or not an instance of [ % ]',
                                    $factory,
                                    FactoryInterface::class
                                )
                            );
                        }
                        /* Configure and return the driver string */
                        $componentDriver = $this->resolveComponentBundleDriver($bundle);
                        $optionalParams = array_key_exists('optional_parameters', $bundle) ? $bundle['optional_parameters'] : [];
                        
                        $newInstance = $factory->create($componentDriver, $overridables, $optionalParams);
                        $this->registeredBundle[$bundleClass['accessor']] = $newInstance;

                    }
                }
            }
        }
        return $newInstance;
    }

    /**
     * The override options will only be available if overriding_options is set ot true
     * @param array $bundle
     * @param string $bundleName
     * @return array
     * @throws \Exception
     */
    private function resolveComponentBundleOverridableOptions(array $bundle, string $bundleName): array
    {
        $overrideConfig = [];
        if (isset($bundle['overriding_options']) && $bundle['overriding_options'] === true) {
            $overrideConfig = array_key_exists('overriding_yml', $bundle) ? $bundle['overriding_yml'] : '';
            if (!file_exists($this->appPath . '/Config/' . $overrideConfig . '.yml')) {
                throw new \LogicException('You have the set to override the default configurations. Please ensure you have a ' . $bundleName . '.yml file exists with the Config directory.');
            }
            $overrideConfig = Yaml::file(isset($bundle['overriding_yml']) ? $bundle['overriding_yml'] : '');
        }
        return $overrideConfig;
    }

    /**
     * @param array $bundle
     */
    private function resolveComponentBundleDependencies(array $bundle): void
    {
        $hasDependencies = array_key_exists('dependencies', $bundle) ? $bundle['dependencies'] : [];
        if (count($hasDependencies) > 0) {
            foreach ($hasDependencies as $key => $dependency) {
                if (isset($key) && $key !=='') {
                    if (!class_exists($dependency)) {
                        throw new \LogicException($dependency . ' class does not exists');
                    }
                    $this->container->get($dependency);
                }
            }
        }

    }

    /**
     * @param array $bundle
     * @return string
     */
    private function resolveComponentBundleDriver(array $bundle): string
    {
        $componentDriver = '';
        $hasDrivers = array_key_exists('drivers', $bundle) ? $bundle['drivers'] : [];
        if (count($hasDrivers) > 0) {
            $defaultDrivers = $hasDrivers['default_driver'];
            if ($defaultDrivers !=='') {
                foreach ($hasDrivers['sources'] as $key => $driver) {
                    if (str_contains($key, $defaultDrivers)) {
                        $componentDriver = $driver;
                    }
                }
            }
        }
        return $componentDriver;

    }

}