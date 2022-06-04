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

use Closure;
use Throwable;
use ReflectionClass;
use ReflectionProperty;
use MagmaCore\Base\BaseEntity;
use MagmaCore\Base\BaseApplication;
use MagmaCore\Base\Traits\ModelCastingTrait;
use MagmaCore\DataSchema\DataSchemaBuilderInterface;
use MagmaCore\Base\Exception\BaseInvalidArgumentException;
use MagmaCore\DataObjectLayer\DataRepository\DataRepository;
use MagmaCore\DataObjectLayer\DataRepository\DataRepositoryFactory;

class BaseModel extends BaseModelRelationship
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

    protected array $nullableClone = [];
    protected array $unsettableClone = [];
    protected array $cloneableKeys = [];
    protected array $columnStatus = [];

    /** @var array */
    protected const ALLOWED_CASTING_TYPES = ['array_json'];

    /**
     * Main class constructor
     *
     * @param string $tableSchema
     * @param string $tableSchemaID
     * @param string|null $entity
     */
    public function __construct(string $tableSchema = null,string $tableSchemaID = null,string $entity = null) 
    {
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

        parent::__construct($this);
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
        try {
            $factory = new DataRepositoryFactory('baseModel', $tableSchema, $tableSchemaID);
            $this->repository = $factory->create(DataRepository::class);
        } catch(Throwable $throwable) {
            throw $throwable;
        }
    }

    /**
     * Throw an exception if the two required model constants is empty
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
     * @return object
     */
    public function getRepo(): object
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

    public function trashSupport(): string
    {
        return 'deleted_at';
    }

    /**
     * Allows models to retrieve other models. Simple pass in the qualified namespace of the model
     * we want an object of ie getOtherModel(RoleModel::class)->getRepo() which will give access
     * to the repository for the other model
     *
     * @param string $model
     * @return BaseModel
     */
    public function getOtherModel(string $model): BaseModel
    {
        if (!is_string($model)) {
            throw new BaseInvalidArgumentException('Invalid argument. Ensure you are passing the fully qualified namespace of the other model. ie [ExampleModel::class]');
        }
        $modelObject = BaseApplication::diGet($model);
        if (!$modelObject instanceof self) {
            throw new BaseInvalidArgumentException($model . ' is an invalid model. As its does not relate to the BaseModel.');
        }
        return $modelObject;

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
    public function get(string $objectName, string $optionalNamespace = null)
    {
        if (empty($objectName)) {
            throw new \InvalidArgumentException('Please provide the name of your object');
        }
        if (strpos($objectName, '.') === false) {
            throw new \InvalidArgumentException('Invalid object name ensure you are referencing the object using the correct notation i.e validate.user');
        }
        // As we are expecting the object name using dot notations we need to convert it
        if (is_string($objectName)) {
           
            if ($optionalNamespace !==null) {
                $pieces = explode('.', $objectName);
                $name = isset($pieces[1]) ? $pieces[1] : '';
                $modelName = ucwords($optionalNamespace . $name);
        
            } else {
                $objectName = 'app.' . $objectName;
                $modelName = ucwords(str_replace('.', '\\', $objectName));

            }

            return BaseApplication::diGet($modelName);
        }
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
    public function getColumns(string $schema = null): array
    {
        return $this->create($schema)->getSchemaColumns();
    }

    /**
     * Allows each model to return a $fillable array of database column names which
     * must never be null. Each model must define a class property of $fillable which
     * returns an array of fillable fields
     *
     * @param string $model
     * @return array
     */
    public function getFillables(?string $model = null): array
    {
        if (!$this->fillable) {
            throw new BaseInvalidArgumentException('No fillable array set for your entity class ' . $model);
        }
        return $this->fillable;
    }

    /**
     * Return the schema object database column name as an array. Which can be used
     * to map the columns within the dataColumn object. To construct the datatable
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

            $reflectionProperty->setAccessible(false);

            return $this->dbColumns;

        }
    }

    /**
     * Unserialize any serialize data coming from the database
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

    /**
     * Unset the database column which is not cloneable
     *
     * @param array $cloneArray
     * @return void
     */
    public function unsetCloneKeys(array $cloneArray)
    {
        if (is_array($this->unsettableClone) && count($this->unsettableClone) > 0) {
            foreach ($this->unsettableClone as $unsettable) {
                unset($cloneArray[$unsettable]);
            }
        }
        return $cloneArray;
    }

    /**
     * returns an array of database column which should be unique when cloning
     *
     * @return array
     */
    public function getClonableKeys(): ?array
    {
        if (is_array($this->cloneableKeys) && count($this->cloneableKeys) > 0) {
            return $this->cloneableKeys;
        }
        return null;
    }

    /**
     * Get the value of a column[name] for the current queried ID. The name of the column must
     * be specified within the second argument.
     * 
     * @param int $id
     * @param string $field
     * @return mixed
     */
    public function getSelectedNameField(int $id, string $field = null, ?string $model = null): mixed
    {
        $name = $this->getRepo()->findObjectBy(['id' => $id], [$field]);
        if ($field === null) {
            throw new BaseInvalidArgumentException('Your second argument is null. This needs to represent a column name for the matching repository.');
        }
        return $name->$field;
    }

    /**
     * Return an array of column values if table supports the column field
     *
     * @return array
     */
    public function getColumnStatus(): array
    {
        return $this->columnStatus;
    }

    public function relationship(Closure $closure = null)
    {
        if (!$closure instanceof Closure) {
            throw new \Exception(sprintf('%s is not an instance of closure', $closure));
        }
        return $closure($this);
    }

    /**
     * Creating relationships between tables starts by with addParent() method. This is the method which
     * acts as the main parent table. The method will automatically create a alias or the second argument
     * can be used to specify a custom alias for the query. The 3rd argument allow customizing of the data
     * columns from the database table which is returned. We can add as much relative to a parent table
     * which exists. All column from all relations will be return. The 3rd argument allows us to limit what result
     * we want to get back.
     *
     * @param object|null $parentModel
     * @param string|null $parentAlias
     * @param array|null $selectors
     * @return self
     */
    public function addParent(object $parentModel = null, string $parentAlias = null, array $selectors = null): self
    {
        $this->parentModel = $parentModel;
        /* this should get the first character of the parent table schema name */
        $this->parentAlias = ($parentAlias !==null ? $parentAlias : substr($parentModel->getSchema(), 0, 1));
        $this->selectors = (isset($selectors) && is_array($selectors) && count($selectors) > 0 ? implode(', ', $selectors) : '*');
        $this->joinQuery .= sprintf(
            'SELECT %s FROM %s %s ', 
                $this->selectors, 
                $this->parentModel->getSchema(), 
                $this->parentAlias
            );
        return $this;
    }

    /**
     * This method allows us to add a relationship table to our parent table. only if the foreign key of the 
     * child table is link to the primary key in the parent table. The method will return an exception otherwise
     * saying no relationship exists between the child and the parent table. The first argument takes the qualified 
     * namespace of the child model. Which is then converted to the child model object. The 2nd argument is a closure
     * which has access to the current base model object and its own model object
     *
     * @param string|null $model
     * @param Closure|null $fn
     * @return self
     * @throws Exception
     */
    public function addRelation(string $model = null, Closure $fn = null): self
    {
        $this->childModel = BaseApplication::diGet($model);
        if (!$this->childModel instanceof BaseModel) {
            throw new \Exception(sprintf('%s is not an instance of BaseModel', $model));
        }
        $this->joinQuery .= $fn($this, $this->childModel);
        return $this;
    }

    /**
     * Uses the left join. This will return all rows from the left even if theres no match from the
     * right table
     *
     * @param string|null $foreignKey
     * @param string $alias
     * @return void
     */
    public function leftJoin(string $foreignKey = null, string $alias = null)
    {
        return  sprintf(
            'LEFT JOIN %s %s ON %s.%s = %s.%s ', 
                $this->childModel->getSchema(), 
                $alias, 
                $this->parentAlias,
                $this->parentModel->getSchemaID(),
                $alias,
                $foreignKey
            );

    }
    
    /**
     * Uses the right join. This will return all rows from the right even if theres no match from the
     * left table
     *
     * @param string|null $foreignKey
     * @param string $alias
     * @return void
     */
    public function rightJoin(string $foreignKey = null, string $alias = '')
    {
        return  sprintf(
            'RIGHT JOIN %s %s ON %s.%s = %s.%s ', 
                $this->childModel->getSchema(), 
                $alias, 
                $this->parentAlias,
                $this->parentModel->getSchemaID(),
                $alias,
                $foreignKey
            );

    }

    /**
     * Uses the inner join. This will only return results when theres match in all the related 
     * tables.
     *
     * @param string|null $foreignKey
     * @param string $alias
     * @return void
     */
    public function innerJoin(string $foreignKey = null, string $alias = '')
    {
        return  sprintf(
            'INNER JOIN %s %s ON %s.%s = %s.%s ', 
                $this->childModel->getSchema(), 
                $alias, 
                $this->parentAlias,
                $this->parentModel->getSchemaID(),
                $alias,
                $foreignKey
            );

    }

    /**
     * Add a where clause within the relationship query to filter the result for something specific
     *
     * @param integer|null $itemID
     * @return self
     */
    public function where(int $itemID = null): self
    {
        $this->joinQuery .= sprintf(
            ' WHERE %s.%s = :%s', 
                $this->parentAlias, 
                $this->parentModel->getSchemaID(),
                $this->parentModel->getSchemaID()
            );
        $this->whereID = $itemID;
        return $this;
    }

    /**
     * Use the limit clause to limit the amount of result required. coupled with the offset to start
     * fetching data from a specific row.
     *
     * @param integer|null $limit
     * @param integer $offset
     * @return self
     */
    public function limit(int $limit = null, float|int $offset = 0): self
    {
        $this->limit = $limit;
        $this->offset = $offset;
        if (isset($this->limit) && $this->limit !==null && $this->limit > 0) {
            $this->joinQuery .= sprintf(
                ' LIMIT %s, %s', 
                    ':offset', 
                    ':limit'
                );
        }
        return $this;
    }

    /**
     * Bind all the relevant relationships queries and parameters to get the desired results.
     *
     * @param string $type
     * @return mixed
     */
    public function getRelations(string $type = 'fetch_all'): mixed
    {
        $conditions = [];
        if (isset($this->whereID) && $this->whereID !==null) {
            $conditions = [$this->parentModel->getSchemaID() => $this->whereID];
        } elseif (isset($this->limit) && $this->limit !==null && $this->offset !==null) {
            $conditions = ['limit' => $this->limit, 'offset' => $this->offset];
        } else {
            $conditions = [];
        }

        $data = $this->getRepo()->findByRawQuery($this->joinQuery, $conditions, $type);
        if ($data !==null) {
            return $data;
        }
    }

    function calculateBankHolidays($yr) {

        $bankHols = Array();
    
        // New year's:
        switch ( date("w", strtotime("$yr-01-01 12:00:00")) ) {
            case 6:
                $bankHols[] = "$yr-01-03";
                break;
            case 0:
                $bankHols[] = "$yr-01-02";
                break;
            default:
                $bankHols[] = "$yr-01-01";
        }
    
        // Good friday:
        $bankHols[] = date("Y-m-d", strtotime( "+".(easter_days($yr) - 2)." days", strtotime("$yr-03-21 12:00:00") ));
    
        // Easter Monday:
        $bankHols[] = date("Y-m-d", strtotime( "+".(easter_days($yr) + 1)." days", strtotime("$yr-03-21 12:00:00") ));
    
        // May Day:
        if ($yr == 1995) {
            $bankHols[] = "1995-05-08"; // VE day 50th anniversary year exception
        } else {
            switch (date("w", strtotime("$yr-05-01 12:00:00"))) {
                case 0:
                    $bankHols[] = "$yr-05-02";
                    break;
                case 1:
                    $bankHols[] = "$yr-05-01";
                    break;
                case 2:
                    $bankHols[] = "$yr-05-07";
                    break;
                case 3:
                    $bankHols[] = "$yr-05-06";
                    break;
                case 4:
                    $bankHols[] = "$yr-05-05";
                    break;
                case 5:
                    $bankHols[] = "$yr-05-04";
                    break;
                case 6:
                    $bankHols[] = "$yr-05-03";
                    break;
            }
        }
    
        // Whitsun:
        if ($yr == 2002) { // exception year
            $bankHols[] = "2002-06-03";
            $bankHols[] = "2002-06-04";
        } else {
            switch (date("w", strtotime("$yr-05-31 12:00:00"))) {
                case 0:
                    $bankHols[] = "$yr-05-25";
                    break;
                case 1:
                    $bankHols[] = "$yr-05-31";
                    break;
                case 2:
                    $bankHols[] = "$yr-05-30";
                    break;
                case 3:
                    $bankHols[] = "$yr-05-29";
                    break;
                case 4:
                    $bankHols[] = "$yr-05-28";
                    break;
                case 5:
                    $bankHols[] = "$yr-05-27";
                    break;
                case 6:
                    $bankHols[] = "$yr-05-26";
                    break;
            }
        }
    
        // Summer Bank Holiday:
        switch (date("w", strtotime("$yr-08-31 12:00:00"))) {
            case 0:
                $bankHols[] = "$yr-08-25";
                break;
            case 1:
                $bankHols[] = "$yr-08-31";
                break;
            case 2:
                $bankHols[] = "$yr-08-30";
                break;
            case 3:
                $bankHols[] = "$yr-08-29";
                break;
            case 4:
                $bankHols[] = "$yr-08-28";
                break;
            case 5:
                $bankHols[] = "$yr-08-27";
                break;
            case 6:
                $bankHols[] = "$yr-08-26";
                break;
        }
    
        // Christmas:
        switch ( date("w", strtotime("$yr-12-25 12:00:00")) ) {
            case 5:
                $bankHols[] = "$yr-12-25";
                $bankHols[] = "$yr-12-28";
                break;
            case 6:
                $bankHols[] = "$yr-12-27";
                $bankHols[] = "$yr-12-28";
                break;
            case 0:
                $bankHols[] = "$yr-12-26";
                $bankHols[] = "$yr-12-27";
                break;
            default:
                $bankHols[] = "$yr-12-25";
                $bankHols[] = "$yr-12-26";
        }
    
        // Millenium eve
        if ($yr == 1999) {
            $bankHols[] = "1999-12-31";
        }
    
        return $bankHols;
    
    }
}
