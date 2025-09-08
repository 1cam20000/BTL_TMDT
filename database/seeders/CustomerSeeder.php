<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Customer::updateOrCreate(
            ['email' => 'customer@velstore.com'],
            [
                'name' => 'Customer',
                'email' => 'customer@velstore.com',
                'password' => bcrypt('customer123'),
            ]
        );
    }
}
