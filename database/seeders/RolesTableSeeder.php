<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Permission::truncate();
        // Role::truncate();
        // DB::table('role_has_permissions')->truncate();
        // DB::table('model_has_roles')->truncate();
        // DB::table('model_has_permissions')->truncate();

        Role::create(['name' => 'superadmin']);
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'editor']);

        $roleSuperAdmin = Role::findByName('superadmin');
        $roleSuperAdmin->givePermissionTo('users.userIndex');
        $roleSuperAdmin->givePermissionTo('users.create');
        $roleSuperAdmin->givePermissionTo('users.edit');
        $roleSuperAdmin->givePermissionTo('users.delete');



        //php artisan migrate --seed
    }
}
