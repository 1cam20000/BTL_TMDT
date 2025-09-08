<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class VendorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Vendor::updateOrCreate(
            ['email' => 'vendor@velstore.com'],
            [
                'name' => 'Vendor',
                'email' => 'vendor@velstore.com',
                'password' => bcrypt('vendor123'),
            ]
        );
    }
}
