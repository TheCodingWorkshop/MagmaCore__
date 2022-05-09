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

namespace MagmaCore\Ash\Traits;

use Exception;
use MagmaCore\Base\Exception\BaseInvalidArgumentException;
use MagmaCore\Utility\Yaml;
use MagmaCore\Widget\WidgetFactory;
use MagmaCore\Ash\Exception\FileNotFoundException;
use MagmaCore\Base\Traits\BaseAnchorTrait;
use MagmaCore\Base\Traits\ControllerSessionTrait;
use MagmaCore\DataObjectLayer\ClientRepository\ClientRepositoryFactory;

trait TemplateTraits
{

    use ControllerSessionTrait;

    /**
     * Merge extended assests with core assets. for a simplify output
     * @param string|null $type
     * @return array
     * @throws Exception
     * @throws BaseInvalidArgumentException
     */
    private function getExtendedAssets(?string $type = null): array
    {
        $extendedAssets = Yaml::file('extend_assets');
        if (!is_array($extendedAssets) && count($extendedAssets) > 0) {
            throw new BaseInvalidArgumentException('Please add reference to your extend_assets.yml file. This is currently empty or invalid format.');
        }

        return $extendedAssets[$type] ?? [];
    }

    /**
     * Add one or more js file. Can be used within the layout.html template to add template
     * js files. These are loaded where ever the function is called. Uses internal method
     * to resolve the location of the files and will throw an exception if file is not found.
     *
     * @param mixed|null $js
     * @param string $location
     * @return string
     * @throws FileNotFoundException
     * @throws Exception
     */
    public function addjs(mixed $js = null, string $location = 'footer'): void
    {
        $this->js = $js;
        $jsYmls = array_merge(Yaml::file('assets')['scripts'], $this->getExtendedAssets('scripts'));
        $allJs = array_merge($jsYmls, $this->js ?? []);
        if (is_array($allJs) && count($allJs) > 0) {
            foreach ($allJs as $file) {
                if (isset($file['enable']) && $file['enable'] === true) {
                    if (isset($file['location']) && $file['location'] === $location) {
                        if (isset($file['cdn']) && $file['cdn'] === true) {
                            $jsFile = $file['src'] ?? '';
                        } else {
                            $jsFile = $this->resolvePath($file['src']);
                        }
                        if ($jsFile) {
                            /* Get the specified route index position */
                            $this->getScriptsByConditions($file, $jsFile);
                        }
                    }
                }
            }
        }
    }

    /**
     * Add one or more css file. Can be used within the layout.html template to add template
     * css files. These are loaded where ever the function is called. Uses internal method
     * to resolve the location of the files and will throw an exception if file is not found.
     *
     * @param mixed $css
     * @return string
     * @throws FileNotFoundException
     */
    public function addcss(mixed $css = null): void
    {
        $this->css = $css;
        $cssYmls = array_merge(Yaml::file('assets')['stylesheets'], $this->getExtendedAssets('stylesheets'));
        $allCss = array_merge($cssYmls, $this->css ?? []);
        if (is_array($allCss) && count($allCss) > 0) {

            foreach ($allCss as $file) {
                if (isset($file['enable']) && $file['enable'] === true) {
                    if (isset($file['cdn']) && $file['cdn'] === true) {
                        $cssFile = $file['href'] ?? '';
                    } else {
                        $cssFile = $this->resolvePath($file['href']);
                    }

                    if ($cssFile) {
                        $this->printStylesheets($cssFile, $file);
                    }
                }
            }
        }
    }

    /**
     * Add local css stylesheets to individual html template
     *
     * @param mixed $styles
     * @return string
     */
    public function localCss(mixed $styles = null): string
    {
        $css = '';
        if ($styles !==null && is_array($styles) && count($styles) > 1) {
            for ($i = 0; $i < count($styles); $i++) {
                $css .= sprintf('<link rel="stylesheet" href="/public/assets/css%s">', $styles[$i]);
            }
        }
        $css .= sprintf('<link rel="stylesheet" href="/public/assets/css%s">', $styles); 
        return $css;
    }

    /**
     * Return the index position of the route. index[0] = the route namespace
     * index[1] = the route controller and index[2] = the route controller method. 
     *
     * @param integer|null $position
     * @return string
     */
    private function parseRouteUrl(?int $position = 1): string
    {
        $parts = explode('/', $_SERVER['QUERY_STRING']);
        $routeParts = isset($parts[$position]) ? $parts[$position] : '';
        return $routeParts;
    }

    /**
     * Conditionally load a asset. By default all assets which is set to true will be loaded 
     * within the client browser. setting any asset to false will completely disable that asset
     * everywhere. However we can define the [routes] property within the asset.yml file and 
     * pass an array of routes we want the asset to be loaded on. Not defining this key will 
     * load the asset everywhere.
     *
     * @param array $file
     * @param string|null $assetFile - styles ot scripts
     * @return void
     */
    private function getScriptsByConditions(array $file = [], string $assetFile = null, ?int $position = 1): void
    {
        if (is_array($file['routes']) && count($file['routes']) > 0) {
            foreach ($file['routes'] as $route) {
                if ($$this->parseRouteUrl($position) === $route) {
                    $this->printScripts($assetFile, $file);
                }
            }
        } else {
            $this->printScripts($assetFile, $file);
        }

    }

    /**
     * Echo the script tag for use within MagmaCore templating engine
     *
     * @param string|null $jsFile
     * @param array $file
     * @return void
     */
    private function printScripts(string $jsFile = null, array $file = [])
    {
        echo sprintf(
            '<script src="%s"%s%s%s></script>%s', 
            $jsFile, 
            isset($file['turbo_reload']) && $file['turbo_reload'] === true ? ' data-turbo-track="reload"' : '',
            !empty($file['integrity']) ? ' integrity="' . $file['integrity'] . '"' : '',
            !empty($file['crossorigin']) ? ' crossorigin="' . $file['crossorigin'] . '"' : '',
            PHP_EOL
        );

    }

    private function printStylesheets(string $cssFile = null, array $file = [])
    {
        $css = '<link rel="stylesheet"';
        $css .= ' href="' . $cssFile . '"';
        $css .= isset($file['reload']) && $file['reload'] === true ? ' data-turbo-frame="reload"' : '';
        $css .= '>' . "\n";
        echo $css;

    }

    /**
     * throw a file not found exception is the file being loaded doesn't exists.
     *
     * @param string $file
     * @return string
     * @throws FileNotFoundException
     */
    private function resolvePath(string $file): string
    {
        if (!file_exists(APP_ROOT . $file)) {
            throw new FileNotFoundException("{$file} was not found within the specified directory. Please ensure your file exists.");
        }
        return $file;
    }

    private function disabledClass(object $controller): string
    {
        return isset($controller->tableGrid) && $controller->tableGrid->getTotalRecords() === 0 ? ' uk-disabled' : '';
    }

    /**
     * Show the trash can if and enable and the model supports it
     *
     * @param object $controller
     * @return boolean
     */
    private function hasYamlSupport(object $controller, ?string $key = null): bool
    {
        $_key = $controller->thisRouteController() . '_settings';
        $yml = $this->getSessionData($_key, $controller);
        return ($yml[$key] === "true") ? true : false;
    }

    /**
     * Check whether a model has trash support ie. the deleted_at column within the
     * database table
     *
     * @param object $controller
     * @return boolean
     */
    public function hasTrashSupport(object $controller): bool
    {
        if (!isset($controller->repository)) {
            throw new \Exception(sprintf('Missing repository property for controller [%s]. [%s] is trying to access this property to check whether your table support the trash feature', get_class($controller), __METHOD__));
        }
        
        if (!method_exists($controller, 'schemaAsString')) {
            throw new \Exception(sprintf('Your controller class is missing [%s] method. This is now required as of version [%s]', 'schemaAsString', '1.3.9'));
        }
        $columns = $controller->repository->getColumns($controller->schemaAsString());
        if (is_array($columns)) {
            if (!in_array($controller->repository->trashSupport(), $columns)) {
                throw new \Exception('Trash feature is not supported by your model');
            }
        }

        return true;
    }

    /**
     * Render a template within the template to the client browser
     *
     * @param string $widgetName
     * @param ?string $tableSchema
     * @param ?string $tableSchemaID
     * @param mixed $widgetData - Data the template can pass back to the widget component
     * @return void
     */
    public function getWidget(string $widgetName, ?string $tableSchema = null, ?string $tableSchemaID = null, mixed $widgetData = null)
    {
        $widget = (new WidgetFactory)
            ->create(
                ClientRepositoryFactory::class, 
                [
                    $widgetName => [
                    'table' => $tableSchema, 
                    'table_id' => $tableSchemaID
                    ]
                ]
            );
        return $widget->renderWidget($widgetData);
    }

}
