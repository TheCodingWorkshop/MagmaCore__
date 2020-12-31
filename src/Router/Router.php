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

use MagmaCore\Router\Exception\RouterBadFunctionCallException;
use MagmaCore\Router\Exception\RouterBadMethodCallException;
use MagmaCore\Router\Exception\RouterNoRoutesFound;
use MagmaCore\Router\RouterInterface;
use MagmaCore\Router\RouterTrait;
use Closure;

class Router implements RouterInterface
{

    /** @var Object - returns extended router methods */
    use RouterTrait;

    /** @var array - Associative array of routes (the routing table) */
    protected array $routes = [];
    /** @var array - Parameters from the matched route */
    protected array $params = [];
    /** @var string - Controller Slug */
    protected string $controllerSlug = "Controller";
    /** @var string */
    protected string $namespace = 'App\Controller\\';

    /**
     * @inheritDoc
     * @return void
     */
    public function add(string $route, array $params = [], Closure $cb = null)
    {
        if ($cb !=null) {
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
     * @inheritDoc
     * @throws RouterException
     */
    public function dispatch(string $url, $args = null, ?Object $request = null)
    {
        $url = $this->removeQueryStringVariables($url);
        if ($this->match($url)) {
            $controllerName = $this->params['controller'] . $this->controllerSlug;
            $controllerName  = $this->transformUpperCamelCases($controllerName);
            $controllerName = $this->getNamespace() . $controllerName;

            if (class_exists($controllerName)) {
                $controllerObject = new $controllerName($this->params);
                $action = $this->params['action'];
                $action = $this->transformLowerCamelCase($action);

                if (is_callable([$controllerObject, $action])) {
                    $controllerObject->$action();
                } else {
                    throw new RouterBadMethodCallException("Method {$action} does not exists.");
                }
            } else {
                throw new RouterBadFunctionCallException("Class {$controllerName} does not exists.");
            }

        } else {
            throw new RouterNoRoutesFound("Route {$url} does not match any valid route.", 404);
        }
    }

    /**
     * Match the route to the routes in the routing table, setting the $params
     * property if a route is found.
     *
     * @param string $url The route URL
     * @return boolean  true if a match found, false otherwise
     */
    public function match(string $url) : bool
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
    public function getParams() : array
    {
        return $this->params;
    }

    /**
     * @inheritdoc
     * @return array
     */
    public function getRoutes() : array
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
    protected function removeQueryStringVariables($url) : string
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