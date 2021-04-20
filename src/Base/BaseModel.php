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

use MagmaCore\Base\BaseEntity;
use MagmaCore\Base\BaseApplication;
use MagmaCore\Base\Exception\BaseException;
use MagmaCore\Base\Exception\BaseInvalidArgumentException;
use MagmaCore\DataObjectLayer\DataRepository\DataRepository;
use MagmaCore\DataObjectLayer\DataRepository\DataRepositoryFactory;
use MagmaCore\DataObjectLayer\DataRelationship\DataRelationshipInterface;
use MagmaCore\DataObjectLayer\DataRelationship\Exception\DataRelationshipInvalidArgumentException;

class BaseModel
{

    /** @var string */
    protected string $tableSchema;
    /** @var string */
    protected string $tableSchemaID;
    /** @var Object */
    protected Object $repository;
    /** @var BaseEntity */
    protected BaseEntity $entity;
    /** @var array $casts */
    protected array $cast = [];

    protected const ALLOWED_CASTING_TYPES = ['array_json'];

    /**
     * Main class constructor
     *
     * @param string $tableSchema
     * @param string $tableSchemaID
     * @param string|null $entity
     */
    public function __construct(
        string $tableSchema = null,
        string $tableSchemaID = null,
        string $entity = null
    ) {
        $this->throwException($tableSchema, $tableSchemaID);
        if ($entity !== null) {
            $this->entity  = BaseApplication::diGet($entity);
            if (!$this->entity instanceof BaseEntity) {
                throw new BaseInvalidArgumentException('');
            }
        }
        $this->tableSchema = $tableSchema;
        $this->tableSchemaID = $tableSchemaID;
        $this->createRepository($this->tableSchema, $this->tableSchemaID);
    }

    /**
     * Create the model repositories
     *
     * @param string $tableSchema
     * @param string $tableSchemaID
     * @return void
     */
    public function createRepository(string $tableSchema, string $tableSchemaID): void
    {
        $factory = new DataRepositoryFactory('baseModel', $tableSchema, $tableSchemaID);
        $this->repository = $factory->create(DataRepository::class);
    }

    /**
     * Throw an exception
     *
     * @return void
     */
    private function throwException(string $tableSchema, string $tableSchemaID): void
    {
        if (empty($tableSchema) || empty($tableSchemaID)) {
            throw new BaseInvalidArgumentException('Your repository is missing the required constants. Please add the TABLESCHEMA and TABLESCHEMAID constants to your repository.');
        }
    }

    /**
     * Returns the related model entity object
     *
     * @return BaseEntity
     */
    public function getEntity(): BaseEntity
    {
        return $this->entity;
    }

    /**
     * two way casting cast a data type back and fourth
     *
     * @return void
     */
    public function casting()
    {
        if (isset($this->cast)) {
            if (is_array($this->cast) && count($this->cast) > 0) {
                foreach ($this->cast as $key => $value) {
                    if (!in_array($value, self::ALLOWED_CASTING_TYPES)) {
                        throw new BaseInvalidArgumentException($value . ' casting type is not supported.');
                    }
                    $this->resolveCast($key, $value);
                }
            }
        }
    }

    private function resolveCast(string $key, mixed $value)
    {
        if (empty($key)) {
            throw new BaseException('');
        }
        switch ($value) {
            case 'array_json':
                if (isset($this->getEntity()->$key) && $this->getEntity()->$key !== '') {
                    $this->getEntity()->$key = json_encode($value);
                }
                break;
        }
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
     * Returns the databae table schema name
     * 
     * @return string
     */
    public function getSchemaID(): string
    {
        return $this->tableSchemaID;
    }

    /**
     * Returns the database table schema primary key 
     * 
     * @return string
     */
    public function getSchema(): string
    {
        return $this->tableSchema;
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

    /**
     * Undocumented function
     *
     * @param string $relationshipType
     * @return object
     */
    public function addRelationship(string $relationshipType): object
    {
        $relationship = BaseApplication::diGet($relationshipType);
        if (!$relationship instanceof DataRelationshipInterface) {
            throw new DataRelationshipInvalidArgumentException('');
        }
        return $relationship;

    }
}
