<?php

declare(strict_types=1);

namespace MagmaCore\Middleware;

use MagmaCore\Middleware\MiddlewareInterface;
use Closure;

class AfterMiddleware implements MiddlewareInterface
{
    public function middleware(Object $middleware, Closure $next)
    {
        $response = $next($middleware);
        $middleware->runs[] = 'after';
        return $response;
    }
}