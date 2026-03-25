<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            [
                'key' => 'business_name',
                'value' => 'Delicious Catering Co.',
                'type' => 'string',
                'label' => 'Business Name',
                'group' => 'business',
            ],
            [
                'key' => 'business_phone',
                'value' => '+233200000000',
                'type' => 'string',
                'label' => 'Business Phone',
                'group' => 'business',
            ],
            [
                'key' => 'business_email',
                'value' => 'info@catering.com',
                'type' => 'string',
                'label' => 'Business Email',
                'group' => 'business',
            ],
            [
                'key' => 'payment_gateway',
                'value' => 'paystack',
                'type' => 'string',
                'label' => 'Active Payment Gateway',
                'group' => 'payment',
            ],
            [
                'key' => 'paystack_public_key',
                'value' => '',
                'type' => 'string',
                'label' => 'Paystack Public Key',
                'group' => 'payment',
            ],
            [
                'key' => 'paystack_secret_key',
                'value' => '',
                'type' => 'string',
                'label' => 'Paystack Secret Key',
                'group' => 'payment',
            ],
            [
                'key' => 'sms_enabled',
                'value' => '0',
                'type' => 'boolean',
                'label' => 'Enable SMS Notifications',
                'group' => 'notifications',
            ],
            [
                'key' => 'email_enabled',
                'value' => '1',
                'type' => 'boolean',
                'label' => 'Enable Email Notifications',
                'group' => 'notifications',
            ],
            [
                'key' => 'booking_ref_prefix',
                'value' => 'CAT',
                'type' => 'string',
                'label' => 'Booking Reference Prefix',
                'group' => 'business',
            ],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(['key' => $setting['key']], $setting);
        }
    }
}
