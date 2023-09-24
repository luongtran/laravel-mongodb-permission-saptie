<?php

namespace LuongTran\Mongodb\Permissions\Models;

use Moloquent\Eloquent\Model;
use Spatie\Permission\Contracts\Permission as PermissionContract;
use Jenssegers\Mongodb\Eloquent\Relations\BelongsToMany;
use Spatie\Permission\Exceptions\PermissionDoesNotExist;

class Permission extends Model implements PermissionContract
{
    /**
     * A permission can be applied to roles.
     *
     * @return \Illuminate\Support\Collection $roles
     */
    public function roles():  \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        // return $this->getPermissions(
        //     config('laravel-permission.models.role')
        // );
        // return $this->belongsToMany(
        //     config('permission.models.role'),
        //     config('permission.table_names.role_has_permissions'),
        //     PermissionRegistrar::$pivotPermission,
        //     PermissionRegistrar::$pivotRole
        // );

        return $this->embedsMany(
            config('laravel-permission.table_names.role_has_permissions')
        );
    }

    /**
     * A role may be assigned to various users.
     *
     * @return \Illuminate\Support\Collection $users
     */
    public function users()
    {
        return $this->getPermissions(
            config('auth.model') ?: config('auth.providers.users.model')
        );
    }

    /**
     * Find a permission by its name.
     *
     * @param string $name
     *
     * @throws PermissionDoesNotExist
     */
    public static function findByName($name)
    {
        $permission = static::where('name', $name)->first();

        if (! $permission) {
            throw new PermissionDoesNotExist();
        }

        return $permission;
    }

    protected function getPermissions($model)
    {
        return (new $model)->where('permissions.id', $this->getAttribute($this->primaryKey));
    }
}
