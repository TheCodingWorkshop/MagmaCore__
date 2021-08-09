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

namespace MagmaCore\Ash;

use Exception;
use MagmaCore\Utility\Yaml;
use MagmaCore\Auth\Authorized;
use MagmaCore\Ash\Error\LoaderError;
use MagmaCore\Ash\Exception\FileNotFoundException;
use Throwable;

class TemplateEnvironment
{

    /** @var int - cache directory permission */
    protected const CACHE_DIR_PERMISSION = 0744;
    private object $extension;

    /**
     * Main class constructor
     * 
     * @param array $options - yml template options
     * @param string $path - name of the template directory
     * @param string|null $rootPath - the path to the template directory
     */
    public function __construct(protected array $options = [], protected ?string $path = null, protected ?string $rootPath = null) {
        $this->rootPath = (null === $rootPath ? getcwd() : $rootPath) . DIRECTORY_SEPARATOR;
        if ($rootPath !== null && false !== ($realPath = realpath($rootPath))) {
            $this->rootPath = $realPath . DIRECTORY_SEPARATOR;
        }
        /** pipe the $options argument to the class property */
        if ($options)
            $this->options = $options;
        if ($path) {
            $this->path = $path;
        }

    }

    /**
     * Return an array of the pass template options
     *
     * @return array
     */
    public function getOptions(): array
    {
        if (count($this->options) < 0) {
            throw new LoaderError('Invalid or no template options present.');
        }
        return $this->options;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        $checkPath = $this->rootPath . $this->path;
        if (!is_dir($checkPath)) {
            throw new LoaderError(sprintf('The "%s" directory does not exist ("%s").', $this->path, $checkPath));
        }
        return trim($checkPath, '/\\');
    }

    /**
     * Undocumented function
     *
     * @return array|string|string[]
     */
    public function getTemplate(): array|string
    {
        return str_replace('\\', '/', $this->getPath());
    }

    /**
     * Get the cache directory set within the yml configurations file
     *
     * @return array
     */
    public function getCacheOptions(): array
    {
        return $this->getOptions()['template']['template_cache'];
    }

    /**
     * Get the cache directory set within the yml configurations file
     *
     * @return string
     */
    public function getCacheDir(): string
    {
        $cacheDir = $this->getTemplate() . '/' . $this->getCacheOptions()['path'];
        if (!file_exists($cacheDir)) {
            mkdir($cacheDir, self::CACHE_DIR_PERMISSION);
        }

        return $cacheDir;
    }

    /**
     * Create and return the cache file name
     *
     * @param string $file
     * @return string
     */
    public function getCacheKey(string $file): string
    {
        return $this->getCacheDir() . str_replace(array('/', ':', '.html'), array('_', '', ''), $file . '.php');
    }

    /**
     * Get the cache status from the yml configuration
     *
     * @return mixed
     */
    public function getCacheStatus(): mixed
    {
        return $this->getCacheOptions()['enable'];
    }

    /**
     * Render a view template and provide a http response
     *
     * @param string $template
     * @param array $context
     * @return void
     */
    public function view(string $template, array $context = [])
    {
        return (new Template($this))->view($template, $context);
    }

    /**
     * Render a view template and provide a http response
     *
     * @param string $template
     * @param array $context
     * @return void
     */
    public function errorView(string $template, array $context = [])
    {
        return (new Template($this))->errorView($template, $context);
    }

    /**
     * The template class can be extended with added functionality
     *
     * @param object $extension
     * @return void
     */
    public function addExtension(object $extension): void
    {
        if ($extension)
            $this->extension = $extension;
    }

    /**
     * Default template context which can be access from any html templates
     *
     * @return array
     * @throws Exception
     * @throws Throwable
     */
    public function defaultContext(): array
    {
        if (!class_exists(TemplateExtension::class)) {
            throw new FileNotFoundException('The core template extension class is missing.');
        }
        return array_merge(
            ['current_user' => ($user = Authorized::grantedUser()) ? $user : NULL],
            ['func' => new TemplateExtension($this)],
            ['app' => Yaml::file('app')],
            ['menu' => Yaml::file('menu')]
        );
    }
}
