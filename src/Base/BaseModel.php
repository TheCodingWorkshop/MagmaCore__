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

use ReflectionClass;
use ReflectionProperty;
use MagmaCore\Base\BaseEntity;
use MagmaCore\Base\BaseApplication;
use MagmaCore\Base\Traits\ModelCastingTrait;
use MagmaCore\DataSchema\DataSchemaBuilderInterface;
use MagmaCore\Base\Exception\BaseInvalidArgumentException;
use MagmaCore\DataObjectLayer\DataRepository\DataRepository;
use MagmaCore\DataObjectLayer\DataRepository\DataRepositoryFactory;
use MagmaCore\DataObjectLayer\DataRelationship\DataRelationalInterface;
use MagmaCore\DataObjectLayer\DataRelationship\Exception\DataRelationshipInvalidArgumentException;

class BaseModel
{
    use ModelCastingTrait;

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
    /** @var array */
    protected array $dbColumns = [];
    /** @var array $fillable - returns a array of columns which cannot be null */
    protected array $fillable = [];
    /** @var array */
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
        $this->casting(self::ALLOWED_CASTING_TYPES);
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
     * Returns the related model entity object
     *
     * @return BaseEntity
     */
    public function getEntity(): BaseEntity
    {
        return $this->entity;
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
     * Allow an association between two tables.
     *
     * @param string $relationshipType
     * @return object
     */
    public function addRelationship(string $relationshipType): object
    {
        $relationship = BaseApplication::diGet($relationshipType);
        if (!$relationship instanceof DataRelationalInterface) {
            throw new DataRelationshipInvalidArgumentException('');
        }
        return $relationship;
    }

    /**
     * Create method which initialize the schema object and return its result
     * within the set class property.
     *
     * @param string $dataSchema
     * @return static
     */
    public function create(string $dataSchema = null): static
    {
        if ($dataSchema !== null) {
            $newSchema = BaseApplication::diGet($dataSchema);
            if (!$newSchema instanceof DataSchemaBuilderInterface) {
                throw new BaseInvalidArgumentException('');
            }
            $this->dataSchemaObject = $newSchema;
            return $this;
        }
    }

    /**
     * Return an array of database column name matching the object schema
     * and model
     * 
     * @param string $schema
     * @return array
     * @throws BaseInvalidArgumentException
     */
    public function getColumns(string $schema): array
    {
        return $this->create($schema)->getSchemaColumns();
    }

    /**
     * Undocumented function
     *
     * @param string $model
     * @return array
     */
    public function getFillables(string $model): array
    {
        if (!$this->fillable) {
            throw new BaseInvalidArgumentException('No fillable array set for your entity class ' . $model);
        }
        return $this->fillable;
    }

    /**
     * Return the schema object database column name as an array. Which can be used
     * to mapp the columns within the dataColumn object. To construct the datatable
     *
     * @param integer $indexPosition
     * @return array
     */
    public function getSchemaColumns(int $indexPosition = 2): array
    {
        $reflectionClass = new ReflectionClass($this->dataSchemaObject);
        $propertyName = $reflectionClass->getProperties()[$indexPosition]->getName();
        if (str_contains($propertyName, 'Model') === false) {
            throw new BaseInvalidArgumentException('Invalid property name');
        }
        if ($reflectionClass->hasProperty($propertyName)) {
            $reflectionProperty = new ReflectionProperty($this->dataSchemaObject, $propertyName);
            $reflectionProperty->setAccessible(true);
            $props = $reflectionProperty->getValue($this->dataSchemaObject);
            $this->dbColumns = $props->getRepo()
                ->getEm()
                ->getCrud()
                ->rawQuery('SHOW COLUMNS FROM ' . $props->getSchema(), [], 'columns');

            return $this->dbColumns;

            $reflectionProperty->setAccessible(false);
        }
    }

    /**
     * Undocumented function
     *
     * @param array $conditions
     * @param mixed $data
     * @return mixed
     */
    public function unserializeData(array $conditions, mixed $data = null)
    {
        if ($conditions) {
            $serializeData = $this->getRepo()->findOneBy($conditions);
            if ($serializeData) {
                foreach ($serializeData as $serialData) {
                    if (is_null($data)) {
                        throw new \Exception();
                    }
                    if (is_array($data)) {
                        return array_map(fn($d) => unserialize($serialData[$d]), $data);
                    } elseif (is_string($data)) {
                        return unserialize($serialData[$data]);
                    } 

                }
            }
        }
            
    }
}
