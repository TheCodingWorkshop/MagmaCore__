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
use MagmaCore\Middleware\BeforeMiddleware;
use MagmaCore\Utility\Yaml;

class SessionExpires extends BeforeMiddleware
{

    /** @var int */
    protected const SESSION_TIMEOUT = 600;

    /**
     * Expires the session if left idle for more than the specified allowed time set.
     * from the application session.yaml file. else will default to class constant which
     * defaults to 30min idle time;
     *
     * @param object $middleware - contains the BaseController object
     * @param Closure $next
     * @return void
     */
    public function middleware(object $middleware, Closure $next)
    {
        $session = $middleware->getSession();
        if (null !== $session->get('timeout')) {
            $duration = time() - (int)$session->get('timeout');
            $lifetime = Yaml::file('session')['lifetime']; /* Get session lifetime from yaml file */
            $expires = ($lifetime !== 0) ? $lifetime : self::SESSION_TIMEOUT;
            if ($duration > $expires) {
                $session->invalidate();
                /** @todo let the user know the session was expired */
                $middleware->redirect('/security/session');
            } else {
                $session->set('timeout', time());
            }
        }
        $session->set('timeout', time());
        //$this->expire($object);
        return $next($middleware);
    }

    public function expire(object $object)
    {
        // $session = $object->getSession();
        // if ($session->get('user_id') !==null) {
        //     if (isset($_SESSION['is_login']) && $_SESSION['is_login'] == true) {
        //         if (time() - $session->get('last_login') > 60) {
        //             $session->invalidate();
        //             $object->redirect('/security/session');
        //         }
        //     }

        // } else {
        //     $object->redirect('/login');
        // }
        // if (isset($session->get()))
    }

}