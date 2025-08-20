<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\User::updateOrCreate(
            ['email' => 'admin@velstore.com'],
            [
                'name' => 'Admin',
                'email' => 'admin@velstore.com',
                'password' => bcrypt('admin123'),
            ]
        );
    }
}
