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

namespace MagmaCore\DataObjectLayer\DataRepository;

use Throwable;
use MagmaCore\Utility\Sortable;
use MagmaCore\Utility\Paginator;
use MagmaCore\DataObjectLayer\EntityManager\EntityManager;
use MagmaCore\DataObjectLayer\DataRepository\DataRepositoryTrait;
use MagmaCore\DataObjectLayer\Exception\DataLayerNoValueException;
use MagmaCore\DataObjectLayer\EntityManager\EntityManagerInterface;
use MagmaCore\DataObjectLayer\DataRepository\DataRepositoryInterface;
use MagmaCore\DataObjectLayer\Exception\DataLayerInvalidArgumentException;

/**
 * Methods
 * 0. getSchemaID()
 * 1. find(int $id)
 * 2. findAll()
 * 3. findBy(array $selectors = [], array $conditions = [], array $parameters = [], array $optional = [])
 * 4. findOneBy(array $conditions)
 * 5. findObjectBy(array $conditions = [], array $selectors = [])
 * 6. findBySearch(rray $selectors = [], array $conditions = [], array $parameters = [], array $optional = [])
 * 7. findByIdAndDelete(array $conditions)
 * 8. findByIdAndUpdate(array $fields = [], int $id)
 * 9. findWithSearchAndPaging(Object $request, array $args = [])
 * 10. findAndReturn(int $id, array $selectors = []);
 * 11. or404()
 */

class DataRepository implements DataRepositoryInterface
{

    use DataRepositoryTrait;

    protected EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * Returns the entityManager object
     *
     * @return object|false
     */
    public function getEm() : EntityManager|false
    {
        return $this->em;
    }
    /**
     * Checks whether the arguement is of the array type else throw an exception
     *
     * @param array $conditions
     * @return void
     */
    private function  isArray(array $conditions): void
    {
        if (!is_array($conditions))
            throw new DataLayerInvalidArgumentException('The argument supplied is not an array');
    }

    /**
     * Checks whether the argument is set else throw an exception
     *
     * @param integer $id
     * @return void
     */
    private function isEmpty(int $id): void
    {
        if (empty($id))
            throw new DataLayerInvalidArgumentException('Argument should not be empty');
    }

    /**
     * A quick and easy way of getting the schemaID within our the controller
     * classe, the getSchemaID method is part of the crud interface method. We
     * are simple just referrencing that method which will give all controller
     * access to the primary key of the revelant database table.
     *
     * @return string
     */
    public function getSchemaID(): string
    {
        return (string)$this->em->getCrud()->getSchemaID();
    }

    /**
     * @inheritDoc
     *
     * @param integer $id
     * @return array
     * @throws DataLayerInvalidArgumentException
     */
    public function find(int $id): array
    {
        $this->isEmpty($id);
        try {
            return $this->findOneBy(['id' => $id]);
        } catch (Throwable $throwable) {
            throw $throwable;
        }
    }

    /**
     * @inheritDoc
     *
     * @return array
     */
    public function findAll(): array
    {
        try {
            return $this->findBy();
        } catch (Throwable $throwable) {
            throw $throwable;
        }
    }

    /**
     * @inheritDoc
     *
     * @param array $selectors
     * @param array $conditions
     * @param array $parameters
     * @param array $optional
     * @return array
     */
    public function findBy(array $selectors = [], array $conditions = [], array $parameters = [], array $optional = []): array
    {
        try {
            return $this->em->getCrud()->read($selectors, $conditions, $parameters, $optional);
        } catch (Throwable $throwable) {
            throw $throwable;
        }
    }

    /**
     * @inheritDoc
     *
     * @param array $conditions
     * @return array
     * @throws DataLayerInvalidArgumentException
     */
    public function findOneBy(array $conditions): array
    {
        $this->isArray($conditions);
        try {
            return $this->em->getCrud()->read([], $conditions);
        } catch (Throwable $throwable) {
            throw $throwable;
        }
    }

    /**
     * @inheritDoc
     *
     * @param array $conditions
     * @param array $selectors
     * @return Object|null
     */
    public function findObjectBy(array $conditions = [], array $selectors = []): Null|Object
    {
        $this->isArray($conditions);
        try {
            return $this->em->getCrud()->get($selectors, $conditions);
        } catch (Throwable $ex) {
            throw new DataLayerNoValueException('The method should have returned an object. But instead nothing has come back. Check that your source contains values.');
        }
    }

    /**
     * @inheritDoc
     *
     * @param array $selectors
     * @param array $conditions
     * @param array $parameters
     * @param array $optional
     * @return array
     * @throws BaseInvalidArgumentException
     */
    public function findBySearch(array $selectors = [], array $conditions = [], array $parameters = [], array $optional = []): array
    {
        $this->isArray($conditions);
        try {
            return $this->em->getCrud()->search($selectors, $conditions, $parameters, $optional);
        } catch (Throwable $throwable) {
            throw $throwable;
        }
    }

    /**
     * Delete bulk item from a database table by simple providing an array of IDs to
     * which you want to delete.
     *
     * @param array $items
     * @return boolean
     */
    public function findAndDelete(array $items = []) : bool
    {
        if (is_array($items) && count($items) > 0) {
            foreach ($items as $item) {
                $delete = $this->findByIDAndDelete(['id' => $item]);
                if ($delete) {
                    return ($delete == true) ? true : false;
                }
            }
        }
        return false;
    }

    /**
     * @inheritDoc
     *
     * @param array $conditions
     * @return boolean
     * @throws DataLayerInvalidArgumentException
     */
    public function findByIdAndDelete(array $conditions): bool
    {
        $this->isArray($conditions);
        try {
            $result = $this->findObjectBy($conditions);
            if ($result) {
                $delete = $this->em->getCrud()->delete($conditions);
                if ($delete) {
                    return ($delete == true) ? true : false;
                }
                
            }
        } catch (Throwable $throwable) {
            throw $throwable;
        }
    }

    /**
     * @inheritDoc
     *
     * @param array $fields
     * @param integer $id
     * @return boolean
     * @throws BaseInvalidArgumentException
     */
    public function findByIdAndUpdate(array $fields = [], int $id): bool
    {
        $this->isArray($fields);
        try {
            $result = $this->findOneBy([$this->em->getCrud()->getSchemaID() => $id]);
            if ($result != null && count($result) > 0) {
                $params = (!empty($fields)) ? array_merge([$this->em->getCrud()->getSchemaID() => $id], $fields) : $fields;
                $update = $this->em->getCrud()->update($params, $this->em->getCrud()->getSchemaID());
                if ($update) {
                    return true;
                }
            }
        } catch (Throwable $throwable) {
            throw $throwable;
        }
    }

    /**
     * @inheritDoc
     *
     * @param array $args
     * @param Object $request
     * @return array|false
     */
    public function findWithSearchAndPaging(Object $request, array $args = []): array|false
    {
        list($conditions, $totalRecords) = $this->getCurrentQueryStatus($request, $args);

        $sorting = new Sortable($args['sort_columns']);
        $paging = new Paginator($totalRecords, $args['records_per_page'], $request->query->getInt('page', 1));
        $parameters = ['limit' => $args['records_per_page'], 'offset' => $paging->getOffset()];
        $optional = ['orderby' => $sorting->getColumn() . ' ' . $sorting->getDirection()];

        if ($request->query->getAlnum($args['filter_alias'])) {
            $searchRequest = $request->query->getAlnum($args['filter_alias']);
            if (is_array($args['filter_by'])) {
                for ($i = 0; $i < count($args['filter_by']); $i++) {
                    $searchConditions = [$args['filter_by'][$i] => $searchRequest];
                }
            }
            $results = $this->findBySearch($args['filter_by'], $searchConditions);
        } else {
            $queryConditions = array_merge($args['additional_conditions'], $conditions);
            $results = $this->findBy($args['selectors'], $queryConditions, $parameters, $optional);
        }
        return [
            $results,
            $paging->getPage(),
            $paging->getTotalPages(),
            $totalRecords,
            $sorting->sortDirection(),
            $sorting->sortDescAsc(),
            $sorting->getClass(),
            $sorting->getColumn(),
            $sorting->getDirection()
        ];
    }

    /**
     * Query the database and returns the relevant amount of results based on the set query.
     *
     * @param Object $request
     * @param array $args
     * @return array|false
     */
    private function getCurrentQueryStatus(Object $request, array $args) : array|false
    {
        $totalRecords = 0;
        $req = $request->query;
        $status = $req->getAlnum($args['query']);
        $searchResults = $req->getAlnum($args['filter_alias']);
        if ($searchResults) {
            for ($i = 0; $i < count($args['filter_by']); $i++) {
                $conditions = [$args['filter_by'][$i] => $searchResults];
                $totalRecords = $this->em->getCrud()->countRecords($conditions, $args['filter_by'][$i]);
            }
        } else if ($status) {
            $conditions = [$args['query'] => $status];
            $totalRecords = $this->em->getCrud()->countRecords($conditions);
        } elseif (count($args['additional_conditions']) > 0) {
            $conditions = $args['additional_conditions'];
            $totalRecords = $this->em->getCrud()->countRecords($conditions);
        } else {
            $conditions = [];
            $totalRecords = $this->em->getCrud()->countRecords($conditions);
        }
        return [
            $conditions,
            $totalRecords
        ];
    }


    /**
     * @inheritDoc
     *
     * @param integer $id
     * @param array $selectors
     * @return self
     */
    public function findAndReturn(int $id, array $selectors = []): self
    {
        if (empty($id) || $id === 0) {
            throw new DataLayerInvalidArgumentException('Please add a valid argument');
        }
        try {
            $this->findAndReturn = $this->findObjectBy(['id' => $id], $selectors);
            return $this;
        } catch (Throwable $throwable) {
            throw $throwable;
        }
    }

    /**
     * @inheritDoc
     *
     * @return Object|null
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function or404()
    {
        if ($this->findAndReturn != null) {
            return $this->findAndReturn;
        } else {
            header('HTTP/1.1 404 not found');
            // $twig = new \Magma\Base\BaseView();
            //$twig->twigRender('error/404.html.twig');
            exit;
        }
    }

    public function count(array $conditions = [], ?string $field = 'id')
    {
        return $this->em->getCrud()->countRecords($conditions, $field);
    }
}
