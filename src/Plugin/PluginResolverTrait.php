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
use ReflectionMethod;
use MagmaCore\Base\BaseApplication;
use MagmaCore\DataObjectLayer\DataLayerClientFacade;
use MagmaCore\Plugin\Exception\PluginInvalidArgumentException;

trait PluginResolverTrait
{

    /** @var array $supportedMethods */
    private array $supportedMethod = [
        'schema',
        'getSchema',
        'getSchemaID'
    ];
    /** @var array $args */
    private array $args = [
        'blueprint' => \MagmaCore\DataSchema\DataSchemaBlueprint::class,
        'schema' => \MagmaCore\DataSchema\DataSchema::class
    ];
    /** @var string  */
    private string $methodWatch = 'pluginDeploy';

    /**
     * Use PHP ReflectionClass to resolve the bootstrap plugin class. By fetching
     * the docComment from the pluginRegistration method which is a required method
     * by interface design. The meta layed on within the doc block must comply with
     * how they should be listed out by the framework.ie key names must match (Name, 
     * URI, Description, Author, Homepage and Version). This way the framework can 
     * automatically register your plugin details within the database
     *
     * @param string $pluginName
     * @return void
     */
    private function resolvePluginData(string $pluginName, array $supportedMeta): void
    {
        $reflection = new ReflectionClass($pluginName);
        $docComments = $reflection->getMethod($this->methodWatch)->getDocComment();
        $docComments = str_replace(['/**', '*/', '*'], '', $docComments);
        $docComments = explode(';', $docComments);
        if (is_array($docComments)) {
            $pluginComment = [];
            foreach ($docComments as $docComment) {
                /* Create the key/value pair return from the explode array */
                list($commentKey, $commentValue) = explode(':', $docComment);
                /* assign key to the value */
                $pluginComment[$commentKey] = $commentValue;
                /** Loop the result and check the meta keys are valid else throw an exeception */
                foreach (array_map('trim', $pluginComment) as $key => $value) {
                    $key = trim($key); /* trim away any white spaces around the keys */
                    if (!in_array($key, $supportedMeta, true)) {
                        throw new PluginInvalidArgumentException($key . ' is not a valid plugin meta tag. Please see list of support meta tags [' . implode(', ', $supportedMeta) . ']');
                    }
                    /** Assign the key=value pair to the pluginMeta property for use eleswhere */
                    $this->pluginMeta[$key] = $value;
                }
            }
        }
    }

    /**
     * Unset any unresolvable services from the plugin service request
     *
     * @param array $unresolvables
     * @param array $services
     * @return void
     */
    private function unsetUnresolvables(array $unresolvables, array $services): array
    {
        if (count($unresolvables) > 0) {
            foreach ($unresolvables as $unresolvable) {
                if (in_array($unresolvable, $services, true)) {
                    $index = array_search($unresolvable, $services, true);
                    unset($services[$index]);
                }
                return $services;
            }
        }
    }

    /**
     * Not all requested services is resolvable. So this has to be taken into account 
     * and be handle separately. We start by removing the unresolvable services from 
     * the requested plugin services array and booted them through a external private 
     * method. Which carries out the necessary checks before resolving the unresolvables
     *
     * @param array $unresolvable
     * @param array $services
     * @param ReflectionClass $reflection - a reflection of the plugin class
     * @return array
     */
    private function resolvedServices(array $unresolvables, array $services, ReflectionClass $reflection): array
    {
        /* this method will remove any unresolvables and return only resolvable services */
        $resolved = $this->unsetUnresolvables($unresolvables, $services);

        $fixResolvables = $this->fixUnresolvableServices($unresolvables, $services, $reflection);
        return array_merge(
            $this->getResolvableServices($resolved),
            $this->getFixableServices($fixResolvables)
        );
    }

    /**
     * Return the unresolvable services as fix services
     * 
     * @param array|null
     * @return array|null
     */
    public function getFixableServices(array|null $fixResolvables): array|null
    {
        return ($fixResolvables !== null) ? $fixResolvables : [];
    }

    /**
     * Return an container object of the request services which does not need
     * resolving
     *
     * @param array $services
     * @return array
     */
    private function getResolvableServices(array $services): array
    {
        return array_map(
            fn ($service): object => BaseApplication::diGet(PluginServices::PLUGIN_SERVICES[$service]),
            $services
        );
    }

    /**
     * Resolve the unresolvables by first ensuring the requested services is part of 
     * the unresolvables services. Then an only then we will attempt to resolve the services
     * Some parameters are required within the external plugin implemetation class in order
     * to retrive some required parameters to help solve the unresolvables.
     * ie, clientRepository requires the tableSchema and tableSchemaID parameters which
     * should be set within the plugin class
     *
     * @param array $unresolvables
     * @param array $services
     * @param ReflectionClass $reflection - a reflection of the plugin class
     * @return array
     */
    private function fixUnresolvableServices(array $unresolvables, array $services, ReflectionClass $reflection): array
    {
        $resolved = [];
        if (count($unresolvables) > 0) {
            foreach ($unresolvables as $resolveService) {
                /* if a unresolvable is requested then resolve else do nothing */
                if (in_array($resolveService, $services, true)) {
                    switch ($resolveService) {
                        case 'clientRepository':
                            $this->resolvedParameters($reflection);
                            $constants = $reflection->getConstants();
                            $this->resolveMethods($constants, 'schema', $reflection);
                            $resolved = [
                                new DataLayerClientFacade(
                                    str_replace('\\', '_', $reflection->getName()),
                                    $constants['TABLESCHEMA'],
                                    $constants['TABLESCHEMAID']
                                )
                            ];

                            break;
                    }
                }
            }
        }
        return $resolved;
    }

    /**
     * Resolve the client repository object then return it to be added back to
     * the plugin requested service.
     *
     * @param ReflectionClass $reflection
     * @return array
     */
    private function handleClientRepository(ReflectionClass $reflection): array
    {
        $constants = $reflection->getConstants();
        return [
            new DataLayerClientFacade(
                str_replace('\\', '_', $reflection->getName()),
                $constants['TABLESCHEMA'],
                $constants['TABLESCHEMAID']
            )
        ];
    }

    /**
     * Throw an exception if trying to call a service which is not supported or invalid
     *
     * @param array $services
     * @return void
     */
    private function throwException(array $supportedServices, array $services): void
    {
        if (count($services) > 0) {
            foreach ($services as $service) {
                if (!in_array($service, $supportedServices, true)) {
                    throw new PluginInvalidArgumentException('Your requested service [' . $service . '] is not a supported plugin service. Please either check your spelling or see full list of supported plugin services.');
                }
            }
        }
    }

    /**
     * If the clientRepository services is requested. Then the implementing 
     * plugin must adhere to the policy. The plugin class must implement
     * the TABLESCHEMA AND TABLESCHEMAID constants which declare their
     * database model
     *
     * @param ReflectionClass $reflection
     * @return void
     */
    private function resolvedParameters(ReflectionClass $reflection)
    {
        foreach (array('TABLESCHEMA', 'TABLESCHEMAID') as $constant) {
            if (!$reflection->hasConstant($constant)) {
                throw new PLuginInvalidArgumentException('Your plugin is missing the relevant parameter constants which declare your database model. ie [TABLESCHEMA, TABLESCHEMAID]. Please add these to your implementing plugin class.');
            }
        }
    }

    /**
     * Resolve method arguments
     *
     * @param object $class
     * @param string $method
     * @param array $args
     * @return mixed
     */
    private function resolvedMethodArgs(object $class, string $method, array $args = []): mixed
    {
        $reflectionMethod = new ReflectionMethod($class, $method);
        $pass = [];
        foreach ($reflectionMethod->getParameters() as $param) {
            if (isset($args[$param->getName()])) {
                $pass[] = $args[$param->getName()];
            } else {
                $pass[] = $param->getDefaultValue();
            }
        }

        return $reflectionMethod->invokeArgs($class, $pass);
    }

    /**
     * If using clientRepository this will throw an exception is some required methods
     * are missing.
     * 
     * Automatically inject selected objects within selected methods within a plugin
     * class. Giving the plugin access to i.e DataSchema and DataSchemaBlueprint object
     * for building database schema.
     *
     * @param array $constants
     * @param ReflectionClass $reflection
     * @return void
     */
    private function resolveMethods(array $constants, string $method, ReflectionClass $reflection): void
    {
        /* Lets check if we've declare the required constants */
        if (isset($constants) && $constants !== '') {
            $classMethods = $reflection->getMethods();
            foreach ($classMethods as $classMethod) {
                if (in_array($classMethod->getName(), $this->supportedMethod, true)) {
                    if (!$reflection->hasMethod($classMethod->getName())) {
                        throw new PluginInvalidArgumentException('using the clientRepository service. Requires you to delcare a schema() method which seems to be missing from your plugin class.');
                    }
                }
            }
            $argument = array_map(fn ($arg) => BaseApplication::diGet($arg), $this->args);

            $this->resolvedMethodArgs(
                $reflection->newInstance(),
                $reflection->getMethod($method)->getName(),
                $argument
            );
        }
    }
}
