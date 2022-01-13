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

use MagmaCore\Http\Request;
use MagmaCore\Router\RouterInterface;
use MagmaCore\Base\Exception\BaseInvalidArgumentException;

class RouterFactory
{

    /** @var Object - Router Object */
    protected Object $routerObject;
    /** @var string|null */
    protected mixed $url;

    protected ?object $request = null;

    /**
     * Router factory constructor
     *
     * @param string|null $url
     */
    public function __construct(?string $url = null)
    {
        $this->request = new Request();
        $this->url = $this->request->get()->getPath();
    }

    public function getRequest()
    {
        return $this->request;
    }

    /**
     * Undocumented function
     *
     * @param string|null $routerString
     * @return RouterInterface
     */
    public function create(?string $routerString = null) : RouterInterface
    {
        $this->routerObject = ($routerString !=null) ? new $routerString() : new Router();
        if (!$this->routerObject instanceof RouterInterface) {
            throw new BaseInvalidArgumentException('Invalid router object');
        }

        return $this->routerObject;
    }

    /**
     * Undocumented function
     *
     * @param array $definedRoutes
     * @return void
     */
    public function buildRoutes(array $definedRoutes = [])
    {
        if (empty($definedRoutes)) {
            throw new BaseInvalidArgumentException('No routes defined');
        }
        $params = [];
        if (count($definedRoutes) > 0) {
            foreach ($definedRoutes as $route => $param) {
                if (isset($param['namespace']) && $param['namespace'] !='') {
                    $params = ['namespace' => $param['namespace']];
                } elseif (isset($param['controller']) && $param['controller'] !='') {
                    $params = ['controller' => $param['controller'], 'action' => $param['action']];
                }
                if (isset($route)) {
                    $this->routerObject->add($route, $params);
                }

            }    
        }
        /* Add dynamic routes based on regular expression */
        $this->routerObject->add('{controller}/{action}');
        /* Dispatch the routes */
        $this->routerObject->dispatch($this->url);

    }

    public function resolveParamNamePrefix($param): array
    {
        if (isset($param['name_prefix']) && $param['name_prefix'] !='') {
            $appendNamespace = array_key_exists('namespace', $param) ? '/' . $param['namespace'] : '/';
            $prefix = $param['name_prefix'];
            if (is_string($prefix)) {
                if (str_contains($prefix, '.')) {
                    $parts = explode('.', $prefix);
                    if (isset($parts) && count($parts) > 0) {
                        $elController = array_shift($parts);
                        $elAction = array_pop($parts);
                        $newArray = Array($prefix => $appendNamespace . $elController . '/' . $elAction);
                        if ($newArray) {
                            return $newArray;
                        }

                    }
                }
            }

        }
        return [];
    }

}