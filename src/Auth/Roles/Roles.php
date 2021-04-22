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

namespace MagmaCore\Auth\Roles;

use MagmaCore\Auth\Roles\RolesInterface;
use App\Model\RolePermissionModel;
use App\Model\UserRoleModel;

class Roles implements RolesInterface
{

    /** @var array */
    protected array $roles = [];
    /** @var array */
    protected array $permissions = [];

    /**
     * This method uses a JOIN to combine the user_role and the roles tables to collect
     * the roles associated with the current user's ID. Each role is then populated with
     * its corresponding permissions with a call to the Role::class method
     *
     * @param int $userID
     * @return void
     */
    public function initRoles(int $userID)
    {
        $this->roles = [];
        // t1 = user_role table [user_id, role_id]
        // t2 = roles table [role_name, id]
        $sqlQuery = "SELECT t1.role_id, t2.role_name FROM user_role as t1 JOIN roles as t2 ON t1.role_id = t2.id WHERE t1.user_id = :user_id";
        $row = (new UserRoleModel())
        ->getRepo()
        ->getEm()
        ->getCrud()
        ->rawQuery($sqlQuery, ['user_id' => $userID], 'fetch');
        if ($row) {
            $row = (array)$row;
            $this->roles[$row['role_name']] = $this->getRolePermissions($row['role_id']);
        }
    }

    /**
     * The getRolePerms method creates a new role object based on a specfic role ID, and then
     * uses a JSON clause to combine the role_permission and permission_name tables. for each
     * permission associated with the given role, the name is stored as the key and its value
     * is set to true.
     *
     * @param int $roleID
     * @return Roles
     */
    public function getRolePermissions(int $roleID)
    {
        // t1 = role_permission [permission_id, role_id]
        // t2 = permission [permission_name, id]
        $sqlQuery = "SELECT t2.permission_name FROM role_permission as t1 JOIN permissions as t2 ON t1.permission_id = t2.id WHERE t1.role_id = :role_id";
        $row = (new RolePermissionModel())
        ->getRepo()
        ->getEm()
        ->getCrud()
        ->rawQuery($sqlQuery, ['role_id' => $roleID], 'fetch');
        if ($row) {
            $row = (array)$row;
            $role = new Roles();
            $role->permissions[$row['permission_name']] = true;
            return $role;
        }
    }

    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * @inheritdoc
     *
     * @param $permissionName
     * @return bool
     */
    public function hasPrivilege($permissionName) : bool
    { 
        foreach ($this->roles as $role) {
            if ($role->hasPermission($permissionName)) {
                return true;
            }
        }
        return false;
    }

    /**
     * HasPermission method accepts a permission name and returns the value based on the 
     * current object
     *
     * @param string $permission
     * @return bool
     */
    public function hasPermission($permission) : bool
    { 
        return isset($this->permissions[$permission]);
    }
    
    /**
     * Check if a user has a specific role
     *
     * @param string $roleName
     * @return boolean
     */
    public function hasRole($roleName) : bool
    { 
        return (isset($this->roles[$roleName]) ? $this->roles[$roleName] : '');
    }


}
