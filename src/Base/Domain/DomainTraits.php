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

namespace MagmaCore\Base\Domain;

use MagmaCore\Utility\Yaml;
use MagmaCore\Base\Exception\BaseInvalidArgumentException;

trait DomainTraits
{

    /**
     * Retunns the current template directory path
     *
     * @return string
     */
    private function templateDir(): string
    {
        return TEMPLATE_PATH . DS . 'Templates';
    }

    /**
     * Explode the name of the current method by double colon :: and remove the 
     * Action suffix from the string. As the method name is the last element within
     * the array the use of array_key_last ensure we are getting the last element.
     *
     * @return string
     */
    private function getFilename(): string
    {
        $parts = explode('::', str_replace('Action', '', $this->method));
        $end = $parts[array_key_last($parts)];
        return $end;
    }

    /**
     * Returns the current object namespace as lowercase
     *
     * @return string
     */
    private function getNamespace(): string
    {
        return strtolower($this->controller->thisRouteNamespace());
    }

    /**
     * Returns the current controller object
     *
     * @return string
     */
    private function controllerLowercase(): string
    {
        return strtolower($this->controller->thisRouteController());
    }

    /**
     * Get the template file extension from the twig config file. note [2]. this
     * referers to the current index of our template file extension. Which is
     * defined in the config index in position 2
     * 
     * extension example [0 => .html, 1 => .twig, 2 => .html.twig]
     * So depending on what extensions you are using 
     *
     * @param int $indexPos - the index position of the file extension
     * @return string
     */
    public function getFileExt(int $indexPos): string
    {

        return Yaml::file('twig')['template_ext'][$indexPos];
    }

    /**
     * Returns both variant of the file. ie. file within the template directory as a string
     * and just a path to the file without the directory string concat.
     * 
     * @param int $ext - the file extension index position
     * @return array
     */
    private function getFile(int $ext): array
    {
        $fullPath = "{$this->templateDir()}/{$this->getNamespace()}/{$this->controllerLowercase()}/{$this->getFileName()}{$this->getFileExt($ext)}";
        $filePath = "/{$this->getNamespace()}/{$this->controllerLowercase()}/{$this->getFileName()}{$this->getFileExt($ext)}";
        return [
            $fullPath,
            $filePath
        ];
    }

    /**
     * Purpose of this method is to attempt to build the twig template file based on the 
     * name of the method. For consistency all methods should follow the framework practice
     * all method should have the Action suffix. Although the method wouldn't be considered a 
     * action method without the Action suffix
     * 
     * @param string|null $filename
     * @param string $methodName
     * @param int $extension - the file extension defaults to 2 (.html.twig)
     * @return self
     */
    public function render(string|null $filename = null, int $extension = 2): self
    {
        list($fullPath, $filePath) = $this->getFile($extension);
        if (!file_exists($fullPath)) {
            throw new \Exception(
                "Your template <code>{$filePath}</code> could be located within <code>{$this->templateDir()}</code>"
            );
        }
        $this->fileToRender = ($filename !== null) ? $filename : $filePath;
        return $this;
    }

    /**
     * Set the context. We are binding the current object using the 'this' key property
     * this allows us to access the current object from any .html.twig template. or any
     * template which uses these chainable methods
     *
     * @param array $context
     * @return self
     */
    public function with(array $context = []): self
    {
        $this->context = array_merge(['this' => $this->controller], $context);
        return $this;
    }

    /**
     * If the render action contains a form we can chain the form to the with method
     * and will merge all the array context together to create the superContext for
     * our twig template. All forms will array key of 'form' as defined to keep things
     * consistant
     *
     * @param Object $formRendering
     * @param string|null $formAction
     * @return self
     */
    public function form(Object $formRendering, string|null $formAction = null): self
    {
        if (!is_array($this->context)) {
            throw new BaseInvalidArgumentException(
                'Invalid context pass to <code>with()</code> method.'
            );
        }
        if (count($this->context) > 0) {
            $this->superContext = array_merge(
                $this->context,
                [
                    'form' => $formRendering->createForm(
                        $this->domainRoute(),
                        ($this->controller->thisRouteAction() === 'edit') ? $this->controller->findOr404() : NULL
                    )
                ]
            );
        } else {
            $this->superContext = $this->context;
        }
        return $this;
    }

    /**
     * Return the auto generated table data or use the first argument to construct
     * a customized table data array. Second arguments allow you to configure the table
     * attributes
     *
     * @param array $tableParams
     * @param array $tableData
     * @return self
     */
    public function table(array $tableParams = [], array $tableData = []): self
    {
        if (count($this->context) < 0) {
            $this->superContext = $this->context;
        }
        $table = $this->tableData->setAttr($tableParams)->table();
        if ($this->tableData) {
            $tableContext = [
                'results' => $this->tableRepository,
                'table' => $table,
                'pagination' => $this->controller->tableGrid->pagination(),
                'columns' => $this->controller->tableGrid->getColumns(),
                'total_records' => $this->controller->tableGrid->totalRecords(),
                'search_query' => $this->controller->request->handler()->query->getAlnum($this->args['filter_alias'])
            ];
        }

        $this->superContext = array_merge($this->context, (!empty($tableData)) ? $tableData : $tableContext);
        return $this;
    }

    /**
     * The end method which finally renders the BaseController render method and 
     * pass the populated arguments based on the method chaining
     *
     * @return void
     */
    public function end(): void
    {
        $this->controller->render($this->fileToRender, $this->superContext);
    }

    public function hasRouteWithID() : bool
    {
        if (!empty($this->controller->thisRouteID())) {
            return true;
        }
        return false;
    }

    private function isRouteIDEqual() : bool
    {
        if ($this->controller->thisRouteID() === $this->controller->findOr404()->id) {
            return true;
        }
        return false;
    }

    private function idRoute(int|null $ID = null, ?string $ds = null) : string
    {
        $out = '';
        $out .= $ds;
        $out .= $this->getNamespace() . $ds;
        $out .= $this->controllerLowercase() . $ds;
        $out .= ($ID !== null) ? $ID . $ds : '';
        $out .= $this->getFileName();

        return $out;
    }

    /**
     * Undocumented function
     *
     * @param Object $controller
     * @return string
     */
    public function domainRoute(string $ds = '/') : string
    {
        if ($this->controller->thisRouteAction() === $this->getFileName()) {
            if ($this->hasRouteWithID()) {
                if ($this->isRouteIDEqual()) {
                    $route = $this->idRoute($this->controller->thisRouteID(), $ds);
                }
            } else {
                $route = $this->idRoute(null, $ds);
            }

            return $route;
        }
    }

}
