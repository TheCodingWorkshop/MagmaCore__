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

namespace MagmaCore\Middleware;

use MagmaCore\Middleware\Exception\MiddlewareInvalidArgumentException;
use Closure;

class Middleware
{

    /** @var array */
    private array $middlewares;

    /**
     * Undocumented function
     *
     * @param array $middlewares
     * @return void
     */
    public function __construct(array $middlewares = [])
    {
        $this->middlewares = $middlewares;
    }

    /**
     * Add middlewares
     *
     * @param [type] $middlewares
     * @return void
     */
    public function middlewares(array $middlewares) : self
    {
        if ($middlewares instanceof Middleware) {
            $middlewares = $middlewares->toArray();
        }
        if ($middlewares instanceof MiddlewareInterface) {
            $middlewares = [$middlewares];
        }
        if (!is_array($middlewares)) {
            throw new MiddlewareInvalidArgumentException(get_class($middlewares) . ' is not a valid middleware object.');
        }
        return new static(array_merge($this->middlewares, $middlewares));
    }

    /**
     * Run the middle before and after the called method and pass and Object
     * through.
     *
     * @param Object $middleware
     * @param Closure $next
     * @return void
     */
    public function middleware(Object $middleware, Closure $next)
    {
        $funcNext = $this->getNextFunc($next);
        /*
         reverse the order of how the middles are called so the first 
         in the array will be executed first
         */
        $middlewares = array_reverse($this->middlewares);
        $func = array_reduce($middlewares, function($nextMiddleware, $middlewares) {
            return $this->createMiddleware($nextMiddleware, $middlewares);
        }, $funcNext);
        /* return the middleware with the object */
        return $func($middleware);

    }

    /**
     * Returns an array of middlewares
     *
     * @return array
     */
    public function toArray() : array
    {
        return $this->middlewares;
    }

    /**
     * Undocumented function
     *
     * @param Closure $next
     * @return void
     */
    public function getNextFunc(Closure $next)
    {
        return function($funcNext) use ($next) {
            return $next($funcNext);
        };
    }

    /**
     * Get a middleware function. This function will get the object from the previous
     * middleware and pass it inwards
     *
     * @param [type] $nextMiddleware
     * @param [type] $middlewares
     * @return void
     */
    public function createMiddleware($nextMiddleware, $middlewares)
    {   
        return function($funcNext) use ($nextMiddleware, $middlewares) {
            return $middlewares->middleware($funcNext, $nextMiddleware);
        };
    }
}