<?php

namespace Database\Seeders;

use App\Models\DeliveryBoy;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DeliveryBoySeeder extends Seeder
{
    public function run(): void
    {
        DeliveryBoy::updateOrCreate(
            ['phone' => '9876543210'],
            [
                'name'      => 'John Delivery',
                'email'     => 'delivery@supplykart.com',
                'phone'     => '9876543210',
                'password'  => Hash::make('Delivery@123'),
                'address'   => 'Logistics Hub, Mumbai',
                'is_active' => true,
            ]
        );
    }
}
