<?php

declare(strict_types=1);

namespace MagmaCore\Base;

use MagmaCore\Base\Exception\BaseInvalidArgumentException;
use ReflectionMethod;

trait ControllerTrait
{


    /**
     * Get the reflection action method
     *
     * @param [type] $method
     * @param array $argument
     * @return void
     */
    private function ResolvedControllerMethods($method, array $argument)
    {
        $reflectionMethod = new ReflectionMethod($this, $method);
        $args = [];
        foreach ($reflectionMethod->getParameters() as $param) {
            $name = $param->getName();
            $class = $param->getClass();
            if ($class === null) {
                $args[] = $this->routeParams[$name];
            } else {
                if ($class->isInstance('')) {
                    $args[] = '';
                } else {
                    throw new \BadMethodCallException("Method {$method} does not exists.");
                }
                return call_user_func_array([$this, $method], $argument);
            }
        }
    }

    /**
     * Method for allowing child controller class to dependency inject other objects
     * 
     * @param array|null $args
     * @return Object
     * @throws BaseInvalidArgumentException
     * @throws ReflectionException
     */
    protected function resolvedServices(?array $args = null)
    {
        if ($args !==null && !is_array($args)) {
            throw new BaseInvalidArgumentException('Invalid argument called in container. Your dependencies should return a key/value pair array.');
        }
        $args = func_get_args();
        if ($args) {
            $output = '';
            foreach ($args as $arg) {
                foreach ($arg as $property => $class) {
                    if (strpos($class, $arg[$property]) !== false) {
                        if ($class) {
                            $output = ($property === 'dataColumns' || $property === 'column') ? $this->$property = $class : $this->$property = BaseApplication::diGet($class);
                        }
                    }
                }
            }
            return $output;
        }
    }

    public function onSelf()
    {
        $controller = $this->routeParams['controller'];
        $namespace = $this->routeParams['namespace'];
        $action = $this->routeParams['action'];
        $id = $this->routeParams['id'];
        $sep = '/';

        if (isset($controller) && is_string($controller) && $controller !=null) {
            if (isset($action) && is_string($action)) {
                switch ($action) {
                    default :
                        if ($this->id !==null || $this->id !==0) {
                            $path = "{$namespace}/{$controller}/{$id}/{$action}";
                        } else {
                            $path = "{$namespace}/{$controller}/{$action}";
                        }
                        if (isset($path) && $path !='') {
                            return rtrim($path);
                        }
                        break;
                }
            }
        }
    }

}