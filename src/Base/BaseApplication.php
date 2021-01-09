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
use MagmaCore\Router\RouterFactory;
use MagmaCore\Session\SessionFacade;
use MagmaCore\Session\Flash\Flash;
use MagmaCore\Session\Flash\FlashType;
use MagmaCore\Utility\Yaml;
use MagmaCore\Container\ContainerFactory;

class BaseApplication
{
    /** @var string - the current application version */
    protected const MAGMA_MIN_VERSION = '1.0.0';

    /** @var Object|null - The Router Object - Leaving null will return the default Router Object */
    protected ?Object $routerObject = null;
    /** @var array - an associative array of user defined routes */
    protected array $routes = [];
    /** @var string|null  */
    protected ?string $urlRoute = null;
    /** @var array - session environment */
    protected array $sessionEnvironment = [];
    /** @var string - session identifier */
    protected ?string $sessionIdentifier = null;
    /** @var string - session storage */
    protected ?string $sessionStorage = null;
    /** @var string $appPath */
    protected string $appPath;
    /** @var array */
    protected array $appConfig = [];
    /** @var string */
    protected $diContainer;

    /** @return void */
    public function __construct()
    { }

    /**
     * Set the application document path
     *
     * @param string $path
     * @return void
     */
    public function setPath(string $path) : void
    {
        $this->appPath = $path;
    }

    /**
     * Get and return the document path
     *
     * @return string
     */
    public function getPath() : string
    {
        return $this->appPath;
    }

    /**
     * Load a yaml configuration file
     *
     * @param string $file
     * @return void
     */
    public function getFromYaml(string $file) : array
    {
        return Yaml::file($file);
    }

    /**
     * Defined common constants which are commonly used throughout the framework
     *
     * @param string $appPath
     * @return void
     */
    private function definedConstants() : void
    {
        if (empty($this->appPath)) {
            throw new BaseInvalidArgumentException('Invalid Path. Please set the application directory path.');
        }
        defined('APP_ROOT') or define('APP_ROOT', $this->getPath());
        defined('CONFIG_PATH') or define('CONFIG_PATH', APP_ROOT . DS . 'Config');
        defined('TEMPLATE_PATH') or define('TEMPLATE_PATH', APP_ROOT . DS . 'App');
        defined('STORAGE_PATH') or define('STORAGE_PATH', APP_ROOT . DS . 'Storage');
        defined('LOG_PATH') or define('LOG_PATH', STORAGE_PATH . DS . 'Logs');
        defined('ERROR_RESOURCE') or define('ERROR_RESOURCE', APP_ROOT . DS . 'vendor/magmacore/magmacore/src/ErrorHandler/Resources');

    }

    /**
     * Pipe the router object and an array of user define routes. This could be set from
     * a yaml file or a regular PHP array. The application will take either and run the 
     * appropritate method to extract the values needed.
     * Default router object is defined within the RouterFactory class
     *
     * @param array $routes
     * @param Object $routerObject
     * @return void
     */
    public function setRouter(
        array $routes, 
        ?Object $routerObject = null, 
        ?string $urlRoute = null
        ) : void
    {
        if (
            empty($routes) || 
            is_array($routes) && 
            count($routes) < 0 ||
            !is_array($routes)
            ) {
            throw new BaseInvalidArgumentException(
                "Invalid or no routes set. This is an absolute must in order to render any routes within the application."
            );
        }
        $this->routes = $routes;
        $this->routerObject = $routerObject;
        $this->urlRoute = ($urlRoute !=null) ? $urlRoute : NULL;

    }

    /**
     * Automatically wire up the application routes internally making using the application
     * quick and easy. The actual router object is optional as the framework will use the default
     * router. The option is there only if we wish to implement an alternative router class.
     * However the script absolutely requires an array of routes to render. This is not optional. 
     * failure will result in a exception being thrown.
     *
     * @return void
     * @uses RouterFactory
     */
    private function autoWireRoutes()
    {
        if (!class_exists(RouterFactory::class)) {
            throw new \BadFunctionCallException(RouterFactory::class . " Class does not exists. Ensure this package is installed through Composer.");
        }
        $factory = new RouterFactory($this->urlRoute);
        $factory->create($this->routerObject);
        if (count($this->routes) > 0)
            return $factory->buildRoutes($this->routes);    
    }

    /**
     * Set up session through the session facade class. Assigning an optional arguments
     * to the method. Session is automatically globalized so session object can be access
     * through the GlobalManager::get('session_global')
     *
     * @param array|null $sessionEnvironment - defaults to internal environment in SessionFacade
     * @param string|null $sessionIdentifier - defaults to declared string in SessionFacade
     * @param string|null $sessionStorage - defaults to NativeSessionStorage in SessionFacade
     * @return void
     */
    public function setSession(
        ?array $sessionEnvironment = null, 
        ?string $sessionIdentifier = null, 
        ?string $sessionStorage = null
    )
    {
        if (!Yaml::file('app')['system']['use_session'] === true) {
            throw new \BadFunctionCallException("The session component is disable within your app.yaml configuration file please enable this in order to proceed.");
        }

        $session = (new SessionFacade($sessionEnvironment, $sessionIdentifier, $sessionStorage))->setSession();
        if ($session) {
            return $session;
        }

    }

    /**
     * Return the session global variable through a static method which should make
     * accessing the global variable much more simplier
     * returns the session object
     *
     * @return Object
     */
    public static function getSession() : Object
    {
        return \MagmaCore\Session\GlobalManager\GlobalManager::get('session_global');
    }

    /**
     * Set the application configurations. This can be your application main app.yaml
     * configuration file.
     *
     * @param array $appConfig
     * @return void
     */
    public function setConfig(array $appConfig) : void
    {
        if (!file_exists(CONFIG_PATH . '/app.yml')) {
            throw new BaseInvalidArgumentException('No app.yml file was detected within your application Config directory.');
        }
        $this->appConfig = $appConfig;
    }

    /**
     * Returns an array of your application app.yaml configuration settings
     *
     * @return array
     */
    public function getConfig() : array
    {
        return $this->appConfig;
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    private function defaultEnvironment() : void
    {
        ini_set('default_charset', $this->appConfig['settings']['default_charset']);
    }

    /**
     * Convert PHP errors to exception and set a custom exception
     * handler. Which allows us to take control or error handling 
     * so we can display errors in a customizable way
     *
     * @return void
     */
    public function setErrorHandlers() : void
    {
        error_reporting(E_ALL | E_STRICT);
        set_error_handler('MagmaCore\ErrorHandler\ErrorHandler::errorHandle');
        set_exception_handler('MagmaCore\ErrorHandler\ErrorHandler::exceptionHandle');
    }

    /**
     * Set the dependency Object to use within the application. This will default to
     * the internal container Object
     *
     * @param string|null $diContainer
     * @return void
     */
    public function setContainer(?string $diContainer = null) : void
    {
        $this->diContainer = $diContainer;
    }

    /**
     * Get the dependency from the dependency container
     *
     * @param string $class
     * @return void
     */
    public static function diGet(string $class)
    {
        $di = (new ContainerFactory())->create();
        if ($di) {
            return $di->get($class);
        }
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function run()
    {
        $this->definedConstants();
        if (version_compare($phpVersion = PHP_VERSION, $coreVersion = self::MAGMA_MIN_VERSION, '<')) {
            die(sprintf('You are runninig PHP %s, but the core framework requires at least PHP %s', $phpVersion, $coreVersion));
        }
        
        $this->defaultEnvironment();
        $this->autoWireRoutes();

    }

}