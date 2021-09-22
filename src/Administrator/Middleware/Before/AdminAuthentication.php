<?php

declare(strict_types=1);

namespace MagmaCore\Administrator\Middleware\Before;

use Closure;
use MagmaCore\Auth\Authorized;
use MagmaCore\Auth\Roles\PrivilegedUser;
use MagmaCore\Auth\Roles\Roles;
use MagmaCore\Middleware\BeforeMiddleware;

class AdminAuthentication extends BeforeMiddleware
{
    /**
     * Prevent unauthorized access to the administration panel. Only users with specific
     * privileges can access the admin area.
     *
     * @param Object $middleware
     * @param Closure $next
     * @return void
     */
    public function middleware(object $middleware, Closure $next)
    {
        $user = PrivilegedUser::getUser();
        if (!$user->hasPrivilege('have_admin_access')) {
            $middleware->flashMessage("<strong class=\"uk-text-danger\">Access Denied </strong>Sorry you need the correct privilege to access this area.", $middleware->flashInfo());
            $middleware->redirect(Authorized::getReturnToPage());
        }

        return $next($middleware);
    }
}
