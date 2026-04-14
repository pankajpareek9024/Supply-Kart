<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Setting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // Create default super admin
        Admin::updateOrCreate(
            ['email' => 'admin@supplykart.com'],
            [
                'name'     => 'Super Admin',
                'email'    => 'admin@supplykart.com',
                'password' => Hash::make('Admin@123'),
                'role'     => 'super_admin',
            ]
        );

        // Seed default settings
        $defaults = [
            'site_name'         => 'SupplyKart',
            'contact_email'     => 'support@supplykart.com',
            'contact_phone'     => '+91 98765 43210',
            'contact_address'   => '123, Business Hub, Mumbai, India',
            'delivery_charge'   => '90',
            'free_delivery_min' => '999',
        ];

        foreach ($defaults as $key => $value) {
            Setting::firstOrCreate(['key' => $key], ['value' => $value]);
        }

        $this->command->info('Admin seeded: admin@supplykart.com / Admin@123');
    }
}
