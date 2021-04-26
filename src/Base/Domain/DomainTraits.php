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

use App\Schema\UserSchema;
use MagmaCore\Utility\Yaml;
use MagmaCore\Utility\Stringify;
use MagmaCore\Base\Domain\DomainLogicRules;
use MagmaCore\Base\Exception\BaseOutOfBoundsException;
use MagmaCore\Base\Exception\BaseBadMethodCallException;
use MagmaCore\Base\Exception\BaseInvalidArgumentException;

trait DomainTraits
{

    protected array $allowedRules = [
        'password_required',
        'password_equal'
    ];


    /**
     * Retunns the current template directory path
     *
     * @return string
     */
    private function templateDir(): string
    {
        return TEMPLATE_PATH . DS . Yaml::file('routes')['template_dir'];
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
     * Append the client directory name when dealing with non dynamic routes ie
     * routes which doesn't defined a dynamic namespace within the route.yml file
     *
     * @return void
     */
    public function fileDirectoryFromNamespace()
    {
        $append = '';
        if (empty($this->getNamespace()) || $this->getNamespace() == '') {
            return Yaml::file('routes')['client_dir'] . '/';
        }
        return $this->getNamespace();
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
        $fullPath = "{$this->templateDir()}/{$this->fileDirectoryFromNamespace()}/{$this->controllerLowercase()}/{$this->getFileName()}{$this->getFileExt($ext)}";
        $filePath = "{$this->fileDirectoryFromNamespace()}/{$this->controllerLowercase()}/{$this->getFileName()}{$this->getFileExt($ext)}";
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
        if ($filename !== null) {
            $this->fileToRender = $filename;
        } else {
            list($fullPath, $filePath) = $this->getFile($extension);
            if (!file_exists($fullPath)) {
                throw new \Exception(
                    "{$filePath} template file could be located within {$this->templateDir()}"
                );
            }
            $this->fileToRender = $filePath;
        }
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
    public function form(Object $formRendering, string|null $formAction = null, mixed $data = null): self
    {
        $this->superContext = array_merge(
            $this->context,
            [
                'form' => $formRendering->createForm(
                    ($formAction !== null) ? $formAction : $this->domainRoute(),
                    ($data !== null) ? $data : $this->findSomeData()
                )
            ]
        );
        return $this;
    }

    /**
     * Return the object for any edit route from any controller which has a findOr404
     * method else will just return null and thats if we are not passing a third
     * argument to our $this->form() method above.
     *
     * @return object|null
     */
    private function findSomeData()
    {
        if (method_exists($this->controller, 'findOr404')) {
            if (!empty($this->controller->thisRouteID())) {
                return $this->controller->findOr404();
            } else {
                return NULL;
            }
        }
    }

    /**
     * Return the auto generated table data or use the first argument to construct
     * a customized table data array. Second arguments allow you to configure the table
     * attributes
     *
     * @param array $tableParams
     * @param object|null $column = null
     * @param array $tableData
     * @return self
     */
    public function table(
        array $tableParams = [],
        Object|null $column = null,
        Object|null $repository = null,
        array $tableData = []
    ): self {

        /* Create the table object and pass the dataColumn and repository object */
        $table = $this->tableData
            ->create(
                ($column !== null) ? $column : $this->controller->column,
                ($repository !== null) ? $repository : $this->tableRepository,
                $this->args,
                /** 
                 * getColumns() is a method located within the BaseModel class 
                 * and it simple returns an array of columns for the model db table
                 * its takes 1 argument which is schema that built the model. See
                 * the relevant schema located in the App/Schema directory
                 * */
                $this->controller->repository->getColumns($this->schema)
            )
            ->setAttr($tableParams)
            ->table();

        /* Create data table context which gets pass to the twig rendering template */
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
     * Singular can be used to display information about single object. Method 
     * which chains the singular() method would be able to access the data 
     * using the variable (row) within the rendered twig template.
     *
     * @return self
     */
    public function singular(): self
    {
        $this->superContext = array_merge(
            $this->context,
            ['row' => $this->controller->toArray($this->controller->findOr404())]
        );
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
        $context = (isset($this->superContext) && count($this->superContext) > 0) ? $this->superContext : $this->context;
        $this->controller->render($this->fileToRender, $context);
    }

    /**
     * Checks whether the queried route has a valid id token
     *
     * @return boolean
     */
    public function hasRouteWithID(): bool
    {
        if (!empty($this->controller->thisRouteID())) {
            return true;
        }
        return false;
    }

    /**
     * Checks whether the current route matches the queried object route
     *
     * @return boolean
     */
    private function isRouteIDEqual(): bool
    {
        if ($this->controller->thisRouteID() === $this->controller->findOr404()->id) {
            return true;
        }
        return false;
    }

    /**
     * Construct the action routes. returns the relevant strings based on
     * first argument being present.
     *
     * @param integer|null $id
     * @param string|null $ds
     * @return string
     */
    private function idRoute(int|null $id = null, ?string $ds = null): string
    {
        $out = '';
        $out .= (!empty($this->controller->thisRouteNamespace()) ? $ds : '');
        $out .= $this->getNamespace() . $ds;
        $out .= $this->controllerLowercase() . $ds;
        $out .= ($id !== null) ? $id . $ds : '';
        $out .= $this->getFileName();

        return $out;
    }

    /**
     * Dynamically construct the action routes
     *
     * @param Object $controller
     * @return string
     */
    public function domainRoute(string $ds = '/'): string
    {

        if ($this->controller->thisRouteAction() === $this->getFileName()) {
            if ($this->hasRouteWithID()) {
                if ($this->isRouteIDEqual()) {
                    $route = $this->idRoute($this->controller->thisRouteID(), $ds);
                }
            } else {
                $route = $this->idRoute(null, $ds);
            }
        } else {
            $route = $this->idRoute($this->controller->findOr404()->id, $ds);
        }
        return $route;
    }

    /**
     * Undocumented function
     *
     * @param array $rules
     * @param object $controller
     * @return Closure
     */
    public function enforceRules(array $rules = [], Object $controller)
    {
        if (sizeof($rules) > 0) {
            foreach ($rules as $rule) {
                if (isset($rule)) {
                    if (!is_string($rule))
                        throw new BaseInvalidArgumentException('Rules should be defined as strings');
                    if (!in_array($rule, $this->allowedRules, true))
                        throw new BaseOutOfBoundsException('Invalid "' . $rule . '" is not allowed.');
                    return array_walk(
                        $rules,
                        function ($callbackValue, $callbackKey, $controller) {
                            if ($callbackValue) {
                                $validCallback = (new Stringify())->camelCase($callbackValue);
                                if (!method_exists(new DomainLogicRules, $validCallback)) {
                                    throw new BaseBadMethodCallException(
                                        $validCallback . '() does not exists within ' . __CLASS__
                                    );
                                }
                                call_user_func_array(
                                    array(new DomainLogicRules, $validCallback),
                                    [
                                        $callbackValue,
                                        $callbackKey,
                                        $controller
                                    ]
                                );
                            }
                        },
                        $controller
                    );
                }
            }
        }
    }

    public function getSubmitValue(): string
    {
        return $this->getFileName() . '-' . strtolower($this->controller->thisRouteController());
    }


}
