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

namespace MagmaCore\Base;

use MagmaCore\Base\BaseApplication;
use MagmaCore\Base\Events\BeforeRenderActionEvent;
use MagmaCore\Base\Events\BeforeControllerActionEvent;
use MagmaCore\Base\Traits\ControllerMenuTrait;
use MagmaCore\Base\Traits\ControllerPrivilegeTrait;
use MagmaCore\Session\GlobalManager\GlobalManager;
use MagmaCore\Utility\Yaml;
use MagmaCore\Base\BaseView;
use MagmaCore\Auth\Authorized;
use MagmaCore\Base\BaseRedirect;
use MagmaCore\Session\Flash\Flash;
use MagmaCore\Session\SessionTrait;
use MagmaCore\Ash\TemplateExtension;
use MagmaCore\Middleware\Middleware;
use MagmaCore\Session\Flash\FlashType;
use MagmaCore\Base\Exception\BaseLogicException;
use MagmaCore\Base\Traits\ControllerCastingTrait;
use MagmaCore\Auth\Roles\PrivilegedUser;
use MagmaCore\UserManager\UserModel;
use MagmaCore\UserManager\Rbac\Permission\PermissionModel;
use MagmaCore\Base\Exception\BaseBadMethodCallException;
use Exception;
use MagmaCore\Base\Traits\TableSettingsTrait;

class BaseController extends AbstractBaseController
{

    use SessionTrait,
        ControllerCastingTrait,
        ControllerPrivilegeTrait,
        ControllerMenuTrait,
        TableSettingsTrait;

    /** @var array */
    protected array $routeParams;
    /** @var object */
    protected Object $templateEngine;
    /** @var */
    protected object $template;
    /** @var array */
    protected array $callBeforeMiddlewares = [];
    /** @var array */
    protected array $callAfterMiddlewares = [];
    protected array $controllerContext = [];

    protected array $noSettingsController = [
        'setting',
        'dashboard'
    ];

    /**
     * Main class constructor
     *
     * @param array $routeParams
     */
    public function __construct(array $routeParams, array $menuItems = [])
    {
        parent::__construct($routeParams);
        $this->routeParams = $routeParams;
        $this->templateEngine = new BaseView();

        $this->diContainer(Yaml::file('providers'));
        $this->initEvents();
        $this->buildControllerMenu($routeParams);

        if (!in_array($routeParams['controller'], $this->noSettingsController)) {
            $this->initalizeControllerSession($this);
        }

    }

    /**
     * Return and instance of the base application class
     * @return \MagmaCore\Base\BaseApplication
     */
    public function baseApp()
    {
        return new BaseApplication();
    }

    public function getRouteParams(): array
    {
        return $this->routeParams;
    }

    /**
     * Magic method called when a non-existent or inaccessible method is
     * called on an object of this class. Used to execute before and after
     * filter methods on action methods. Action methods need to be named
     * with an "Action" suffix, e.g. indexAction, showAction etc.
     *
     * @param $name
     * @param $arguments
     * @throws BaseException
     * @return void
     */
    public function __call($name, $argument)
    {
        if (is_string($name) && $name !== '') {
            $method = $name . 'Action';
            if (method_exists($this, $method)) {
                if ($this->eventDispatcher->hasListeners(BeforeControllerActionEvent::NAME)) {
                    $this->dispatchEvent(
                        BeforeControllerActionEvent::class, 
                        $name, 
                        $this->routeParams, 
                        $this
                    );
                }        
                if ($this->before() !== false) {
                    call_user_func_array([$this, $method], $argument);
                    $this->after();
                }
            } else {
                throw new BaseBadMethodCallException("Method {$method} does not exists.");
            }
        } else {
            throw new Exception;
        }
    }

    protected function defineCoreMiddeware(): array
    {
        return [
            'error404' => Erorr404::class
        ];
    }

    /**
     * Returns an array of middlewares for the current object which will
     * execute before the action is called. Middlewares are also resolved
     * via the container object. So you can also type hint any dependency
     * you need within your middleware constructor. Note constructor arguments
     * cannot be resolved only other objects
     *
     * @return array
     */
    protected function callBeforeMiddlewares(): array
    {
        return array_merge($this->defineCoreMiddeware(), $this->callBeforeMiddlewares);
    }

    /**
     * Returns an array of middlewares for the current object which will
     * execute before the action is called. Middlewares are also resolved
     * via the container object. So you can also type hint any dependency
     * you need within your middleware constructor. Note constructor arguments
     * cannot be resolved only other objects
     *
     * @return array
     */
    protected function callAfterMiddlewares(): array
    {
        return $this->callAfterMiddlewares;
    }

    /**
     * Before method. Call before controller action method
     * @return void
     */
    protected function before()
    {
        $object = new self($this->routeParams);
        (new Middleware())->middlewares($this->callBeforeMiddlewares())
            ->middleware($object, function ($object) {
                return $object;
            });
    }

    /**
     * After method. Call after controller action method
     * 
     * @return void
     */
    protected function after()
    {
        $object = new self($this->routeParams);
        (new Middleware())->middlewares($this->callAfterMiddlewares())
            ->middleware($object, function ($object) {
                return $object;
            });
    }

    /**
     * Template context which relies on the application owning a user and permission
     * model before providing any data to the rendered template
     * 
     * @return array
     */
    private function templateModelContext(): array
    {
        if (!class_exists(UserModel::class) || !class_exists(PermissionModel::class)) {
            return array();
        }
        return array_merge(
            ['current_user' => Authorized::grantedUser()],
            ['this_route' => strtolower($this->thisRouteController())],
            ['this_action' => strtolower($this->thisRouteAction())],
            ['this_namespace' => strtolower($this->thisRouteNamespace())],
            ['privilege_user' => PrivilegedUser::getUser()],
            ['func' => new TemplateExtension($this)],
        );
    }

    /**
     * Return some global context to all rendered templates
     * 
     * @return array
     */
    private function templateGlobalContext(): array
    {
        return array_merge(
            ['app' => Yaml::file('app')],
            ['menu' => Yaml::file('menu')],
            ['routes' => (isset($this->routeParams) ? $this->routeParams : [])]
        );
    }

    /**
     * Allow all routes within a controller to access a central define set of template context variable. Which uses
     * teh system session to store the current controller and validate that only the context set in a specific
     * wont be accessible from another controller routes
     *
     * controller global set in userController wont be accessible in roleController
     *
     * @return array
     */
    protected function controllerViewGlobals(): array
    {
        $currentController = $this->getSession()->set('controller', $this->routeParams['controller']);
        if ($this->thisRouteController() === $currentController) {
            return $this->controllerContext;
        }
        return array();

    }

    /**
     * Rendered template exception
     * @return void
     */
    private function throwViewException(): void
    {
        if (null === $this->templateEngine) {
            throw new BaseLogicException(
                'You can not use the render method if the build in template engine is not available.'
            );
        }

    }

    /**
     * Render a template response using Twig templating engine
     *
     * @param string $template - the rendering template
     * @param array $context - template data context
     * @return Response
     * @throws LoaderError
     * @throws BaseLogicException
     */
    public function view(string $template, array $context = [])
    {
        $this->throwViewException();
        $templateContext = array_merge(
            $this->templateGlobalContext(), 
            $this->templateModelContext()
        );
        if ($this->eventDispatcher->hasListeners(BeforeRenderActionEvent::NAME)) {
            $this->dispatchEvent(BeforeRenderActionEvent::class);
        }
        $response = $this->response->handler();
        $request = $this->request->handler();
        $response->setCharset('ISO-8859-1');
        $response->headers->set('Content-Type', 'text/plain');
        $response->setStatusCode($response::HTTP_OK);
        $response->setContent($this->templateEngine->ashRender($template, array_merge($context, $templateContext, $this->controllerViewGlobals())));
        if ($response->isNotModified($request)) {
            $response->prepare($request);
            $response->send();
        }
    }

    /**
     * @return array
     */
    public function getRoutes(): array
    {
        return $this->routeParams;
    }

    /**
     * Alias of view() method
     *
     * @param string $template - the rendering template
     * @param array $context - template data context
     * @return Response
     * @throws LoaderError
     * @throws BaseLogicException
     */
    public function render(string $template, array $context = [])
    {
        return $this->view($template, $context);
    }

    /**
     * @inheritdoc
     *
     * @param string $url
     * @param boolean $replace
     * @param integer $responseCode
     * @return void
     */
    public function redirect(string $url, bool $replace = true, int $responseCode = 303)
    {
        $this->redirect = new BaseRedirect(
            $url,
            $this->routeParams,
            $replace,
            $responseCode
        );

        if ($this->redirect) {
            $this->redirect->redirect();
        }
    }

    public function onSelf()
    {
        if (isset($_SERVER['REQUEST_URI'])) {
            return $_SERVER['REQUEST_URI'];
        }
    }

    public function getSiteUrl(?string $path = null): string
    {
        return sprintf(
            "%s://%s%s",
            isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http',
            $_SERVER['SERVER_NAME'],
            ($path !== null) ? $path : $_SERVER['REQUEST_URI']
        );
    }

    /**
     * Conbination method which encapsulate the flashing and redirecting all within
     * a single method. Use the relevant arguments to customized the output
     *
     * @param boolean $action
     * @param string|null $redirect
     * @param string $message
     * @param string $type
     * @return void
     */
    public function flashAndRedirect(bool $action, ?string $redirect = null, string $message, string $type = FlashType::SUCCESS): void
    {
        if (is_bool($action)) {
            $this->flashMessage($message, $type);
            $this->redirect(($redirect === null) ? $this->onSelf() : $redirect);
        }
    }

    /**
     * Returns the session based flash message
     *
     * @param string $message
     * @param string $type
     * @return void
     */
    public function flashMessage(string $message, string $type = FlashType::SUCCESS)
    {
        $flash = (new Flash(SessionTrait::sessionFromGlobal()))->add($message, $type);
        if ($flash) {
            return $flash;
        }
    }

    /**
     * Returns the session based flash message type warning as string
     *
     * @return string
     */
    public function flashWarning(): string
    {
        return FlashType::WARNING;
    }

    /**
     * Returns the session based flash message type success as string
     *
     * @return string
     */
    public function flashSuccess(): string
    {
        return FlashType::SUCCESS;
    }

    /**
     * Returns the session based flash message type danger as string
     *
     * @return string
     */
    public function flashDanger(): string
    {
        return FlashType::DANGER;
    }

    /**
     * Returns the session based flash message type info as string
     *
     * @return string
     */
    public function flashInfo(): string
    {
        return FlashType::INFO;
    }

    /**
     * Returns a translation string to convert to default or choosen locale
     *
     * @param string $locale
     * @return string
     */
    public function locale(?string $locale = null): ?string
    {
        /*if (null !== $locale)
            return Translation::getInstance()->$locale;*/
        return $locale;
    }

    /**
     * Returns the session object for use throughout any controller. Can be used 
     * to called any of the methods defined with the session class
     *
     * @return object
     */
    public function getSession(): object
    {
        return SessionTrait::sessionFromGlobal();
    }

    public function getCache()
    {
        return $this->cache();
    }

    /**
     * Return the cache object
     */
    public function cache(): object
    {
        return $this->baseApp($this)->loadCache();
    }

    public function themeBuilder(): object
    {
        $themeBuilder = GlobalManager::get('themeBuilder_global');
        return $themeBuilder;
    }



}
