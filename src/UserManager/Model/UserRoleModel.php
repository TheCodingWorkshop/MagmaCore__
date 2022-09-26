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

use MagmaCore\UserManager\Entity\UserRoleEntity;
use MagmaCore\UserManager\Schema\UserRoleSchema;
use MagmaCore\Base\AbstractBaseModel;
use MagmaCore\Base\Exception\BaseInvalidArgumentException;
use MagmaCore\DataObjectLayer\DataRelationship\Relationships\ManyToMany;

class UserRoleModel extends AbstractBaseModel
{

    /** @var string */
    protected const TABLESCHEMA = 'user_role';
    /** @var string */
    protected const TABLESCHEMAID = 'user_id';
    /** @var object $relationship */
    protected object $relationship;
    /** @var string */
    public const FOREIGNKEY = 'user_id';

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
        parent::__construct(self::TABLESCHEMA, self::TABLESCHEMAID, UserRoleEntity::class);
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
     * @param object $controller
     * @return array
     */
    public function getUserRoleID(object $controller): array
    {
        return $controller->flattenArray($this->getRepo()->findBy(['role_id'], [self::TABLESCHEMAID => $controller->thisRouteID()]));
    }

    /**
     * Create an relation between the user and role models using the user_role
     * pivot table as the glue between both relationships
     *
     * @return ManyToMany
     */
    public function hasRelationship(): ManyToMany
    {
        return $this->addRelationship(ManyToMany::class)
            ->hasOne(UserModel::class)->belongsToMany(RoleModel::class)
            ->tablePivot($this, UserRoleSchema::class);
    }

}