<?php
declare(strict_types=1);

namespace MagmaCore\System\App\Controller;

use MagmaCore\Administrator\Controller\AdminController;
use MagmaCore\System\App\Commander\SystemCommander;
use MagmaCore\System\App\Model\EventModel;
use MagmaCore\Base\BaseController;
use MagmaCore\Base\Domain\Actions\SystemAction;
use MagmaCore\System\App\DataColumns\SystemColumn;
use MagmaCore\System\App\Schema\EventSchema;
use MagmaCore\System\Event\SystemActionEvent;
use MagmaCore\Utility\Yaml;

class SystemController extends AdminController
{

    /**
     * Extends the base constructor method. Which gives us access to all the base
     * methods implemented within the base controller class.
     * Class dependency can be loaded within the constructor by calling the
     * container method and passing in an associative array of dependency to use within
     * the class
     *
     * @param array $routeParams
     * @return void
     * @throws BaseInvalidArgumentException
     */
    public function __construct(array $routeParams)
    {
        parent::__construct($routeParams);
        $this->addDefinitions(
            [
                'sysytemEventAction' => SystemAction::class,
                'repository' => EventModel::class,
                'column' => SystemColumn::class,
                'schema' => EventSchema::class,
                'commander' => SystemCommander::class,
            ]
        );
    }

    /**
     * Middleware which are executed before any action methods is called
     * middlewares are return within an array as either key/value pair. Note
     * array keys should represent the name of the actual class its loading ie
     * upper camel case for array keys. alternatively array can be defined as
     * an index array omitting the key entirely
     *
     * @return array
     */
    protected function callBeforeMiddlewares(): array
    {
        return [];
    }

    /**
     * Returns a 404 error page if the data is not present within the database
     * else return the requested object
     *
     * @return mixed
     */
    public function findOr404(): mixed
    {
        if (isset($this)) {
            return $this->repository->getRepo()
                ->findAndReturn($this->thisRouteID())
                ->or404();
        }
    }


    /**
     * @throws \Exception
     */
    protected function indexAction()
    {
        $args = Yaml::file('controller')[$this->thisRouteController()];
        $args['records_per_page'] = 10;

        $dataRepository = $this->repository
            ->getRepo()
            ->findWithSearchAndPaging($this->request->handler(), $args);

        $tableData = $this->tableGrid->create(
            $this->column,
            $dataRepository,
            $args,
            $this->repository->getColumns(EventSchema::class),
            $this,
            $this->request
        )->setAttr([])->table();

        $this->render(
            '/admin/system/index.html',
            [
                'table' => $tableData,
                'pagination' => $this->tableGrid->pagination(),
                'columns' => $this->tableGrid->getColumns(),
                'dataColumns' => $this->tableGrid->getDataColumns(),
                'total_records' => $this->tableGrid->totalRecords(),
                'search_query' => $this->request->handler()->query->getAlnum($args['filter_alias'])
            ]
        );
    }

    protected function showAction()
    {
        $this->showAction
            ->execute($this, NULL, NULL, NULL, __METHOD__)
            ->render()
            ->with()
            ->singular()
            ->end();
    }

}