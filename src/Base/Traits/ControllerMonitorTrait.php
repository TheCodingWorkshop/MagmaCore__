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

namespace MagmaCore\Base\Traits;

use MagmaCore\Administrator\Model\ControllerDbModel;
use MagmaCore\Base\BaseModel;
use MagmaCore\Base\Nodes\PermissionNodes;
use MagmaCore\Utility\Serializer;
use MagmaCore\Utility\Stringify;
use MagmaCore\Utility\Utilities;

/**
 * Controller monitor trait class monitors the various controllers registered and ping a notification
 * each time something changes within a controller i.e a method ect.
 * Note this is not  asynchronos operation this is done on each request
 */
trait ControllerMonitorTrait
{

    use BaseReflectionTrait,
        ControllerDiscoveryTrait;

    /**
     * Returns an instance of the DbController model. For fetching data from the database anout
     * the registered controllers
     * @return BaseModel
     */
    private function getDbControllers(): BaseModel
    {
        return new ControllerDbModel();
    }

    /**
     * Construct the namespace of the current calling controller. This will primarly located
     * withom the App/Controller namespace or under the Admin namespace
     * @return string
     */
    public function getClassNamespace(): string
    {
        $classNamespace = Stringify::studlyCaps($this->thisRouteController() . 'Controller');
        if (isset($this->routeParams['namespace'])) {
            $className = '\App\Controller\Admin\\' . $classNamespace;
        } else {
            $className = '\App\Controller\\' . $classNamespace;
        }

        return $className;
    }

    /**
     * Get the reflection object of the current calling controller
     * @return object
     * @throws \ReflectionException
     */
    public function getClassNameReflection()
    {
        $classReflection = $this->getClassNamespace();
        if ($classReflection) {
            return $this->reflection($classReflection);
        }
    }

    /**
     * Returns an array of methods which belongs to the queried controller
     * @return array|null
     * @throws \ReflectionException
     */
    public function getControllerMethods(): ?array
    {
        $reflection = $this->getClassNameReflection();
        $methods = $reflection->methods(\ReflectionMethod::IS_PROTECTED);
        if (is_array($methods) && count($methods) > 0) {
            return $methods;
        }

        return null;
    }

    /**
     * Returns an array of the action only methods from the queried controller
     * @return array|null
     * @throws \ReflectionException
     */
    public function getActionOnlyMethods(): ?array
    {
        $suffix = 'Action';
        $methods = $this->getControllerMethods();
        return array_filter($methods, function($method) use ($suffix) {
            if (str_contains($method->name, $suffix)) {
                return $method->name;
            }

            return null;
        });
    }

    /**
     * Returns an array of just the action methods outside of the reflection object
     * @return array
     * @throws \ReflectionException/
     */
    public function getActionMethods(): array
    {
        return array_map(function($met) {
            return $met->name;
        }, $this->getActionOnlyMethods());

    }

    /**
     * Returns an array of methods without the action suffix
     * @return array
     * @throws \ReflectionException
     */
    private function getMethodsWithoutActionString(): array
    {
        return array_map(fn($method) => str_replace('Action', '', $method), $this->getActionMethods());
    }

    /**
     * Return an array of method converted permission strings which can be used to protected
     * the action route/method it' relate to.
     * @throws \ReflectionException
     */
    public function convertMethodsToPermissionString()
    {
        $methods = $this->getMethodsWithoutActionString();
        return array_map(function($method) {
            return str_replace(PermissionNodes::PERMISSION_SEARCHES, PermissionNodes::PERMISSION_REPLACE, $method);
        }, $methods);
    }

    /**
     * Returns an array of permission ready nodes which we can insert within the database for each
     * controller
     */
    public function readyPermissionNodes()
    {
        return array_map(fn($method) => sprintf('can_%s_%s', $method, $this->thisRouteController()), $this->convertMethodsToPermissionString());
    }

    /**
     * Ping methods pings the queried controller to check whether the methods already define
     * has change. i.e it will return an array of methods which wasn't already discovered. This
     * is done by checking the database for all registered controller and their respected
     * methods and compare the current controller for any changes. all changes will ne inserted
     * into the database and any methods which no longer exists will be remove from database
     *
     * @return bool
     */
    public function pingMethods(): array|bool
    {
        /* get the model onject */
        $model = $this->getDbControllers()->getRepo();
        /* Get controller data based on the current route controller */
        $controller = Utilities::flattenContext($model->findBy([], ['controller' => $this->thisRouteController()]));
        /* session key for controller data */
        $sessionKey = 'controller_discover';
        /* the session object */
        $session = $this->getSession();

        /* Discover a new controller if it doesn't already exists */
        if ($controller['controller'] !== $this->thisRouteController()) {
            $this->discoverNewController($model, $this->thisRouteController(), $session, $sessionKey);
        }

        /* are we working in the current route controller */
        if ($controller['controller'] === $this->thisRouteController()) {
            /* Uncompress the methods */
            $unserializeMethods = Serializer::unCompress($controller['methods']);
            if (is_array($unserializeMethods) && count($unserializeMethods) > 0) {
                $differences = array_diff($this->getActionMethods(), $unserializeMethods);
            }
            if (is_array($differences) && count($differences) <= 0) {
                /* check if the method exists within the database */
                /* delete session the session if it does */
                return [];
            } else {
                /* Once the controller is registered and methods saved. Any changes to the controller like
                adding new methods will be saved to the session. Which will be ping to the discovery route */
                if (!$session->has($sessionKey)) {
                    $session->set(
                        $sessionKey,
                        [
                            'new_' . $this->thisRouteController()  .'_discovery' => $differences,
                            'parent_controller' => $controller['controller']
                        ]
                    );
                }
                return $differences;
            }
        }

        return false;

    }

}