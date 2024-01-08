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

    private $userPermissions = [
        'user-view-file-log',
    ];
    public function run()
    {
        $user = User::firstOrCreate(
            ['name' => 'Admin'],
            ['email' => 'admin@admin.com','password' => '12345678'],
        );

        $adminRole = Role::firstOrCreate(['name' => 'Admin']);
        $adminRole->syncPermissions($this->adminPermissions);
        $user->syncPermissions($this->adminPermissions);
        $user->assignRole('Admin');

        $userRole = Role::firstOrCreate(['name' => 'User']);
        $userRole->syncPermissions($this->userPermissions);

    }
}
