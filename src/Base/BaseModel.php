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

namespace MagmaCore\Base;

use MagmaCore\Base\Exception\BaseInvalidArgumentException;
use MagmaCore\DataObjectLayer\DataRepository\DataRepositoryFactory;
use MagmaCore\DataObjectLayer\DataRepository\DataRepository;

class BaseModel
{ 

    /** @var string */
    protected string $tableSchema;
    /** @var string */
    protected string $tableSchemaID;
    /** @var Object */
    protected Object $repository;

    /**
     * Main class constructor
     *
     * @param string $tableSchema
     * @param string $tableSchemaID
     */
    public function __construct(string $tableSchema = null, string $tableSchemaID = null)
    {
        if (empty($tableSchema) || empty($tableSchemaID)) {
            throw new BaseInvalidArgumentException('Your repository is missing the required constants. Please add the TABLESCHEMA and TABLESCHEMAID constants to your repository.');
        }
        $factory = new DataRepositoryFactory('baseModel', $tableSchema, $tableSchemaID);
        $this->repository = $factory->create(DataRepository::class);
    }

    /**
     * Returns the current repository
     * @return Object
     */
    public function getRepo()
    {
        return $this->repository;
    }


}
