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
        return $next($middleware);
    }
}