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

namespace MagmaCore\UserManager\Model;

use MagmaCore\UserManager\Schema\UserRoleSchema;
use MagmaCore\Base\AbstractBaseModel;
use MagmaCore\Base\Exception\BaseInvalidArgumentException;
use MagmaCore\DataObjectLayer\DataRelationship\OneToOne;

class UserMetaDataModel extends AbstractBaseModel
{

    /** @var string */
    protected const TABLESCHEMA = 'user_metadata';
    /** @var string */
    protected const TABLESCHEMAID = 'id';
    /** @var object $relationship */
    protected object $relationship;

    /**
     * Main constructor class which passes the relevant information to the
     * base model parent constructor. This allows the repository to fetch the
     * correct information from the database based on the model/entity
     *
     * @return void
     * @throws BaseInvalidArgumentException
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
    public function guardedID(): array
    {
        return [
        ];
    }

    /**
     * Create an relation between the user and role models using the user_role
     * pivot table as the glue between both relationships
     *
     * @return OneToOne
     */
    public function hasRelationship(): OneToOne
    {
        return $this->addRelationship(OneToOne::class)
            ->hasOne(UserMetaDataModel::class)->belongsToOne(UserModel::class)
            ->tablePivot($this, UserRoleSchema::class);
    }

}