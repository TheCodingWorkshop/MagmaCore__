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

interface RouterInterface
{

    /**
     * Add a route to the routing table
     *
     * @param string $route The route URL
     * @param array $params Parameters (controller, action, etc.)
     * @param Closure|null $cb - optional callback function
     *
     * @return void
     */
    public function add(string $route, array $params = [], Closure $cb = null);

    /**
     * Dispatch the route, creating the controller object and running the
     * action method
     *
     * @param string $url The route URL
     * @return void
     */
    public function dispatch(string $url);

    /**
     * Get the currently matched parameters
     *
     * @return array
     */
    public function getParams() : array;

    /**
     * Get all the routes from the routing table
     *
     * @return array
     */
    public function getRoutes() : array;


}