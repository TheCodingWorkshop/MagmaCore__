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

/**
 * Each record in both tables can relate to none or any number of records 
 * in the other table. These relationships require a third table, 
 * called an associate, pivot or linking table, because relational systems cannot 
 * directly accommodate the relationship.
 */
class DataManyToMany extends AbstractDataRelationship
{

    protected object $relatableModel;

    public function __construct(string $relatableModel)
    {
        if (!empty($relatableModel))
            $this->relatableModel = BaseApplication::diGet($relatableModel);
        
        parent::__construct($this->relatableModel);
    }


}