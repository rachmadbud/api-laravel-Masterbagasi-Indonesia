<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WorkerUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * php artisan db:seed --class=WorkerUserSeeder
     */
    public function run(): void
    {
        // make super user
        $user = User::create([
            'name' => 'Worker',
            'username' => 'Worker',
            'email' => 'worker@mail.com',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
        ]);

        // assign super user role
        $user->assignRole('worker');
    }
}
