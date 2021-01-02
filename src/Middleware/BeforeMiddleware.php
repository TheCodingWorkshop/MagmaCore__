<?php

declare(strict_types=1);

namespace MagmaCore\Middleware;

use MagmaCore\Middleware\MiddlewareInterface;
use Closure;

class BeforeMiddleware implements MiddlewareInterface
{

    /**
     * @inheritdoc
     * @param Object $middleware
     * @param Closure $next
     * @return void
     */
    public function middleware(Object $middleware, Closure $next)
    {
        $middleware->runs[] = 'before';
        return $next($middleware);
    }
}