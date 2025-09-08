<?php

namespace Database\Seeders;

use App\Models\PaymentGateway;
use Illuminate\Database\Seeder;

class PaymentGatewaySeeder extends Seeder
{
    public function run(): void
    {
        $gateways = [
            [
                'name' => 'PayPal',
                'code' => 'paypal',
                'description' => 'PayPal payment gateway',
                'is_active' => true,
            ],
            [
                'name' => 'Stripe',
                'code' => 'stripe',
                'description' => 'Stripe payment gateway',
                'is_active' => true,
            ],
        ];

        foreach ($gateways as $gateway) {
            PaymentGateway::firstOrCreate(['code' => $gateway['code']], $gateway);
        }
    }
}
