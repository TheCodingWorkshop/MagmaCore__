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

namespace MagmaCore\DataObjectLayer\DataRelationship;

use Exception;
use MagmaCore\Base\BaseApplication;
use MagmaCore\DataObjectLayer\DataRelationship\DataLayerClientFacade;
use MagmaCore\DataObjectLayer\DataRelationship\Exception\DataRelationshipInvalidArgumentException;

/**
 * Both tables can have only one record on each side of the relationship.
 * each primary key value relates to none or only one record in the related table
 */
class DataRelationship
{

    private object $pivot;
    private $callback = null;
    private ?object $andBelongsToMany = null;
    private ?object $belongsToMany = null;
    private ?object $belongsTo = null;
    private ?object $hasMany = null;
    private ?object $andBelongsTo = null;
    private ?object $setMoreRelationship = null;

    /**
     * Return and instance of a model object
     * @param string $model
     * @return object
     */
    public function init(string $model): object
    {
        if ($model) {
            return BaseApplication::diGet($model);
        }
    }
    /**
     * Sets the model object related to this method
     * @param string $model
     * @return self
     */
    public function belongsToMany(string $model): self
    {
        $this->belongsToMany = $this->init($model);
        return $this;
    }

    /**
     * Returns the model object related to the property
     * @return object
     */
    public function getBelongsToMany(): object
    {
        return $this->belongsToMany;
    }

    /**
     * Sets the model object related to this method
     * @param string $model
     * @param callable|null $callback
     * @return self
     */
    public function andBelongsToMany(string $model, ?callable $callback = null): self
    {
        if (!is_callable($callback)) {
            throw new DataRelationshipInvalidArgumentException('');
        }
        $this->andBelongsToMany = $this->init($model);
        $this->callback = $callback($this);
        return $this;
    }

    /**
     * Returns the model object related to the property
     * @return object
     */
    public function getAndBelongsToMany(): object
    {
        return $this->andBelongsToMany;
    }

    /**
     * @param string $model
     * @return $this
     */
    public function belongsTo(string $model): self
    {
        $this->belongsTo = $this->init($model);
        return $this;
    }

    /**
     * @param string $model
     * @return $this
     */
    public function andBelongsTo(string $model): self
    {
        $this->andBelongsTo = $this->init($model);
        return $this;
    }

    /**
     * @return object
     */
    public function getAndsBelongsTo(): object
    {
        return $this->andBelongsTo;
    }

    /**
     * @return object
     */
    public function getBelongsTo(): object
    {
        return $this->belongsTo;
    }

    /**
     * Sets the pivot model object
     * @param string $model
     * @return void
     */
    public function pivot(string $model): self
    {
        $this->pivot = $this->init($model);
        return $this;
    }

    /**
     * Returns the pivot model object
     * @return object
     */
    public function getPivot(): object
    {
        return $this->pivot;
    }

    /**
     * Returns the schema name for the queried model
     * @param string $property
     * @return string
     */
    public function getSchema(string $property): string
    {
        if (!empty($property))
            return $this->{$property}()->getSchema();
    }

    /**
     * Returns the schema id for the queried model
     * @param string $property
     * @return string
     */
    public function getSchemaID(string $property): string
    {
        if (!empty($property))
            return $this->{$property}()->getSchemaID();
    }

    /**
     * Returns the repository for the queried model
     * @param string $property
     * @return object
     */
    public function getRepo(string $property): object
    {
        if (!empty($property))
            return $this->{$property}()->getRepo();
    }

    /**
     * @param string $resultType
     * @return mixed
     */
    public function associate(string $resultType): mixed
    {
    }

    /**
     * @param string $relationshipType
     * @return $this
     */
    public function setMoreRelationship(string $relationshipType): self
    {
        $relationshipType = BaseApplication::diGet($relationshipType);
        if (!$relationshipType instanceof DataRelationalInterface) {
            throw new DataRelationshipInvalidArgumentException('');
        }
        $this->setMoreRelationship = $relationshipType;
        return $this;
    }

    /**
     * @param string $identifier
     * @param string $schema
     * @param string $schemaID
     * @return object
     */
    public function getClientRepo(string $identifier, string $schema, string $schemaID): object
    {
        $clientRepo = new DataLayerClientFacade($identifier, $schema, $schemaID);
        if ($clientRepo) {
            return $clientRepo->getClientRepository();
        }
    }


}
