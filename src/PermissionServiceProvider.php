<?php

namespace LuongTran\Mongodb\Permissions;

use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\AliasLoader;
use LuongTran\Mongodb\Permissions\Contracts\EmbedPermission as EmbedPermissionContract;
use LuongTran\Mongodb\Permissions\Contracts\EmbedRole as EmbedRoleContract;
use LuongTran\Mongodb\Permissions\Models\Permission;
use LuongTran\Mongodb\Permissions\Models\Role;
use LuongTran\Mongodb\Permissions\Traits\HasPermissions;
use LuongTran\Mongodb\Permissions\Traits\HasRoles;

class PermissionServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     */
    public function boot(PermissionRegistrar $permissionLoader)
    {
        $permissionLoader->registerPermissions();
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->registerAliasLoaders();
        $this->registerEmbedModelBindings();
    }

    /**
     * Extends Traits class
     */
    protected function registerAliasLoaders()
    {
        $loader = AliasLoader::getInstance();
        $loader->alias('Spatie\Permission\Models\Permission', Permission::class);
        $loader->alias('Spatie\Permission\Models\Role', Role::class);
        $loader->alias('Spatie\Permission\Traits\HasPermissions', HasPermissions::class);
        $loader->alias('Spatie\Permission\Traits\HasRoles', HasRoles::class);
    }

    /**
     * Bind the Permission and Role model into the IoC.
     */
    protected function registerEmbedModelBindings()
    {
        $configTables = $this->app->config['laravel-permission.table_names'];

        $this->app->bind(EmbedPermissionContract::class, $configTables['role_has_permissions']);
        $this->app->bind(EmbedPermissionContract::class, $configTables['user_has_permissions']);
        $this->app->bind(EmbedRoleContract::class, $configTables['user_has_roles']);
    }
}
