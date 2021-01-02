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

namespace MagmaCore\DataObjectLayer\FileStorageRepository;

use MagmaCore\DataObjectLayer\Exception\DataLayerInvalidArgumentException;
use MagmaCore\DataObjectLayer\Exception\DataLayerException;
use MagmaCore\Utility\Paginator;

class FileStorageRepository extends FileStorage implements FileStorageRepositoryInterface
{

    /**
     * Auto generate a unique ID for each record we insert in the flat database
     * file. Whilst this approach doesn't guarantee a unique ID for. But will be 
     * sufficient for this purpose
     * 
     * @since 1.0.0
     * @param array $fields - Accept the method to merge the auto ID column to it
     * @return array
     */
    public function autoID($fields)
    {
       return array_merge(["id" => uniqid('id_', true), "datetime" => date('Y-m-d H:i:s')], $fields);
    }

    /**
     * @inheritDoc
     */
    public function persist(array $fields)
    {
        try{
            $fields = $this->autoID($fields);
            $this->flatDatabase()
            ->insert()
            ->in($this->paper)
            ->set($fields)
            ->execute();
            
            return true;
            
        } catch(DataLayerInvalidArgumentException $e){
            error_log($e->getMessage());
        } catch (DataLayerException $e) {
        }
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function findAll(string $sort = 'datetime', int $limit = 10)
    {
        $results = $this->flatDatabase()
            ->read()
            ->in($this->paper)
            ->limit($limit)
            ->sortDesc($sort)
            ->get();
        
        if ($results)
            return $results;
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function findBy(string $key, string $operator, $value, string $sort = 'created_at', int $limit = 10)
    {
        if (isset($this->conditions) && $this->conditions == true) {
            $results = $this->flatDatabase()
                ->read()
                ->in($this->paper)
                ->where($key, $operator, $value)
                ->limit($limit)
                ->sortDesc($sort)
                ->get();

        } else {
            $results = $this->flatDatabase()
                ->read()
                ->in($this->paper)
                ->limit($limit)
                ->sortDesc($sort)
                ->get();
        }
        if ($results)
            return $results;
    }

    /**
     * @inheritDoc
     */
    public function findFirst()
    {
        $results = $this->flatDatabase()
            ->read()
            ->in($this->paper)
            ->first();
        
        if ($results)
            return $results;
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function findSkip($skip = 5, $limit = 10)
    {
        $results = $this->flatDatabase()
            ->read()
            ->in($this->paper)
            ->skip($skip)
            ->limit($limit)
            ->get();
        
        if ($results)
            return $results;
    }

    /**
     * @inheritDoc
     * @throws InvalidArgumentException
     * @throws Exception
     */
    public function save(array $fields, string $key, string $operator, $value)
    {
        if (isset($this->conditions) && $this->conditions == true) {
            $save = $this->flatDatabase()
                ->update()
                ->in($this->paper)
                ->set($fields)
                ->where($key, $operator, $value)
                ->execute();
        } else {
            $save = $this->flatDatabase()
                ->update()
                ->in($this->paper)
                ->set($fields)
                ->execute();
        }
        if ($save) {
            return true;
        }
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function delete(string $key, string $operator, $value)
    {
        if (isset($this->conditions) && $this->conditions == true) {
            $delete = $this->flatDatabase()
            ->delete()
            ->in($this->paper)
            ->where($key, $operator, $value)
            ->execute();

        } else {
            $delete = $this->flatDatabase()
            ->delete()
            ->in($this->paper)
            ->execute();
        }

        if ($delete) {
            return true;
        }
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function findPaperResultsAndPaginate(array $args, $request)
    {
        if (empty($args)) {
            throw new DataLayerException("Missing arguments in " . __METHOD__);
        }

        $page = $request->query->getInt("page", 1);
        $defaults = ["records_per_page" => 5, "sort_by" => ""]; // Default for $args
        $arg = array_merge($defaults, $args);

        /* Filter by query and return total records along with query */
        list($key, $value, $totalRecords) = $this->totalQueryStatusCount($request, $args['query']); // get variables from method array return
        $paging = new Paginator($totalRecords, $args['records_per_page'], $page);

        $results = $this->findBy($key, "=", $value, $args['sort_by'], intval($args['records_per_page']));
        $results = (array)$results;

        if ($results) {
            return [
                $results,
                $paging->getPage(),
                $paging->getTotalPages(),
                $totalRecords
           ];
        }

    }

    /**
     * @inheritDoc
     */
    public function totalQueryStatusCount($request, $query)
    {
        $total_records = 0;
        $req = $request->query;
        $query_value = $req->getAlnum($query); // Get the value of the query string
        $key = $value = '';

        if ($req->getAlnum($query))  {
            $key = $query;
            $value = $query_value;
            $total_records = $this->numRowsWith($key, "=", $value);
        } else {
            $total_records = $this->numRows();
        }
        return [
            $key,
            $value,
            $total_records
        ];

    }

    /**
     * @inheritDoc
     */
    public function numRows()
    {
        $count = $this->flatDatabase()->read()->in($this->paper)->count();
        if ($count) {
            return $count;
        }
    }

    /**
     * @inheritDoc
     */
    public function numRowsWith(string $key, string $operator, $value)
    {
        $count = $this->flatDatabase()->read()->in($this->paper)->where($key, $operator, $value)->count();
        if ($count) {
            return $count;
        }
    }


}