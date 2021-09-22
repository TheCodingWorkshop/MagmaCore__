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

namespace MagmaCore\Administrator\Middleware\Before;

use Closure;
use MagmaCore\Auth\Authorized;
use MagmaCore\Middleware\BeforeMiddleware;

class AuthorizedIsNull extends BeforeMiddleware
{

    /**
     * Redirect to login if authorized object is null. As if you're not
     * authorized then access cannot be granted.
     *
     * @param Object $middleware - contains the BaseController object
     * @param Closure $next
     * @return void
     */
    public function middleware(object $middleware, Closure $next)
    {
        $authorized = Authorized::grantedUser();
        if (is_null($authorized)) {
            $middleware->redirect('/login');
        }
        return $next($middleware);
    }

}