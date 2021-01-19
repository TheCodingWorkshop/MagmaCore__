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

namespace MagmaCore\Auth;

use MagmaCore\Base\Exception\BaseUnexpectedValueException;
use MagmaCore\Auth\Model\RememberedLoginModel as RememberedLogin;
use MagmaCore\Auth\Roles\Roles;
use MagmaCore\Session\SessionTrait;
use MagmaCore\Cookie\CookieFacade;
use App\Model\UserModel;
use Throwable;

/**
 * @todo delete cookie from browser if database cookie token is not vaild or missing
 */
class Authorized
{ 

    use SessionTrait;

    /** @var string */
    protected const TOKEN_COOKIE_NAME = "remember_me";

    /**
     * Login the user
     *
     * @param object $user The user model
     * @param boolean $rememberMe Remember the login if true
     * @return void
     * @throws GlobalManagerException
     * @throws Exception
     */
    public static function login(Object $user, $rememberMe)
    {
        /* Set userID Session here */
        SessionTrait::registerUserSession($user->id ? $user->id : 0); /* 0 is Gueast */
        if ($rememberMe) {
            $rememberLogin = new RememberedLogin();
            list($token, $timestampExpiry) = $rememberLogin->rememberedLogin($user->id);
            if ($token !=null) {
                $cookie = (new CookieFacade(['name' => self::TOKEN_COOKIE_NAME, 'expires' => $timestampExpiry]))->initialize();
                $cookie->set($token);
            }
        }
    }

    /**
     * Helper function for getting the current user ID from the active session
     *
     * @return int
     * @throws GlobalManagerException
     */
    protected static function getCurrentSessionID()
    {
        return intval(SessionTrait::sessionFromGlobal()->get('user_id'));
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public static function grantedUser()
    {
        $userSessionID = self::getCurrentSessionID();
        if (isset($userSessionID) && $userSessionID !==0) {
            $user = (new UserModel())
            ->getRepo()
            ->findObjectBy( /* We only want to return a few columns */
                ['id' => $userSessionID],
                [
                    'id',
                    'email',
                    'firstname',
                    'lastname',
                    'password_hash',
                    'gravatar',
                    'status'
                ]
            );
            if ($user === null) {
                throw new BaseUnexpectedValueException('Empty user object returned. Please try again');
            }
            $priviUser = new Roles();
            $priviUser->id = $user->id;
            $priviUser->email = $user->email;
            $priviUser->firstname = $user->firstname;
            $priviUser->lastname = $user->lastname;
            $priviUser->name = "{$user->firstname} {$user->lastname}";
            $priviUser->role = ["all"];
            $priviUser->password_hash = $user->password_hash;
            $priviUser->gravatar = $user->gravatar;
            $priviUser->status = $user->status;

            $priviUser->initRoles($user->id);
            return $priviUser;
        } else {
            $user = self::loginFromRemembermeCookie();
            if ($user) {
                return $user;
            } 
        }

    }

    /**
     * Logout the user and kill the user session and also delete the cookie
     * created for user login
     *
     * @return void
     * @throws GlobalManagerException
     * @throws Throwable
     */
    public static function logout()
    {
        if (self::getCurrentSessionID() !=null) {
            SessionTrait::SessionFromGlobal()->invalidate();
            self::forgetLogin();

        }
    }

    /**
     * Remember the originally-requested page in the session
     *
     * @return void
     * @throws GlobalManagerException
     */
    public static function rememberRequestedPage()
    {
        SessionTrait::sessionFromGlobal()->set('return_to', $_SERVER['REQUEST_URI']);
    }

    /**
     * Get the originally-requested page to return to after requiring login,
     * or default to the homepage
     *
     * @return void
     * @throws GlobalManagerException
     */
    public static function getReturnToPage()
    {
        $page = SessionTrait::sessionFromGlobal()->get('return_to');
        return $page ?? '/';
    }

    /**
     * Login the user from a remembered login cookie
     *
     * @return object or null
     * @throws GlobalManagerException
     * @throws Throwable
     */
    protected static function loginFromRemembermeCookie()
    {
        $cookie = $_COOKIE[self::TOKEN_COOKIE_NAME] ?? false;
        if ($cookie) {
            $rememberLogin = new RememberedLogin();
            $cookieToken = $rememberLogin->findByToken($cookie);
            if ($cookieToken && !$rememberLogin->hasExpired($cookieToken->expires_at)) {
                $user = $rememberLogin->getUser($cookieToken->id);
                if ($user) {
                    self::login($user, false);
                    return $user;
                } 
            }
        }
    }

    /**
     * Forget the remembered login, if present
     *
     * @return bool
     * @throws Throwable
     */
    protected static function forgetLogin()
    {
        $cookie = $_COOKIE[self::TOKEN_COOKIE_NAME] ?? false;
        if ($cookie) {    
            $rememberLogin = new RememberedLogin();
            $rememberCookie = $rememberLogin->findByToken($cookie);
            if ($rememberCookie) {        
                $rememberLogin->destroy($rememberCookie->token_hash);
            }
            /* expire cookie here */
            $cookie = (new CookieFacade(['name' => self::TOKEN_COOKIE_NAME]))->initialize();
            $cookie->delete();
        }    
    }

}
