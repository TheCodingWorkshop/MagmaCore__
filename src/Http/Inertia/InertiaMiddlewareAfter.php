<?php

declare(strict_types=1);

namespace MagmaCore\Inertia;

use MagmaCore\Middleware\AfterMiddleware;
use MagmaCore\Http\RequestHandler;
use MagmaCore\Http\ResponseHandler;
use Closure;

class InertiaMiddlewareAfter extends AfterMiddleware
{

    /**
     * Undocumented function
     *
     * @param Object $object
     * @param Closure $next
     * @return void
     */
    public function middleware(Object $object, Closure $next)
    {
        $request = (new RequestHandler())->handler();
        if (!$request->headers->get('X-Inertia')) {
            return;
        }
        if ($request->isXmlHttpRequest()) {}
        if ((new ResponseHandler())->handler->getResponse()->getStatusCode() && in_array($request->getMethod(), ['PUT', 'PATCH', 'DELETE'])) {
            $request->getResponse()->setStatusCode(303);
        }

        return $next($object);
    }
}
