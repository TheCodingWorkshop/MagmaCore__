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

namespace MagmaCore\Router;

use Closure;
use ReflectionMethod;
use MagmaCore\Utility\Yaml;
use MagmaCore\Router\RouterTrait;
use MagmaCore\Http\RequestHandler;
use MagmaCore\Base\BaseApplication;
use MagmaCore\Http\ResponseHandler;
use MagmaCore\Router\RouterInterface;
use MagmaCore\Router\Exception\RouterNoRoutesFound;
use MagmaCore\Router\Exception\NoActionFoundException;
use MagmaCore\Router\Exception\RouterBadFunctionCallException;

class Router implements RouterInterface
{

    /** @var Object - returns extended router methods */
    use RouterTrait;

    /** @var array - Associative array of routes (the routing table) */
    protected array $routes = [];
    /** @var array - Parameters from the matched route */
    protected array $params = [];
    /** @var string - Controller Slug */
    protected string $controllerSuffix = "Controller";
    private string $actionSuffix = 'Action';
    /** @var string */
    protected string $namespace = 'App\Controller\\';

    /**
     * @inheritDoc
     * @return void
     */
    public function add(string $route, array $params = [], Closure $cb = null)
    {
        if ($cb != null) {
            return $cb($params);
        }
        // Convert the route to a regular expression: escape forward slashes
        $route = preg_replace('/\//', '\\/', $route);
        // Convert variables e.g. {controller}
        $route = preg_replace('/\{([a-z]+)\}/', '(?P<\1>[a-z-]+)', $route);
        // Convert variables with custom regular expressions e.g. {id:\d+}
        $route = preg_replace('/\{([a-z]+):([^\}]+)\}/', '(?P<\1>\2)', $route);
        // Add start and end delimiters, and case insensitive flag
        $route = '/^' . $route . '$/i';

        $this->routes[$route] = $params;
    }

    /**
     * Create the controller object name using the parameters defined within
     * the yaml configuration file. Route parametes are accessible using 
     * the $this->params property and can fetch any key defined. ie
     * `controller, action, namespace, id etc..`
     *
     * @return string
     */
    private function createController(): string
    {
        $controllerName = $this->params['controller'] . $this->controllerSuffix;
        $controllerName  = $this->transformUpperCamelCases($controllerName);
        $controllerName = $this->getNamespace() . $controllerName;
        return $controllerName;
    }

    /**
     * Undocumented function
     *
     * @return string
     */
    public function createAction(): string
    {
        $action = $this->params['action'];
        $action = $this->transformLowerCamelCase($action);
        return $action;
    }

    /**
     * Undocumented function
     *
     * @param string $url
     * @return array
     */
    private function dispatchWithException(string $url): array
    {
        $url = $this->removeQueryStringVariables($url);
        if (!$this->match($url)) {
            throw new RouterNoRoutesFound("Route {$url} does not match any valid route.", 404);
        }
        if (!class_exists($controller = $this->createController())) {
            throw new RouterBadFunctionCallException("Class {$controller} does not exists.");
        }

        return [$controller];
    }

    /**
     * @inheritDoc
     * @throws RouterException
     */
    public function dispatch(string $url)
    {
        list($controller) = $this->dispatchWithException($url);
        $controllerObject = new $controller($this->params);
        $action = $this->createAction();
        if (preg_match('/action$/i', $action) == 0) {
            if (Yaml::file('app')['system']['use_resolvable_action'] === true) {
            $this->resolveControllerActionDependencies($controllerObject, $action);
            } else {
                $controllerObject->$action();
            }
        } else {
            throw new NoActionFoundException("Method $action in controller $controller cannot be called directly - remove the Action suffix to call this method");;
        }
    }

    /**
     * Undocumented function
     *
     * @param object $controllerObject
     * @param string $newAction
     * @return mixed
     */
    private function resolveControllerActionDependencies(object $controllerObject, string $newAction): mixed
    {
        $newAction = $newAction . $this->actionSuffix;
        $reflectionMethod = new ReflectionMethod($controllerObject, $newAction);
        $reflectionMethod->setAccessible(true);
        if ($reflectionMethod) {
            $dependencies = [];
            foreach ($reflectionMethod->getParameters() as $param) {
                $newAction = BaseApplication::diGet(Yaml::file('providers')[$param->getName()]);
                if (isset($newAction)) {
                    $dependencies[] = $newAction;
                } else if ($param->isDefaultValueAvailable()) {
                    $dependencies[] = $param->getDefaultValue();
                }
            }
            return $reflectionMethod->invokeArgs(
                $controllerObject,
                $dependencies
            );
        }
        $reflectionMethod->setAccessible(false);
    }

    /**
     * Match the route to the routes in the routing table, setting the $params
     * property if a route is found.
     *
     * @param string $url The route URL
     * @return boolean  true if a match found, false otherwise
     */
    public function match(string $url): bool
    {
        foreach ($this->routes as $route => $params) {
            if (preg_match($route, $url, $matches)) {
                // Get named capture group values
                foreach ($matches as $key => $match) {
                    if (is_string($key)) {
                        $params[$key] = $match;
                    }
                }
                $this->params = $params;
                return true;
            }
        }
        return false;
    }

    /**
     * @inheritDoc
     * @return array
     */
    public function getParams(): array
    {
        return $this->params;
    }

    /**
     * @inheritdoc
     * @return array
     */
    public function getRoutes(): array
    {
        return $this->routes;
    }

    /**
     * Remove the query string variables from the URL (if any). As the full
     * query string is used for the route, any variables at the end will need
     * to be removed before the route is matched to the routing table. For
     * example:
     *
     *   URL                           $_SERVER['QUERY_STRING']  Route
     *   -------------------------------------------------------------------
     *   localhost                     ''                        ''
     *   localhost/?                   ''                        ''
     *   localhost/?page=1             page=1                    ''
     *   localhost/posts?page=1        posts&page=1              posts
     *   localhost/posts/index         posts/index               posts/index
     *   localhost/posts/index?page=1  posts/index&page=1        posts/index
     *
     * A URL of the format localhost/?page (one variable name, no value) won't
     * work however. (NB. The .htaccess file converts the first ? to a & when
     * it's passed through to the $_SERVER variable).
     *
     * @param string $url The full URL
     * @return string The URL with the query string variables removed
     */
    protected function removeQueryStringVariables($url): string
    {
        if ($url != '') {
            $parts = explode('&', $url, 2);
            if (strpos($parts[0], '=') === false) {
                $url = $parts[0];
            } else {
                $url = '';
            }
        }

        return rtrim($url, '/');
    }

    /**
     * Get the namespace for the controller class. The namespace defined in the
     * route parameters is added if present.
     *
     * @param string $controllerName
     * @return string The request URL
     */
    protected function getNamespace()
    {
        if (array_key_exists('namespace', $this->params)) {
            $this->namespace .= $this->params['namespace'] . '\\';
        }
        return $this->namespace;
    }
}
