<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UsersSeeder extends Seeder
{
    private $adminPermissions = [
        'admin-view-log',
        'admin-view-file-log',
    ];
    public function run()
    {
        $user = User::firstOrCreate(

                ['name' => 'Admin',
                'email' => 'admin@admin.com','password' => '12345678'],

        );

        $adminRole = Role::firstOrCreate(['name' => 'Admin']);

        $user->syncPermissions($this->adminPermissions);
        $adminRole->syncPermissions($this->adminPermissions);
        $user->assignRole('Admin');



    }
}
