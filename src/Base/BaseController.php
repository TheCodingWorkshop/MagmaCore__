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
// use MagmaCore\Base\Events\BeforeRenderActionEvent;
use MagmaCore\Base\Events\BeforeControllerActionEvent;
use MagmaCore\Base\Traits\ControllerFlashTrait;
use MagmaCore\Base\Traits\ControllerMenuTrait;
use MagmaCore\Base\Traits\ControllerMonitorTrait;
// use MagmaCore\Base\Traits\ControllerPrivilegeTrait;
use MagmaCore\Base\Traits\ControllerViewTrait;
// use MagmaCore\Session\GlobalManager\GlobalManager;
use MagmaCore\Utility\Yaml;
use MagmaCore\Base\BaseView;
// use MagmaCore\Auth\Authorized;
use MagmaCore\Base\BaseRedirect;
// use MagmaCore\Session\Flash\Flash;
use MagmaCore\Session\SessionTrait;
// use MagmaCore\Ash\TemplateExtension;
use MagmaCore\Middleware\Middleware;
// use MagmaCore\Session\Flash\FlashType;
// use MagmaCore\Base\Exception\BaseLogicException;
use MagmaCore\Base\Traits\ControllerCastingTrait;
// use MagmaCore\Auth\Roles\PrivilegedUser;
// use MagmaCore\UserManager\UserModel;
// use MagmaCore\UserManager\Rbac\Permission\PermissionModel;
use MagmaCore\Base\Exception\BaseBadMethodCallException;
use Exception;
use MagmaCore\Base\Traits\TableSettingsTrait;

class BaseController extends AbstractBaseController
{

    use SessionTrait,
        ControllerCastingTrait,
        //ControllerPrivilegeTrait,
        ControllerMenuTrait,
        TableSettingsTrait,
        ControllerFlashTrait,
        ControllerViewTrait,
        ControllerMonitorTrait;

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
    protected array $addLinkModelToArray = [];
    protected array $noSettingsController = [
        'setting',
        'dashboard',
        'history',
        'discovery',
        'notification'
    ];
    protected array $headers = ["User-Agent:", "Authorization:"];

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
        $this->pingMethods();

        $this->showDiscoveries();
        $this->recordHistory();
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
                http_response_code(404);
                $this->getSession()->set('invalid_method', $method);
                header('Location: http://localhost/error/errora');
                exit;        
                //throw new BaseBadMethodCallException("Method {$method} does not exists.");
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
     * @return array
     */
    public function getRoutes(): array
    {
        return $this->routeParams;
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

//    public function themeBuilder(): object
//    {
//        $themeBuilder = GlobalManager::get('themeBuilder_global');
//        return $themeBuilder;
//    }

    public function recordHistory(): void
    {
        $session = $this->getSession();
        $session->setArray('sesson_history_trace', ['history_path' => $_SERVER['HTTP_REFERER'], 'history_user' => $session->get('user_id'), 'history_browser_agent' => $_SERVER['HTTP_USER_AGENT'], 'history_timestamp' => date('h:i:s')]);
    }

    public function dump(mixed $var, bool $die = true, array $optional = [])
    {
        var_dump($var, $optional);
        if ($die) {
            die();
        }
    }

    public function getHttpCode($url) {
        $handle = curl_init($url);
        curl_setopt($handle,  CURLOPT_RETURNTRANSFER, TRUE);
        $response = curl_exec($handle);
        $httpCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);
        curl_close($handle);
        return $httpCode;         
      }    
}
