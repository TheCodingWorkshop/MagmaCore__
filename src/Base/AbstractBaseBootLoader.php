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

use MagmaCore\Logger\Logger;
use MagmaCore\Themes\ThemeBuilder;
use MagmaCore\Base\BaseApplication;
use MagmaCore\Router\RouterFactory;
use MagmaCore\Session\SessionFacade;
use MagmaCore\Base\Traits\BootstrapTrait;
use MagmaCore\Container\ContainerFactory;
use MagmaCore\Session\GlobalManager\GlobalManager;
use MagmaCore\Cache\CacheFacade;
use MagmaCore\Logger\LoggerFactory;

abstract class AbstractBaseBootLoader
{

    use BootstrapTrait;

    /** @var BaseApplication $application */
    protected BaseApplication $application;

    /**
     * Main class constructor
     *
     * @param BaseApplication $application
     */
    public function __construct(BaseApplication $application)
    {
        $this->application = $application;
    }

    /**
     * Compare PHP version with the core version ensuring the correct version of
     * PHP and MagmaCore framework is being used at all time in sync.
     *
     * @return void
     */
    protected function phpVersion(): void
    {
        if (version_compare($phpVersion = PHP_VERSION, $coreVersion = $this->app()->getConfig()['app']['app_version'], '<')) {
            die(sprintf('You are runninig PHP %s, but the core framework requires at least PHP %s', $phpVersion, $coreVersion));
        }
    }

    /**
     * Returns the bootstrap appplications object
     *
     * @return BaseAPplication
     */
    public function app(): BaseApplication
    {
        return $this->application;
    }

    /**
     * Return the session global variable through a static method which should make
     * accessing the global variable much more simplier
     * returns the session object
     *
     * @return Object
     */
    public static function getSession(): Object
    {
        return GlobalManager::get('session_global');
    }

    /**
     * Load the framework default enviornment configuration. Most configurations
     * can be done from inside the app.yml file
     *
     * @return void
     */
    protected function loadEnvironment(): void
    {
        $settings = $this->app()->getConfig()['settings'];
        ini_set('default_charset', $settings['default_charset']);
        date_default_timezone_set($settings['default_timezone']);
    }

    /**
     * Returns an array of the application set providers which will be loaded
     * by the dependency container. Which uses PHP Reflection class to
     * create objects. With a key property which is defined within the yaml
     * providers file
     *
     * @return array
     */
    protected function loadProviders(): array
    {
        return $this->app()->getContainerProviders();
    }

    /**
     * Initialise the pass our classes to be loaded by the framework dependency
     * container using PHP Reflection
     *
     * @param string $dependencies
     * @return mixed
     */
    public static function diGet(string $dependencies): mixed
    {
        $container = (new ContainerFactory())->create();
        if ($container) {
            return $container->get($dependencies);
        }
    }

    /**
     * Returns the default route handler mechanism
     *
     * @return string
     */
    protected function defaultRouteHandler(): string
    {
        if (isset($_SERVER))
            return $_SERVER['QUERY_STRING'];
    }

    /**
     * Get the default session driver defined with the session.yml file
     *
     * @return string
     */
    protected function getDefaultSessionDriver(): string
    {
        return $this->getDefaultSettings($this->app()->getSessions());
    }

    /**
     * Get the default cache driver defined with the cache.yml file
     *
     * @return string
     */
    protected function getDefaultCacheDriver(): string
    {
        return $this->getDefaultSettings($this->app()->getCache());
    }


    /**
     * Builds the application session component and returns the configured object. Based
     * on the session configuration array.
     *
     * @return object - returns the session object
     */
    protected function loadSession(): Object
    {
        $session = (new SessionFacade(
            $this->app()->getSessions(),
            $this->app()->getSessions()['session_name'],
            $this->app()->getSessionDriver()
        ))->setSession();

        GLobalManager::set('session_global', $session);
        return $session;
    }

    public function loadCache()
    {
        $cache = (new CacheFacade())->create($this->getCacheIdentifier(), \MagmaCore\Cache\Storage\NativeCacheStorage::class);
        GLobalManager::set('cache_global', $cache);
        return $cache;

    }

    protected function loadRoutes()
    {
        $factory = new RouterFactory($this->app()->getRouteHandler());
        $factory->create($this->app()->getRouter());
        if (count($this->app()->getRoutes()) > 0) {
            return $factory->buildRoutes($this->app()->getRoutes());
        }
    }

    protected function loadErrorHandlers(): void
    {
        error_reporting($this->app()->getErrorHandlerLevel());
        set_error_handler($this->app()->getErrorHandling()['error']);
        set_exception_handler($this->app()->getErrorHandling()['exception']);
    }

    /**
     * @return mixed
     */
    protected function loadLogger()
    {
        return (new LoggerFactory())
            ->create(
                $this->app()->getLoggerFile(),
                $this->app()->getLogger(),
                $this->app()->getLogMinLevel(),
                $this->app()->getLoggerOptions()
            );
    }

    /**
     * @throws \MagmaCore\Themes\Exception\ThemeBuilderInvalidArgumentException
     */
    public function getTheming()
    {
        (new ThemeBuilder())->create($this->app()->getTheme());
    }

    /**
     * Defined common constants which are commonly used throughout the framework
     *
     * @return void
     */
    public function load(): void
    {
        defined('DS') or define('DS', DIRECTORY_SEPARATOR);
        defined('APP_ROOT') or define('APP_ROOT', $this->app()->getPath());
        defined('PUBLIC_PATH') or define('PUBLIC_PATH', 'public');
        defined('ASSET_PATH') or define('ASSET_PATH', '/' . PUBLIC_PATH . '/assets');
        defined('CSS_PATH') or define('CSS_PATH', ASSET_PATH . '/css');
        defined('JS_PATH') or define('JS_PATH', ASSET_PATH . '/js');
        defined('IMAGE_PATH') or define('IMAGE_PATH', ASSET_PATH . '/images');

        defined('TEMPLATE_PATH') or define('TEMPLATE_PATH', APP_ROOT . DS . 'App');
        defined('TEMPLATES') or define('TEMPLATES', $_SERVER['DOCUMENT_ROOT'] . 'App/Templates/');
        defined('STORAGE_PATH') or define('STORAGE_PATH', APP_ROOT . DS . 'Storage');
        defined('CACHE_PATH') or define('CACHE_PATH', STORAGE_PATH . DS);
        defined('LOG_PATH') or define('LOG_PATH', STORAGE_PATH . DS . 'logs');
        defined('ROOT_URI') or define('ROOT_URI', '');
        defined('RESOURCES') or define('RESOURCES', ROOT_URI);
        defined('UPLOAD_PATH') or define("UPLOAD_PATH", $_SERVER['DOCUMENT_ROOT'] . DS . "uploads/");

        defined('ERROR_RESOURCE') or define('ERROR_RESOURCE', APP_ROOT . DS . 'vendor/magmacore/magmacore/src/ErrorHandler/Resources/Templates');
    }

}