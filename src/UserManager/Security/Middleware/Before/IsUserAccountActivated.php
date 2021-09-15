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

namespace MagmaCore\UserManager\Security\Middleware\Before;

use MagmaCore\UserManager\UserModel;
use Closure;
use MagmaCore\Middleware\BeforeMiddleware;

class isUserAccountActivated extends BeforeMiddleware
{

    /**
     * Prevent login access if a user account is either pending, lock or suspended.
     * Only active account user will be allowed in.
     *
     * @param object $middleware - contains the BaseController object
     * @param closure $next
     * @return void
     */
    public function middleware(object $middleware, Closure $next)
    {
        $message = '';
        if ($email = $middleware->request->handler()->get('email')) {
            if (isset($email)) {
                $user = (new UserModel())->getRepo()->findObjectBy(['email' => $email]);
                if (is_null($user)) {
                    $middleware->flashMessage('Account not found.', $middleware->flashWarning());
                    $middleware->redirect('/login');
                }
//                $message = match ($user->status) {
//                    'pending' => 'Account not activated.',
//                    'lock' => 'Your account is locked. Please contact support for more information',
//                    'suspended' => 'Your account is suspended.',
//                    'active' => 'Welcome',
//                };
//                $middleware->flashMessage($message, $middleware->flashWarning());
//                $middleware->redirect('/login');
            }
        }

        return $next($middleware);
    }
}
