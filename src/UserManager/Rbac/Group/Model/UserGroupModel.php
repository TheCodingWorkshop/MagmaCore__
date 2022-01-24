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

namespace MagmaCore\UserManager\Rbac\Group\Model;

use MagmaCore\Base\AbstractBaseModel;
use MagmaCore\UserManager\Rbac\Group\Entity\GroupRoleEntity;

class UserGroupModel extends AbstractBaseModel
{

    /** @var string */
    protected const TABLESCHEMA = 'user_group';
    /** @var string */
    protected const TABLESCHEMAID = 'id';
    /** @var array $fillable - an array of fields that should not be null */
    protected array $fillable = [
        'group_name',
        'group_slug',
        'group_description',
        'created_byid',
    ];


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
        parent::__construct(self::TABLESCHEMA, self::TABLESCHEMAID, NULL);
    }

    /**
     * Guard these IDs from being deleted etc..
     *
     * @return array
     */
    public function guardedID(): array
    {
        return [];
    }

}

