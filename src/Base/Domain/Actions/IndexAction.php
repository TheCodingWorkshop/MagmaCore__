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

namespace MagmaCore\Base\Domain\Actions;

use MagmaCore\Base\Domain\DomainActionLogicInterface;
use MagmaCore\Cache\Storage\NativeCacheStorage;
use MagmaCore\Base\Domain\DomainTraits;
use MagmaCore\Cache\CacheFacade;
use MagmaCore\Utility\Yaml;

/**
 * Class which handles the domain logic when adding a new item to the database
 * items are sanitize and validated before persisting to database. The class will 
 * also dispatched any validation error before persistence. The logic also implements
 * event dispatching which provide usable data for event listeners to perform other
 * necessary tasks and message flashing
 */
class IndexAction implements DomainActionLogicInterface
{

    use DomainTraits;

    private object $controller;
    private string $method;
    private ?string $schema;

    /** @return void - not currently being used */
    private $cache;

    /** @return void - not currently being used */
    public function __construct(CacheFacade $cache)
    {
        $this->cache = $cache->create('data_repository');
    }

    /**
     * execute logic for adding new items to the database()
     *
     * @param Object $controller - The controller object implementing this object
     * @param string|null $entityObject
     * @param string|null $eventDispatcher - the eventDispatcher for the current object
     * @param string|null $objectSchema
     * @param string $method - the name of the method within the current controller object
     * @param array $rules
     * @param array $additionalContext - additional data which can be passed to the event dispatcher
     * @return IndexAction
     */
    public function execute(
        object $controller,
        ?string $entityObject,
        ?string $eventDispatcher,
        ?string $objectSchema,
        string $method,
        array $rules = [],
        array $additionalContext = []
    ): self {

        $this->controller = $controller;
        $this->method = $method;
        $this->schema = $objectSchema;

        $controller->getSession()->set('redirect_parameters', $_SERVER['QUERY_STRING']);
        $this->args = $this->getControllerArgs($controller);
        $controllerName = $controller->thisRouteController();
        if ($controller->cache()) {
            $this->tableRepository = $controller->cache()->set($controllerName .'_index_controller', $controller->repository->getRepo()->findWithSearchAndPaging($controller->request->handler(), $this->args));
            if ($controller->cache()->get($controllerName .'_index_controller') !==null) {
                $this->tableRepository = $controller->cache()->get($controllerName .'_index_controller');
            } else {
                $this->tableRepository = $controller->repository->getRepo()->findWithSearchAndPaging($controller->request->handler(), $this->args);
            }
        }
        $this->tableData = $controller->tableGrid;

        if ($this->tableData)
            return $this;
    }

}
