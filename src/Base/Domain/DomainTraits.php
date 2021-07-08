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

use Closure;
use Exception;
use MagmaCore\Utility\Yaml;
use MagmaCore\Utility\Stringify;
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
     * Unset the csrf token from the data array
     * @param array $data
     */
    public function removeCsrfToken(array $data)
    {
        if ($data) {
            unset($data['_CSRF_INDEX'], $data['_CSRF_TOKEN'], $data['settings-user']);
        }

    }

    /**
     * Returns the current template directory path
     *
     * @return string
     * @throws Exception
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
        return $parts[array_key_last($parts)];
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
     * referrers to the current index of our template file extension. Which is
     * defined in the config index in position 2
     *
     * extension example [0 => .html, 1 => .twig, 2 => .html.twig]
     * So depending on what extensions you are using
     *
     * @param int $indexPos - the index position of the file extension
     * @return string
     * @throws Exception
     */
    public function getFileExt(int $indexPos): string
    {
        return Yaml::file('template')['template']['template_ext'][$indexPos];
    }

    /**
     * Append the client directory name when dealing with non dynamic routes ie
     * routes which doesn't defined a dynamic namespace within the route.yml file
     *
     * @return string
     * @throws Exception
     */
    public function fileDirectoryFromNamespace(): string
    {
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
     * @throws Exception
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
     * @param int $extension - the file extension defaults to 2 (.html.twig)
     * @return Actions\SettingsAction|Actions\ActivateAction|Actions\BulkDeleteAction|Actions\ConfigAction|Actions\DeleteAction|Actions\EditAction|Actions\IndexAction|Actions\LoginAction|Actions\LogoutAction|Actions\NewAction|Actions\NewPasswordAction|Actions\PurgeAction|Actions\ResetPasswordAction|Actions\SessionExpiredAction|Actions\ShowAction|DomainTraits
     * @throws Exception
     */
    public function render(?string $filename = null, int $extension = 0): self
    {
        if ($filename !== null) {
            $this->fileToRender = $filename;
        } else {
            list($fullPath, $filePath) = $this->getFile($extension);
            if (!file_exists($fullPath)) {
                throw new Exception(
                    $filePath ." template file could be located within {$this->templateDir()}"
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
     * @return Actions\SettingsAction|Actions\ActivateAction|Actions\BulkDeleteAction|Actions\ConfigAction|Actions\DeleteAction|Actions\EditAction|Actions\IndexAction|Actions\LoginAction|Actions\LogoutAction|Actions\NewAction|Actions\NewPasswordAction|Actions\PurgeAction|Actions\ResetPasswordAction|Actions\SessionExpiredAction|Actions\ShowAction|DomainTraits
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
     * consistent
     *
     * @param Object $formRendering
     * @param string|null $formAction
     * @param mixed|null $data
     * @return Actions\SettingsAction|Actions\ActivateAction|Actions\BulkDeleteAction|Actions\ConfigAction|Actions\DeleteAction|Actions\EditAction|Actions\IndexAction|Actions\LoginAction|Actions\LogoutAction|Actions\NewAction|Actions\NewPasswordAction|Actions\PurgeAction|Actions\ResetPasswordAction|Actions\SessionExpiredAction|Actions\ShowAction|DomainTraits
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
                ],
                $this->getDataRelationship()
        );
        return $this;
    }

    /**
     * Return the object for any edit route from any controller which has a findOr404
     * method else will just return null and that's if we are not passing a third
     * argument to our $this->form() method above.
     *
     * @return object|null
     */
    private function findSomeData(): ?object
    {
        if (method_exists($this->controller, 'findOr404')) {
            if (!empty($this->controller->thisRouteID())) {
                return $this->controller->findOr404();
            } else {
                return $this->controller->repository;
            }
        }
        return null;
    }

    /**
     * Return the auto generated table data or use the first argument to construct
     * a customized table data array. Second arguments allow you to configure the table
     * attributes
     *
     * @param array $tableParams
     * @param object|null $column = null
     * @param object|null $repository
     * @param array $tableData
     * @return Actions\SettingsAction|Actions\ActivateAction|Actions\BulkDeleteAction|Actions\ConfigAction|Actions\DeleteAction|Actions\EditAction|Actions\IndexAction|Actions\LoginAction|Actions\LogoutAction|Actions\NewAction|Actions\NewPasswordAction|Actions\PurgeAction|Actions\ResetPasswordAction|Actions\SessionExpiredAction|Actions\ShowAction|DomainTraits
     */
    public function table(array $tableParams = [], object|null $column = null, object|null $repository = null, array $tableData = []): self
    {
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
                $this->controller->repository->getColumns($this->schema),
                $this->controller
            )
            ->setAttr($tableParams)
            ->table();

        /* Create data table context which gets pass to the twig rendering template */
        if ($this->tableData) {
            $tableContext = [
                'table' => $table,
                'pagination' => $this->controller->tableGrid->pagination(),
                'columns' => $this->controller->tableGrid->getColumns(),
                'dataColumns' => $this->controller->tableGrid->getDataColumns(),
                'total_records' => $this->controller->tableGrid->totalRecords(),
                'search_query' => $this->controller->request->handler()->query->getAlnum($this->args['filter_alias'])
            ];
        }
        $this->superContext = array_merge($this->context, (!empty($tableData)) ? $tableData : $tableContext);
        return $this;
    }

    function array_flatten($array) {
        foreach ($array as $arr) {
            return $arr;
        }
    }

    /**
     * Singular can be used to display information about single object. Method
     * which chains the singular() method would be able to access the data
     * using the variable (row) within the rendered twig template.
     *
     * @return Actions\SettingsAction|Actions\ActivateAction|Actions\BulkDeleteAction|Actions\ConfigAction|Actions\DeleteAction|Actions\EditAction|Actions\IndexAction|Actions\LoginAction|Actions\LogoutAction|Actions\NewAction|Actions\NewPasswordAction|Actions\PurgeAction|Actions\ResetPasswordAction|Actions\SessionExpiredAction|Actions\ShowAction|DomainTraits
     */
    public function singular(): self
    {
        $this->superContext = array_merge(
            $this->context,
            ['row' => $this->controller->toArray($this->controller->findOr404())],
            $this->getDataRelationship()
        );
        return $this;
    }

    /**
     * Render a notification
     *
     * @return Actions\SettingsAction|Actions\ActivateAction|Actions\BulkDeleteAction|Actions\ConfigAction|Actions\DeleteAction|Actions\EditAction|Actions\IndexAction|Actions\LoginAction|Actions\LogoutAction|Actions\NewAction|Actions\NewPasswordAction|Actions\PurgeAction|Actions\ResetPasswordAction|Actions\SessionExpiredAction|Actions\ShowAction|DomainTraits
     */
    public function notification(): self
    {
        return $this;
    }

    private function getDataRelationship(): array
    {
        return isset($this->dataRelationship) ? ['relationship' => $this->dataRelationship] : [];
    }

    /**
     * The end method which finally renders the BaseController render method and
     * pass the populated arguments based on the method chaining
     *
     * @param string|null $type
     * @return void
     */
    public function end(string|null $type = null): void
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
     * @param string $ds
     * @return string
     */
    public function domainRoute(string $ds = '/'): string
    {
        $route = '';
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
     * @return bool
     */
    public function enforceRules(array $rules = [], Object $controller): bool
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
        return false;
    }

    public function api(): void
    {
        $this->isRestFul = true;
        if (is_bool($this->domainAction) && $this->domainAction === true) {
            echo $this->controller->apiResponse->response(['success' => 'Created Successfully.'], 201);
        } else {
            echo $this->controller->apiResponse->response(['success' => 'The request was unsuccessful'], 201);
        }
    }

    /**
     * @return string
     */
    public function getSubmitValue(): string
    {
        return $this->getFileName() . '-' . strtolower($this->controller->thisRouteController());
    }

    /**
     * @param string $key
     * @param array $array
     * @return mixed
     */
    public function isSet(string $key, array $array): mixed
    {
        return array_key_exists($key, $array) ? $array[$key] : '';
    }

    /**
     * Return a closure with data relating to the current query. simple joining
     * multiple matching data tables together.
     *
     * @param Closure|null $closure
     * @return Actions\SettingsAction|Actions\ActivateAction|Actions\BulkDeleteAction|Actions\ConfigAction|Actions\DeleteAction|Actions\EditAction|Actions\IndexAction|Actions\LoginAction|Actions\LogoutAction|Actions\NewAction|Actions\NewPasswordAction|Actions\PurgeAction|Actions\ResetPasswordAction|Actions\SessionExpiredAction|Actions\ShowAction|DomainTraits
     * @throws Exception
     */
    public function mergeRelationship(Closure $closure = null): self
    {
        if ($closure) {
            if (!$closure instanceof Closure) {
                throw new Exception();
            }
            $this->dataRelationship = $closure($this->controller->repository, $this->controller->relationship);

        }
        return $this;
    }

<<<<<<< HEAD
    /**
     * Get the controller arguments from the default yaml file or is a database controller
     * settings exists use that.
     *
     * @param object $controller
     * @throws Exception
     * @return array
     */
=======
>>>>>>> 12d2722806a2965cc99949f21e809f1e481f4ca6
    public function getControllerArgs(object $controller): array
    {
        $cs = $controller->controllerRepository->getRepo()->findOneBy(['controller_name' => $controller->thisRouteController()]);
        $a = [];
        foreach ($cs as $arg) {
            $a = $arg;
        }
        if (is_array($a) && empty($a)) {
            $arg = Yaml::file('controller')[$controller->thisRouteController()];
        }
<<<<<<< HEAD
=======

>>>>>>> 12d2722806a2965cc99949f21e809f1e481f4ca6
        return [
            'records_per_page' => $this->isSet('records_per_page', $a) ?: $arg['records_per_page'],
            'query' => $this->isSet('query', $a) ?: $arg['query'],
            'filter_by' => unserialize($this->isSet('filter', $a)) ?: $arg['filter_by'],
            'filter_alias' => $this->isSet('alias', $a) ?: $arg['filter_alias'],
            'sort_columns' => unserialize($this->isSet('sortable', $a)) ?: $arg['sort_columns'],
<<<<<<< HEAD
            'additional_conditions' => $arg['additional_conditions'],
            'selectors' => $arg['selectors'],
=======
            'additional_conditions' => [] ?: $arg['additional_conditions'],
            'selectors' => [] ?: $arg['selectors'],
>>>>>>> 12d2722806a2965cc99949f21e809f1e481f4ca6
        ];

    }
}
