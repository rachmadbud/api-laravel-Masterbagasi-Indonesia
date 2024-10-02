<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * php artisan db:seed --class=AdminUserSeeder
     */
    public function run(): void
    {
        // make super user
        $user = User::create([
            'name' => 'Admin',
            'username' => 'Administrator',
            'email' => 'admin@mail.com',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
        ]);

        // assign super user role
        $user->assignRole('administrator');
    }
}
