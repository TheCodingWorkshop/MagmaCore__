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

namespace MagmaCore\System\App\Model;

use App\Model\TagModel;
use App\Model\PostModel;
use App\Model\MessageModel;
use App\Model\CategoryModel;
use MagmaCore\Ticket\TicketModel;
use MagmaCore\UserManager\UserModel;
use MagmaCore\Base\AbstractBaseModel;
use MagmaCore\UserManager\Rbac\Role\RoleModel;
use MagmaCore\Base\Exception\BaseInvalidArgumentException;
use MagmaCore\UserManager\Rbac\Permission\PermissionModel;

class EventModel extends AbstractBaseModel
{

    /** @var string */
    protected const TABLESCHEMA = 'event_log';
    /** @var string */
    protected const TABLESCHEMAID = 'id';
    protected const COLUMN_IDENTIFIER = 'event_log_name';

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

    public function getTrashModel()
    {
        $models = [
            'user' => UserModel::class,
            'role' => RoleModel::class,
            'permission' => PermissionModel::class,
            'post' => PostModel::class,
            'tag' => TagModel::class,
            'category' => CategoryModel::class,
            'ticket' => TicketModel::class,
            'message' => MessageModel::class,
        ];
        $data = [];
        foreach ($models as $model) {
            $object = $this->getOtherModel($model);
            $ch = explode('\\', $model);
            array_push($data, [
                'object' => end(str_replace('Model', '', $ch)), /** the name of the trash object */
                'object_path' => $model, /** the namespace path of the trash object */
                'items_in_trash' => $object->getRepo()->count(['deleted_at' => 1]), /** how many trash items is there */
                'items_id' => $object->getRepo()->findBy(['id', 'created_at'], ['deleted_at' => 1]), /** the id(s) of the trash items */
            ]);
        }

        return $data;


    }


}

