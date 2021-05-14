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

namespace MagmaCore\Datatable;

interface DatatableInterface
{

    /**
     * Method which initialize the data table. Pulls in the data column
     * object and the object repository. To build the various parts on the 
     * data table
     * 
     * @param string $dataColumnObjectName - the data columns object
     * @param array $dataRepositoryObject - the repository object containing mixed data
     * @param array $sortController - an array of columns to sort by defined within the controller classes
     * @param object $callingController - the controller which is calling the object
     * @return self
     * @throws InvalidArgumentException
     */
    public function create(string $dataColumnString, array $dataRepository = [], array $sortController = [], array $dbColumns = [], object|null $callingController = null) : self;

    /**
     * Generate the data table using the objects and properties pass to
     * the constructor from each controller. Controller data comes prebuild
     * with data results pagination and sorting variables
     * 
     * @return null|string
     */
    public function table() : ?string;

    /**
     * Generates the necessary links which allow scrolling through the
     * table data. Based on the parameters passed to the paginator class
     * 
     * @return null|string
     */
    public function pagination() : ?string;

}