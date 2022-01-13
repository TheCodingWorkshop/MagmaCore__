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

use MagmaCore\Base\BaseApplication;
use MagmaCore\DataObjectLayer\DataRelationship\DataRelationalInterface;
use MagmaCore\DataObjectLayer\DataRelationship\Exception\DataRelationshipInvalidArgumentException;

class BaseModelRelationship implements DataRelationalInterface
{

    private BaseModel $baseModel;

    public function __construct(BaseModel $baseModel)
    {
        $this->baseModel = $baseModel;
    }

    /**
     * Build relationships between tables
     *
     * @param string $relationshipType
     * @return object
     */
    public function setRelationship(string $relationshipType): object
    {
        $relationship = BaseApplication::diGet($relationshipType);
        if (!$relationship instanceof DataRelationalInterface) {
            throw new DataRelationshipInvalidArgumentException('');
        }
        return $relationship;
    }

    /**
     * Returns the associated relationship objects
     * @param string $relationships
     * @return object
     */
    public function getRelationship(string $relationships): object
    {
        $relationshipObject = BaseApplication::diGet($relationships);
        // if (!$relationshipObject instanceof BaseRelationshipInterface) {
        //     throw new BaseInvalidArgumentException('');
        // }
        return $relationshipObject->united();
    }


}

