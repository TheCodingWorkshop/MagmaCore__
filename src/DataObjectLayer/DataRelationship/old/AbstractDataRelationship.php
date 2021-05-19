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

use MagmaCore\Base\BaseApplication;
use MagmaCore\DataObjectLayer\DataRelationship\DataRelationshipInterface;

/**
 * Each record in both tables can relate to none or any number of records 
 * in the other table. These relationships require a third table, 
 * called an associate or linking table, because relational systems cannot 
 * directly accommodate the relationship.
 */
abstract class AbstractDataRelationship implements DataRelationshipInterface
{

    protected object $hasOne;
    protected object $belongsTo;
    protected object $objectModel;

    /**
     * Undocumented function
     *
     * @param string $hasOne
     * @return void
     */
    public function hasOne(string $hasOne): static
    {
        if ($hasOne)
            $this->hasOne = BaseApplication::diGet($hasOne);

        return $this;
    }

    public function getHasOneSchema(): string
    {
        return $this->hasOne->getSchema();
    }

    public function getHasOneSchemaID(): string
    {
        return $this->hasOne->getSchemaID();
    }

    /**
     * 
     *
     * @param string $tablePivot
     * @return void
     */
    public function tablePivot(object $pivotObject, string $schemaObject): static
    {
        if ($pivotObject)
            $this->tablePivot = $pivotObject;
        if ($schemaObject)
            $this->schemaObject = $schemaObject;
        return $this;
    }

    public function filterSelection(
        string $parentModel, 
        array $selectors, 
        $defaultSelector = ['*']
    )
    {
        $filter = array_map(
            fn($selector) : string => $parentModel . '.' . $selector, 
            $selectors
        );
        return (empty($filter)) ? $defaultSelector : $filter;

    }

}
