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

use MagmaCore\DataObjectLayer\FileStorageRepository\FileStorage;
use MagmaCore\Base\Exception\BaseLogicException;
use MagmaCore\EventDispatcher\EventDispatcher;
use MagmaCore\FormBuilder\FormBuilder;
use MagmaCore\Datatable\Datatable;
use MagmaCore\Session\SessionTrait;
//use MagmaCore\Session\SessionFactory;
use MagmaCore\Session\Flash\FlashType;
use MagmaCore\Session\Flash\Flash;
//use MagmaCore\Cookie\CookieFactory;
use MagmaCore\Base\BaseView;
use MagmaCore\Base\BaseRedirect;
use MagmaCore\Http\ResponseHandler;
use MagmaCore\Http\RequestHandler;
use MagmaCore\Middleware\Middleware;
//use MagmaCore\Translation\Translation;
use MagmaCore\Error\Error;

class BaseController extends AbstractBaseController
{

    use SessionTrait;

    /** @var array */
    protected array $routeParams;
    /** @var Object */
    protected Object $twig;
    /** @var array */
    protected array $callBeforeMiddlewares = [];
    /** @var array */
    protected array $callAfterMiddlewares = [];

    /**
     * Main class constructor
     *
     * @param array $routeParams
     */
    public function __construct(array $routeParams)
    {
        parent::__construct($routeParams);
        $this->routeParams = $routeParams;
        $this->twig = new BaseView();

        $this->container(
            [
                "request" => RequestHandler::class,
                "response" => ResponseHandler::class,
                "formBuilder" => FormBuilder::class,
                "eventDispatcher" => EventDispatcher::class,
                "error" => Error::class,
                "session" => "",
                "cache" => "",
                "cookie" => "",
                "tableGird" => Datatable::class,
                "flatDb" => FileStorage::class
            ]
        );

        $this->registerSubscribedServices();
        //$this->registerEventListenerServices();
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
        if (is_string($name) && $name !=='') {
            $method = $name . 'Action';
            if (method_exists($this, $method)) {
                if ($this->before() !== false) {
                    call_user_func_array([$this, $method], $argument);
                    $this->after();
                }
            }else {
                throw new \BadMethodCallException("Method {$method} does not exists.");
            }
        } else {
            throw new \Exception();
        }
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
    protected function callBeforeMiddlewares() : array
    {
        return $this->callBeforeMiddlewares;
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
    protected function callAfterMiddlewares() : array
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
        ->middleware($object, function($object){
            return $object;
        });
    }

    /**
     * After method. Call after controller action method
     * @return void
     */
    protected function after()
    { 
        $object = new self($this->routeParams);
        (new Middleware())->middlewares($this->callAfterMiddlewares())
        ->middleware($object, function($object){
            return $object;
        });

    }

    /**
     * Render a template response using Twig templating engine
     *
     * @param string $template
     * @param array $context - The context (arguments) of the template
     * @return response
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function render(string $template, array $context = [])
    {
        if (null === $this->twig) {
            throw new BaseLogicException('You can not use the render method if the Twig Bundle is not available.');
        }
        $response = (new ResponseHandler($this->twig->twigRender($template, $context)))->handler();
        if ($response) {
            return $response;
        }
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
            $responseCode);

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

    public function getSiteUrl(?string $path = null) : string
    {
        return sprintf(
            "%s://%s%s",
            isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http',
            $_SERVER['SERVER_NAME'],
            ($path !==null) ? $path : $_SERVER['REQUEST_URI']
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
    public function flashAndRedirect(bool $action, ?string $redirect = null, string $message, string $type = FlashType::SUCCESS) : void
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
    public function flashWarning() : string
    {
        return FlashType::WARNING;
    }

    /**
     * Returns the session based flash message type success as string
     *
     * @return string
     */
    public function flashSuccess() : string
    {
        return FlashType::SUCCESS;
    }

    /**
     * Returns the session based flash message type danger as string
     *
     * @return string
     */
    public function flashDanger() : string
    {
        return FlashType::DANGER;
    }

    /**
     * Returns the session based flash message type info as string
     *
     * @return string
     */
    public function flashInfo() : string
    {
        return FlashType::INFO;
    }

    /**
     * Returns a translation string to convert to default or choosen locale
     *
     * @param string $locale
     * @return string
     */
    public function locale(?string $locale = null) : ?string
    {
        /*if (null !== $locale)
            return Translation::getInstance()->$locale;*/
        return $locale;
    }

    /**
     * Returns the session object for use throughout any controller. Can be used 
     * to called any of the methods defined with the session class
     *
     * @return Object
     */
    public function getSession() : Object
    {
        return SessionTrait::sessionFromGlobal();
    }

}