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

class LoginRequired extends BeforeMiddleware
{

    protected const MESSAGE = "<strong class=\"uk-text-danger\">Action Required: </strong>Please login for access.";

    /**
     * Requires basic login when entering protected routes
     *
     * @param Object $middleware - contains the BaseController object
     * @param Closure $next
     * @return void
     */
    public function middleware(object $middleware, Closure $next)
    {
        if (!Authorized::grantedUser()) {
            $middleware->flashMessage(self::MESSAGE, $middleware->flashInfo());
            /* Hold the requested page so when the user logs in we can redirect them back */
            Authorized::rememberRequestedPage();
            $middleware->redirect('/login');
        }

        return $next($middleware);
    }
}
