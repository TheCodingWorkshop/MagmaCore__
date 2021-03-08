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

use MagmaCore\Base\BaseModel;

Abstract class AbstractBaseModel extends BaseModel
{ 
    
    /**
     * return an array of ID for which the system will prevent from being
     * deleted
     *
     * @return array
     */
    abstract public function guardedID() : array;

    /**
     * Returns the databae table schema name
     * 
     * @return string
     */
    abstract public function getSchemaID(): string;

    /**
     * Returns the database table schema primary key 
     * 
     * @return string
     */
    abstract public function getSchema(): string;


}
