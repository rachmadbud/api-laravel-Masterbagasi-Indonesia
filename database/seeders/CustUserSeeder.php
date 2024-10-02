<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CustUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * php artisan db:seed --class=CustUserSeeder
     */
    public function run(): void
    {
        // $user = User::create([
        //     'name' => 'Cust',
        //     'username' => 'Cust',
        //     'email' => 'cust@mail.com',
        //     'password' => bcrypt('password'),
        //     'email_verified_at' => now(),
        // ]);
        $user = User::create([
            'name' => 'Cust 123',
            'username' => 'Cust123',
            'email' => 'cust123@mail.com',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
        ]);

        // assign super user role
        $user->assignRole('customer');
    }
}
