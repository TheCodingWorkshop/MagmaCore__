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

use MagmaCore\Base\Exception\BaseLengthException;
use MagmaCore\Cache\CacheConfig;
use MagmaCore\Base\BaseConstants;
use MagmaCore\Session\SessionConfig;
use MagmaCore\Base\AbstractBaseBootLoader;
use MagmaCore\Base\Exception\BaseInvalidArgumentException;

class BaseApplication extends AbstractBaseBootLoader
{

    protected string|null $appPath;
    protected array $appConfig = [];
    protected array $session;
    protected bool $isSessionGlobal = false;
    protected ?string $globalSessionKey = null;
    protected array $cookie = [];
    protected array $cache = [];
    protected bool $isCacheGlobal = false;
    protected ?string $globalCacheKey = null;
    protected ?string $globalThemeBuilderKey = null;
    protected bool $isGlobalThemeBuilder = false;
    protected array $routes = [];
    protected array $containerProviders = [];
    protected string|null $routeHandler;
    protected string|null $newRouter;
    protected string|null $theme;
    protected ?string $newCacheDriver;
    protected string $handler;
    protected string $logFile;
    protected array $logOptions = [];
    protected string $logMinLevel;
    protected array $themeBuilderOptions = [];
    protected array$errorHandling = [];
    protected ?int $errorLevel = null;

    /** @return void */
    public function __construct()
    {
        /* Pass the current object to the parent class */
        parent::__construct($this);
    }

    /**
     * Set the project root path directory
     *
     * @param string $rootPath
     * @return void
     */
    public function setPath(string $rootPath): self
    {
        $this->appPath = $rootPath;
        return $this;
    }

    /**
     * Return the document root path
     *
     * @return string
     */
    public function getPath(): string
    {
        return $this->appPath;
    }

    /**
     * Set the default theming qualified namespace
     *
     * @param string $theme
     * @return void
     */
    public function setTheme(string $theme): self
    {
        $this->theme = $theme;
        return $this;
    }

    /**
     * Returns the theme qualified namespace
     *
     * @return string
     */
    public function getTheme(): string
    {
        return isset($this->theme) ? $this->theme : '';
    }

    /**
     * Set the application main configuration from the project app.yml file
     *
     * @param array $ymlApp
     * @return self
     */
    public function setConfig(array $ymlApp): self
    {
        $this->appConfig = $ymlApp;
        return $this;
    }

    /**
     * Return the application configuration as an array of data
     *
     * @return array
     */
    public function getConfig(): array
    {
        return $this->appConfig;
    }

    /**
     * Set the application session configuration from the session.yml file else
     * load the core session configration class
     *
     * @param array $ymlSession
     * @param ?string $newSessionDriver
     * @param bool $isGlobal defaults to false
     * @return self
     * @throws BaseInvalidArgumentException
     */
    public function setSession(array $ymlSession = [], string|null $newSessionDriver = null, bool $isGlobal = false, ?string $globalKey = null): self
    {
        $this->session = (!empty($ymlSession) ? $ymlSession : (new SessionConfig())->baseConfiguration());
        $this->newSessionDriver = ($newSessionDriver !== null) ? $newSessionDriver : $this->getDefaultSessionDriver();
        $this->isSessionGlobal = $isGlobal;
        $this->globalSessionKey = $globalKey;
        return $this;
    }

    /**
     * If session yml is set from using the setSession from the application
     * bootstrap. Use the user defined session.yml else revert to the core
     * session configuration.
     *
     * @return array
     * @throws BaseInvalidArgumentException
     */
    public function getSessions(): array
    {
        if (empty($this->session)) {
            throw new BaseInvalidArgumentException('You have no session configuration. This is required.');
        }
        return $this->session;
    }

    /**
     * Returns the default session driver from either the core or user defined
     * session configuration. Throws an exception if neither configuration
     * was found
     *
     * @return string
     * @throws BaseInvalidArgumentException
     */
    public function getSessionDriver(): string
    {
        if (empty($this->session)) {
            throw new BaseInvalidArgumentException('You have no session configuration. This is required.');
        }
        return $this->newSessionDriver;
    }

    /**
     * Turn on global session from public/index.php bootstrap file to make the session
     * object available globally throughout the application using the GlobalManager object
     * @return bool
     */
    public function isSessionGlobal(): bool
    {
        return isset($this->isSessionGlobal) && $this->isSessionGlobal === true ? true : false;
    }

    /**
     * @return string
     * @throws BaseLengthException
     */
    public function getGlobalSessionKey(): string
    {
        if ($this->globalSessionKey !==null && strlen($this->globalSessionKey) < 3) {
            throw new BaseLengthException($this->globalSessionKey . ' is invalid this needs to be more than 3 characters long');
        }
        return ($this->globalSessionKey !==null) ? $this->globalSessionKey : 'session_global';
    }

    /**
     * Set the application cookie configuration from the session.yml file.
     *
     * @param array $ymlCookie
     * @return self
     */
    public function setCookie(array $ymlCookie): self
    {
        $this->cookie = $ymlCookie;
        return $this;
    }

    /**
     * Returns the cookie configuration array
     *
     * @return array
     */
    public function getCookie(): array
    {
        return $this->cookie;
    }

    /**
     * Set the application cache configuration from the session.yml file
     * 
     * @param array $ymlCache
     * @param string|null $newCacheDriver
     * @param bool $isGloabl
     * @param string|null $globalKey
     * @return $this
     */
    public function setCache(array $ymlCache = [], string $newCacheDriver = null, bool $isGloabl = false, ?string $globalKey = null): self
    {
        $this->cache = (!empty($ymlCache) ? $ymlCache : (new CacheConfig())->baseConfiguration());
        $this->newCacheDriver = ($newCacheDriver !== null) ? $newCacheDriver : $this->getDefaultCacheDriver();
        $this->isCacheGlobal = $isGloabl;
        $this->globalCacheKey = $globalKey;
        return $this;
    }

    /**
     * Returns the cache configuration array
     *
     * @return string
     */
    public function getCacheIdentifier(): string
    {
        return $this->cache['cache_name'] ?? '';
    }


    /**
     * Returns the cache configuration array
     *
     * @return array
     */
    public function getCache(): array
    {
        return $this->cache;
    }

    /**
     * Returns the default cache driver from either the core or user defined
     * cache configuration. Throws an exception if neither configuration
     * was found
     *
     * @return string
     * @throws BaseInvalidArgumentException
     */
    public function getCacheDriver(): string
    {
        if (empty($this->cache)) {
            throw new BaseInvalidArgumentException('You have no cache configuration. This is required.');
        }
        return $this->newCacheDriver;
    }

    /**
     * Turn on global caching from public/index.php bootstrap file to make the cache
     * object available globally throughout the application using the GlobalManager object
     * @return bool
     */
    public function isCacheGlobal(): bool
    {
        return isset($this->isCacheGlobal) && $this->isCacheGlobal === true ? true : false;
    }

    /**
     * @return string
     * @throws BaseLengthException
     */
    public function getGlobalCacheKey(): string
    {
        if ($this->globalCacheKey !==null && strlen($this->globalCacheKey) < 3) {
            throw new BaseLengthException($this->globalCacheKey . ' is invalid this needs to be more than 3 characters long');
        }
        return ($this->globalCacheKey !==null) ? $this->globalCacheKey : 'cache_global';
    }

    /**
     * Set the application container providers configuration from the session.yml file.
     *
     * @param array $ymlProviders
     * @return self
     */
    public function setContainerProviders(array $ymlProviders): self
    {
        $this->containerProviders = $ymlProviders;
        return $this;
    }

    /**
     * Returns the container providers configuration array
     *
     * @return array
     */
    public function getContainerProviders(): array
    {
        return $this->containerProviders;
    }

    /**
     * @param string $handler
     * @return $this
     */
    public function setLogger(string $file, string $handler, string $minLevel, array $options): self
    {
        $this->handler = $handler;
        $this->logFile = $file;
        $this->logOptions = $options;
        $this->logMinLevel = $minLevel;
        return $this;
    }

    /**
     * @return string
     */
    public function getLogger(): string
    {
        return $this->handler;
    }

    /**
     * @return string
     */
    public function getLoggerFile(): string
    {
        return $this->logFile;
    }

    /**
     * @return array
     */
    public function getLoggerOptions(): array
    {
        return $this->logOptions;
    }

    /**
     * @return string
     */
    public function getLogMinLevel(): string
    {
        return $this->logMinLevel;
    }

    /**
     * Set the application routes configuration from the session.yml file.
     *
     * @param array $ymlRoutes
     * @param string|null $routeHandler - Can be Request object
     * @param string|null $newRouter - accepts the fully qualified namespace of new router class
     * @return self
     */
    public function setRoutes(array $ymlRoutes, string|null $routeHandler = null, string|null $newRouter = null): self
    {
        $this->routes = $ymlRoutes;
        $this->routeHandler = ($routeHandler !== null) ? $routeHandler : $this->defaultRouteHandler();
        $this->newRouter = ($newRouter !== null) ? $newRouter : '';
        return $this;
    }

    /**
     * Returns the application route configuration array
     *
     * @return array
     */
    public function getRoutes(): array
    {
        if (count($this->routes) < 0) {
            throw new BaseInvalidArgumentException('No routes detected within your routes.yml file');
        }
        return $this->routes;
    }

    /**
     * Returns the application route handler mechanism
     *
     * @return string
     */
    public function getRouteHandler(): string
    {
        if ($this->routeHandler === null) {
            throw new BaseInvalidArgumentException('Please set your route handler.');
        }
        return $this->routeHandler;
    }

    /**
     * Get the new router object fully qualified namespace
     *
     * @return string
     */
    public function getRouter(): string
    {
        if ($this->newRouter === null) {
            throw new BaseInvalidArgumentException('No new router object was defined.');
        }
        return $this->newRouter;
    }

    /**
     * Define the framework error handling
     *
     * @param string $errorClass
     * @param int $level
     * @return self
     */
    public function setErrorHandler(array $errorHandling, ?int $level = null): self
    {
        $this->errorHandling = $errorHandling;
        $this->errorLevel = $level;
        return $this;
    }

    /**
     * Load the error handling configurations from the relevant yaml file
     *
     * @return array or throw exception
     * @throws BaseInvalidArgumentException
     */
    public function getErrorHandling(): ?array
    {
        if (count($this->errorHandling) > 0) {
            return $this->errorHandling;
        }
        throw new BaseInvalidArgumentException('Error loading the error handling configurations. Please check your method argument.');
    }

    public function getErrorHandlerLevel(): int
    {
        if ($this->errorLevel !== null) {
            return $this->errorLevel;
        }
        throw new BaseInvalidArgumentException('Error figuring out the error_level defined within the  error_handler. Ensure this is defined within the second argument within the setErrorHandler method.');
    }

    /**
     * Pass the thene builder option
     *
     * @param array $themeBuilderOptions
     * @param bool $isGlobal
     * @param string|null $globalKey
     * @return $this
     */
    // public function setThemeBuilder(array $themeBuilderOptions = [], bool $isGlobal = false, ?string $globalKey = null): self
    // {
    //     if (count($themeBuilderOptions) < 0) {
    //         throw new BaseInvalidArgumentException(sprintf('%s options have return empty.', implode(' '. $themeBuilderOptions)));
    //     }
    //     $this->isGlobalThemeBuilder = $isGlobal;
    //     $this->globalThemeBuilderKey = $globalKey;
    //     $this->themeBuilderOptions = $themeBuilderOptions;
    //     return $this;
    // }

    /**
     * Returns the theme builder options array from the yaml file
     *
     * @return array
     */
    // public function getThemeBuilderOptions(): array
    // {
    //     return $this->themeBuilderOptions;
    // }

    /**
     * Return the default theme builder library
     *
     * @return string
     */
    // public function getDefaultThemeBuilder(): ?string
    // {
    //     if (count($this->themeBuilderOptions) > 0) {
    //         foreach ($this->themeBuilderOptions['cssDriver'] as $key => $value) {
    //             if (array_key_exists('default', $value)) {
    //                 if ($value['default'] === true) {
    //                     return $value['class'];
    //                 }
    //             }
    //         }
    //     }
    //     return null;
    // }

    /**
     * Turn on global caching from public/index.php bootstrap file to make the cache
     * object available globally throughout the application using the GlobalManager object
     * @return bool
     */
    // public function isThemeBuilderGlobal(): bool
    // {
    //     return isset($this->isGlobalThemeBuilder) && $this->isGlobalThemeBuilder === true;
    // }

    /**
     * @return string
     * @throws BaseLengthException
     */
    // public function getGlobalThemeBuilderKey(): string
    // {
    //     if ($this->globalThemeBuilderKey !==null && strlen($this->globalThemeBuilderKey) < 3) {
    //         throw new BaseLengthException($this->globalThemeBuilderKey . ' is invalid this needs to be more than 3 characters long');
    //     }
    //     return ($this->globalThemeBuilderKey !==null) ? $this->globalThemeBuilderKey : 'themeBuilder_global';
    // }


    public function run(): void
    {
        BaseConstants::load($this->app());
        $this->phpVersion();
        $this->loadErrorHandlers();
        $this->loadSession();
        $this->loadCache();
        $this->loadLogger();
        $this->loadEnvironment();
        //$this->loadThemeBuilder();
        $this->loadRoutes();
    }
}