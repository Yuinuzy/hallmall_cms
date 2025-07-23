<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //create user
        $user = User::create([
            'name'      => 'Muhammad Azis S',
            'email'     => 'it@hpai.id',
            'password'  => Hash::make('Hpai1234'),
        ]);

        //get all permissions
        $permissions = Permission::all();

        //get role admin
        $role = Role::find(1);

        //assign permission to role
        $role->syncPermissions($permissions);

        //assign role to user
        $user->assignRole($role);
    }
}
