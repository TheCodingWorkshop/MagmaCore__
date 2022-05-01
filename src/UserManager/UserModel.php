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

namespace MagmaCore\UserManager;

use MagmaCore\UserManager\Rbac\Role\RoleRelationship;
use MagmaCore\UserManager\Rbac\Role\RoleModel;
use MagmaCore\Auth\Contracts\UserSecurityInterface;
use MagmaCore\Base\AbstractBaseModel;
use MagmaCore\Base\Exception\BaseInvalidArgumentException;
use MagmaCore\Utility\PasswordEncoder;
use MagmaCore\Utility\UtilityTrait;

class UserModel extends AbstractBaseModel implements UserSecurityInterface
{

    use UtilityTrait;

    /** @var string */
    protected const TABLESCHEMA = 'users';
    /** @var string */
    protected const TABLESCHEMAID = 'id';
    /** @var array - field casting */
    protected array $cast = ['firstname' => 'array_json'];
    /* @var array COLUMN_STATUS */
    public const COLUMN_STATUS = ['status' => ['pending', 'active', 'trash', 'lock', '']];


    /** @var array $fillable - an array of fields that should not be null */
    protected array $fillable = [
        'firstname',
        'lastname',
        'email',
        'status',
        'password_hash',
        'created_byid',
        'remote_addr',
    ];
    protected ?string $validatedHashPassword;
    protected ?object $tokenRepository;

    /** @var array - bulk action array properties */
    protected array $unsettableClone = ['id', 'created_at', 'activation_token', 'password_reset_hash'];
    protected array $cloneableKeys = ['firstname', 'lastname', 'email'];

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
        parent::__construct(self::TABLESCHEMA, self::TABLESCHEMAID, UserEntity::class);
        
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

    /**
     * Return an array of column values if table supports the column field
     *
     * @return array
     */
    public function getColumnStatus(): array
    {
        return self::COLUMN_STATUS;
    }

    /**
     * See if a user record already exists with the specified email
     *
     * @param string $email email address to search for
     * @param int|null $ignoreID
     * @return boolean  True if a record already exists with the specified email, false otherwise
     */
    public function emailExists(string $email, int $ignoreID = null): bool
    {
        if (!empty($email)) {
            $result = $this->getRepo()->findObjectBy(['email' => $email], ['*']);
            if ($result) {
                if ($result->id != $ignoreID) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * Return true if the user account is activated. ie. status is set to active
     * returns false otherwise.
     *
     * @param string $email
     * @return boolean
     */
    public function accountActive(string $email): bool
    {
        if (!empty($email)) {
            $result = $this->getRepo()->findObjectBy(['email' => $email], ['status']);
            if ($result) {
                if ($result->status === 'active') {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * Validate the new user password. Using the validate user object
     * Once the password is validated it will then be hash using the
     * passing hash from our traits services
     *
     * @param object $entityCollection - data returning from the user entity filtered and sanitized
     * @param object|null $repository
     * @return self
     */
    public function validatePassword(object $entityCollection, ?object $repository = null): static
    {
        $validate = $this->get('Validate.UserValidate', 'MagmaCore\UserManager\\')->validate($entityCollection, $repository);
        if (empty($validate->errors)) {
            $this->validatedHashPassword = password_hash(
                $entityCollection->all()['password_hash'], 
                constant($this->appSecurity('password_algo')['default']), 
                $this->appSecurity('hash_cost_factor')
            );
            $this->tokenRepository = $repository;

            return $this;
        }
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getNameForSelectField($id)
    {
        return $this->getOtherModel(RoleModel::class)->getNameForSelectField($id);
    }

    /**
     * @return object
     */
    public function role(): object
    {
        return $this->getRelationship(RoleRelationship::class);
    }

    /**
     * Return the user object based on the passed parameter
     *
     * @param integer $userID
     * @return object|null
     */
    public function getUser(int $userID): ?object
    {
        if (empty($userID) || $userID === 0) {
            throw new BaseInvalidArgumentException('Please add a valid user id');
        }

        $user = $this->getRepo()->findObjectBy(['id' => $userID]);
        if ($user) {
            return $user;
        }

        return null;
    }

}
