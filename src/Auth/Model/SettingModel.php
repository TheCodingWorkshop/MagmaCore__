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

namespace MagmaCore\Auth\Model;

use MagmaCore\Base\AbstractBaseModel;

class SettingModel extends AbstractBaseModel
{ 

    /** @var string */
    protected const TABLESCHEMA = 'settings';
    /** @var string */
    protected const TABLESCHEMAID = 'id';

    /**
     * Main constructor class which passes the relevant information to the 
     * base model parent constructor. This allows the repsitory to fetch the
     * correct information from the database based on the model/entity
     * 
     * @throws BaseInvalidArgumentException
     * @return void
     */
    public function __construct()
    {
        parent::__construct(self::TABLESCHEMA, self::TABLESCHEMAID);
    }

    /**
     * Guard these IDs from being deleted etc..
     *
     * @return array
     */
    public function guardedID() : array
    {
        return [];
    }

    /**
     * Returns the databae table schema name
     * 
     * @return string
     */
    public function getSchemaID(): string
    {
        return self::TABLESCHEMAID;
    }

    /**
     * Returns the database table schema primary key 
     * 
     * @return string
     */
    public function getSchema(): string
    {
        return self::TABLESCHEMA;
    }

}