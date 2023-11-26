<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class UsersSeeder extends Seeder
{
    public function run()
    {
        User::create(
            [
                'name' => 'Nicolas Al Ahmar',
                'email' => 'nicolasalahmar@gmail.com',
                'password' => '12345678',
                'role' => 'user'
            ]
        );
    }
}
