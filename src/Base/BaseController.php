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

use MagmaCore\Base\Exception\BaseInvalidArgumentException;
use MagmaCore\Base\Exception\BaseLogicException;
use MagmaCore\Base\BaseView;
use MagmaCore\Base\BaseRedirect;
use MagmaCore\Base\BaseApplication;
use MagmaCore\Session\Flash\FlashType;
use MagmaCore\Session\Flash\Flash;
use MagmaCore\Http\ResponseHandler;
use MagmaCore\Http\RequestHandler;
use MagmaCore\Session\SessionTrait;
use MagmaCore\Middleware\Middleware;
use ReflectionMethod;
use StdClass;

class BaseController
{

    use SessionTrait;

    /** @var array */
    protected array $routeParams;
    /** @var Object */
    protected Object $twig;
    /** @var string - flash danger type */
    protected string $flashDanger;
    /** @var string - flash info type */
    protected string $flashInfo;
    /** @var string - flash success type */
    protected string $flashSuccess;
    /** @var string - flash warning type */
    protected string $flashWarning;
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
        $this->routeParams = $routeParams;
        $this->flashDanger = FlashType::DANGER;
        $this->flashWarning = FlashType::WARNING;
        $this->flashSuccess = FlashType::SUCCESS;
        $this->flashInfo = FlashType::INFO;
        $this->twig = new BaseView();

        $this->container(
            [
                "request" => RequestHandler::class,
                "response" => ResponseHandler::class,
            ]
        );
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
     * Get the reflection action method
     *
     * @param [type] $method
     * @param array $argument
     * @return void
     */
    private function getReflectionAction($method, array $argument)
    {
        $reflectionMethod = new ReflectionMethod($this, $method);
        $args = [];
        foreach ($reflectionMethod->getParameters() as $param) {
            $name = $param->getName();
            $class = $param->getClass();
            if ($class === null) {
                $args[] = $this->routeParams[$name];
            } else {
                if ($class->isInstance('')) {
                    $args[] = '';
                } else {
                    throw new \BadMethodCallException("Method {$method} does not exists.");
                }
                return call_user_func_array([$this, $method], $argument);
            }
        }
    }

    /**
     * Method for allowing child controller class to dependency inject other objects
     * 
     * @param array|null $args
     * @return Object
     * @throws BaseInvalidArgumentException
     * @throws ReflectionException
     */
    protected function container(?array $args = null)
    {
        if ($args !==null && !is_array($args)) {
            throw new BaseInvalidArgumentException('Invalid argument called in container. Your dependencies should return a key/value pair array.');
        }
        $args = func_get_args();
        if ($args) {
            $output = '';
            foreach ($args as $arg) {
                foreach ($arg as $property => $class) {
                    if (strpos($class, $arg[$property]) !== false) {
                        if ($class) {
                            $output = ($property === 'dataColumns' || $property === 'column') ? $this->$property = $class : $this->$property = BaseApplication::diGet($class);
                        }
                    }
                }
            }
            return $output;
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
     * Return the current controller name as a string
     * @return string
     */
    public function thisRouteController() : string
    {
         return $this->routeParams['controller'];
    }

    /**
     * Return the current controller action as a string
     * @return string
     */
    public function thisRouteAction() : string
    {
        return $this->routeParams['action'];
    }

    /**
     * Return the current controller namespade as a string
     * @return string
     */
    public function thisRouteNamespace() : string
    {
        return $this->routeParams['namespace'];
    }

    /**
     * Return the current controller token as a string
     * @return string
     */
    public function thisRouteToken() : string
    {
        return $this->routeParams['token'];
    }

    /**
     * Return the current controller route ID if set as a int
     * @return int
     */
    public function thisRouteID() : int
    {
        return $this->routeParams['id'];
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
            return $this->redirect->redirect();
        }
    }

    public function onSelf()
    {
        $controller = $this->routeParams['controller'];
        $namespace = $this->routeParams['namespace'];
        $action = $this->routeParams['action'];
        $id = $this->routeParams['id'];
        $sep = '/';

        if (isset($controller) && is_string($controller) && $controller !=null) {
            if (isset($action) && is_string($action)) {
                switch ($action) {
                    default :
                        if ($this->id !==null || $this->id !==0) {
                            $path = "{$namespace}/{$controller}/{$id}/{$action}";
                        } else {
                            $path = "{$namespace}/{$controller}/{$action}";
                        }
                        if (isset($path) && $path !='') {
                            return rtrim($path);
                        }
                        break;
                }
            }
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
        return $this->flashWarning;
    }

    /**
     * Returns the session based flash message type success as string
     *
     * @return string
     */
    public function flashSuccess() : string
    {
        return $this->flashSuccess;
    }

    /**
     * Returns the session based flash message type danger as string
     *
     * @return string
     */
    public function flashDanger() : string
    {
        return $this->flashDanger;
    }

    /**
     * Returns the session based flash message type info as string
     *
     * @return string
     */
    public function flashInfo() : string
    {
        return $this->flashInfo;
    }

    /**
     * Returns a translation string to convert to default or choosen locale
     *
     * @param string $text
     * @return string
     */
    public function locale(string $text) : string
    {
        return $text;
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