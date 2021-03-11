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

use MagmaCore\Router\RouterFactory;
use MagmaCore\Session\SessionFacade;
use MagmaCore\Container\ContainerFactory;
use MagmaCore\Session\Exception\SessionException;
use MagmaCore\Session\GlobalManager\GlobalManager;

abstract class AbstractBaseBootLoader
{

    protected BaseApplication $application;

    public function __construct(BaseApplication $application)
    {
        $this->application = $application;
    }

    protected function phpVersion()
    {
        if (version_compare($phpVersion = PHP_VERSION, $coreVersion = $this->application->getConfig()['app']['app_version'], '<')) {
            die(sprintf('You are runninig PHP %s, but the core framework requires at least PHP %s', $phpVersion, $coreVersion));
        }
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
     * Defined common constants which are commonly used throughout the framework
     *
     * @return void
     */
    protected function loadConstants(): void
    {
        defined('DS') or define('DS', DIRECTORY_SEPARATOR);
        defined('APP_ROOT') or define('APP_ROOT', $this->application->getPath());
        defined('TEMPLATE_PATH') or define('TEMPLATE_PATH', APP_ROOT . DS . 'App');
        defined('STORAGE_PATH') or define('STORAGE_PATH', APP_ROOT . DS . 'Storage');
        defined('LOG_PATH') or define('LOG_PATH', STORAGE_PATH . DS . 'logs');
        defined('ERROR_RESOURCE') or define('ERROR_RESOURCE', APP_ROOT . DS . 'vendor/magmacore/magmacore/src/ErrorHandler/Resources/Templates');
        defined('ROOT_URI') or define('ROOT_URI', '');
        defined('RESOURCES') or define('RESOURCES', ROOT_URI);
        defined('UPLOAD_PATH') or define("UPLOAD_PATH", $_SERVER['DOCUMENT_ROOT'] . DS . "uploads/");
        
    }

    protected function loadEnvironment()
    {
        $settings = $this->application->getConfig()['settings'];
        ini_set('default_charset', $settings['default_charset']);
    }

    protected function loadProviders()
    {
        $providers = $this->application->getContainerProviders();
    }

    public static function diGet(string $class)
    {
        $container = (new ContainerFactory())->create();
        if ($container) {
            return $container->get($class);
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
    protected function getDefaultSessionDriver()
    {
        $session = $this->application->getSessions();
        if (count($session) > 0) {
            if (array_key_exists('drivers', $session)) {
                $sess  = $session['drivers']['default_storage'];
                if ($sess['default'] === true) {
                    return $sess['class'];
                }
            }
        }
    }

    /**
     * Builds the application session component and returns the configured object. Based
     * on the session configuration array.
     *
     * @return object - returns the session object
     */
    protected function loadSession(): Object
    {
        $sessionIdentifier = $this->application->getSessions()['session_name'];
        $session = (new SessionFacade(
            $this->application->getSessions(),
            $sessionIdentifier,
            $this->application->getSessionDriver()
        ))->setSession();
        if (!$session) {
            throw new SessionException('Please enable session within the session.yml configuration in order to use this Sessions.');
        } else {
            GlobalManager::set('session_global', $session);
            return $session;
        }
    }

    /**
     * 
     */
    protected function loadRoutes()
    {
        $factory = new RouterFactory($this->application->getRouteHandler());
        $factory->create($this->application->getRouter());
        if (count($this->application->getRoutes()) > 0) {
            return $factory->buildRoutes($this->application->getRoutes());
        }
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    protected function loadErrorHandlers()
    {
        error_reporting($this->application->getErrorHandlerLevel());
        set_error_handler($this->application->getErrorHandling()['error']);
        set_exception_handler($this->application->getErrorHandling()['exception']);
    }
}
