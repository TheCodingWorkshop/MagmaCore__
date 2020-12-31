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

namespace MagmaCore\Utility;

class Paginator
{

    /** @var int */
    protected float $totalPages;

    /** @var int */
    protected int $page;
    
    /** @var int */
    protected float $offset;

    /**
     * Class constructor
     *
     * @param integer $totalRecords Total number of records
     * @param integer $recordsPerPage Number of records on each page
     * @param string $page Current page
     *
     * @return void
     */
    public function __construct(int $totalRecords, int $recordsPerPage, int $page)
    {
        // Make sure the page number is within a valid range from 1 to the total number of pages
        $this->totalPages = ceil($totalRecords / $recordsPerPage);
        $data = [
            'options' => [
                'default' => 1,
                'min_range' => 1,
                'max_range' => $this->totalPages
            ]
        ];
        $this->page = filter_var($page, FILTER_VALIDATE_INT, $data);
        // Calculate the starting record based on the page and number of records per page
        $this->offset = $recordsPerPage * ($this->page - 1);
    }

    /**
     * Get the starting record within the SQL Query
     * 
     * @return int
     */
    public function getOffset() : int
    {
        return (int)$this->offset;
    }

    /**
     * Gte the current page
     * 
     * @return void
     */
    public function getPage() : int
    {
        return (int)$this->page;
    }

    /**
     * Get the total number of pages
     * 
     * @return int
     */
    public function getTotalPages() : int
    {
        return (int)$this->totalPages;
    }


}