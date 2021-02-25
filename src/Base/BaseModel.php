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
use MagmaCore\Base\BaseApplication;

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

    /**
     * Return the name object from within the app namespace. i,e validate.user
     * will instantiate the App\Validate\UserValidate Object. We only call the object
     * on the fly and use it when we want.
     *
     * @param string $objectName - the name of the object with the dot notation
     * @return object
     * @throws InvalidArgumentException
     * @throws ReflectionException
     */
    public function get(string $objectName)
    {
        if (empty($objectName)) {
            throw new \InvalidArgumentException('Please provide the name of your object');
        }
        if (strpos($objectName, '.') === false) {
            throw new \InvalidArgumentException('Invalid object name ensure you are referencing the object using the correct notation i.e validate.user');
        }
        // As we are expecting the object name using dot notations we need to convert it
        if (is_string($objectName)) {
            $objectName = 'app.' . $objectName;    
            $objectName = ucwords(str_replace('.', '\\', $objectName));

            return BaseApplication::diGet($objectName);
        }

    }



}
