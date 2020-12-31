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

namespace MagmaCore\Auth\Controller;

use MagmaCore\Base\BaseController;
use MagmaCore\Auth\Authorized;
use LoaderError;
use RuntimeError;
use SyntaxError;


class AuthSecurityController extends BaseController
{

    /**
     * Extends the base constructor method. Which gives us access to all the base 
     * methods inplemented within the base controller class.
     * Class dependency can be loaded within the constructor by calling the 
     * container method and passing in an associative array of dependency to use within
     * the class
     *
     * @param array $routeParams
     */
    public function __construct(array $routeParams)
    {
        parent::__construct($routeParams);
        $this->container(
            [
                "loginForm" => \MagmaCore\Auth\Form\LoginForm::class,
                "authenticator" => \MagmaCore\Auth\Authenticator::class,
                "request" => \MagmaCore\Http\RequestHandler::class,
                "form" => \MagmaCore\FormBuilder\FormBuilder::class
            ]
        );
    }

    /**
     * Before filter which is called before every controller
     * method. Use to check is user as privileges to be in the backend
     * or use to log data on requesting of methods
     *
     * @return void
     */
    protected function before()
    {
        if ($this->thisRouteController() === 'Security' && $this->thisRouteAction() === 'index') {
            $userID = $this->getSession()->get('user_id');#
            if (isset($userID) && $userID !== 0) {
                $this->redirect('/');
            }
        }
    }

    /**
     * After filter which is called after every controller. Can be used
     * for garbage collection
     *
     * @return void
     */
    protected function after()
    {}

    /**
     * Entry method which is hit on request. This method should be implement within
     * all sub controller class as a default landing point when a request is 
     * made.
     *
     * @return Response
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    protected function indexAction()
    { 

        if (isset($this->loginForm)) {
            $this->render(
                "client/security/index.html.twig",
                [
                    "form" => $this->loginForm->createForm("/security/login"),
                ]
            );
        }
    }

    /**
     * Security login process. 
     * 
     * @return Response
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    protected function loginAction()
    {
        $authenticatedUser = $this
        ->authenticator
        ->authenticate($_POST['email'], $_POST['password_hash']);

        $remember = $this->request->handler()->get('remember_me');
        if (isset($this->form)) :    
            // Checks the request can be handle and the form is submittable
            if ($this->form->canHandleRequest() && $this->form->isSubmittable('signin')) : {
                if ($this->form->csrfValidate()) {
                    if ($authenticatedUser) {
                        $this->getLogin($authenticatedUser, $remember);
                        $this->flashMessage($this->locale('login_successful'), $this->flashSuccess());
                        $this->redirect('/');
                    } else {
                        $this->flashMessage($this->locale('login_fail', $this->flashWarning()));
                        $this->redirect($this->onSelf());
                    }
                } else {
                    $this->flashMessage($this->locale('invalid_csrf', $this->flashDanger()));
                    $this->redirect($this->onSelf());
                }
            }
            endif;
        endif;
    }

    /**
     * Authorized logging out the current user. This will destroy the entire user
     * session and clear the remembered_logins database table of any cookies
     *
     * @return void
     */
    protected function logoutAction() : void
    {
        Authorized::logout();
        $this->redirect("/security/show-logout-message");
    }

    /**
     * Show a "logged out" flash message and redirect to the homepage.
     * Necessary to use the flash messages as they use the session and
     * at the end of the logout method (destroyAction) the session is destroyed
     * so a new action needs to be called in order to use the session.
     *
     * @return void
     * @throws Exception
     */
    protected function showLogoutMessageAction()
    {
        $this->flashMessage('Youv\'e successfully logged out', $this->flashInfo());
        $this->redirect('/');
    }

    /**
     * Returns the authenticated user as an object
     *
     * @param Object $authenticatedUser - returns the authicated user Object
     * @param bool $remember
     * @return void
     */
    private function getLogin(Object $authenticatedUser, $remember) : void
    {
        Authorized::login($authenticatedUser, $remember);
    }


}