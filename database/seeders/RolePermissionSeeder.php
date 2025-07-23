<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'create post',
            'edit post',
            'delete post',
            'publish post',
        ];

        // ✅ Tambahkan guard_name saat membuat permissions
        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web',
            ]);
        }

        // ✅ Tambahkan guard_name saat membuat roles
        $adminRole = Role::firstOrCreate([
            'name' => 'admin',
            'guard_name' => 'web',
        ]);

        $editorRole = Role::firstOrCreate([
            'name' => 'editor',
            'guard_name' => 'web',
        ]);

        // Assign permission ke role
        $adminRole->syncPermissions(Permission::all());
        $editorRole->syncPermissions(['create post', 'edit post']);

        // Buat user jika belum ada
        $user = User::firstOrCreate([
            'email' => 'admin@example.com',
        ], [
            'name' => 'Super Admin',
            'password' => bcrypt('password'),
        ]);

        // Assign role
        if (!$user->hasRole('admin')) {
            $user->assignRole('admin');
        }
    }
}
