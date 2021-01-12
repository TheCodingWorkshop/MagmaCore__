<?php

declare(strict_types=1);

namespace MagmaCore\Inertia;

use MagmaCore\Middleware\BeforeMiddleware;
use MagmaCore\Http\RequestHandler;
use MagmaCore\Http\ResponseHandler;
use Closure;

class InertiaMiddlewareBefore extends BeforeMiddleware
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
        if (
            'GET' === $request->getMethod() && 
            $request->headers->get('X-Inertia') !== 
            $this->inertia->getVersion()) {

            $response = new Response('', 409, ['X-Inertia-Location' => $request->getUri()]);
            (new ResponseHandler())->hander()->setResponse($response);
        }
        return $next($object);
    }
}
