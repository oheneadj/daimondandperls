<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // ── Business ────────────────────────────────────────────────────
            [
                'key' => 'business_name',
                'value' => 'Diamonds & Pearls Catering Services',
                'type' => 'string',
                'label' => 'Business Name',
                'group' => 'business',
            ],
            [
                'key' => 'business_phone',
                'value' => '+233244203181',
                'type' => 'string',
                'label' => 'Business Phone',
                'group' => 'business',
            ],
            [
                'key' => 'business_whatsapp',
                'value' => '233244203181',
                'type' => 'string',
                'label' => 'Business WhatsApp Number (no +)',
                'group' => 'business',
            ],
            [
                'key' => 'business_email',
                'value' => 'info@diamondsandpearlsgh.com',
                'type' => 'string',
                'label' => 'Business Email',
                'group' => 'business',
            ],
            [
                'key' => 'business_address',
                'value' => 'Diamonds and Pearls Kitchen, Gbawe, Accra, Ghana',
                'type' => 'string',
                'label' => 'Business Address',
                'group' => 'business',
            ],
            [
                'key' => 'booking_ref_prefix',
                'value' => 'DPC',
                'type' => 'string',
                'label' => 'Booking Reference Prefix',
                'group' => 'business',
            ],

            // ── Payment ─────────────────────────────────────────────────────
            [
                'key' => 'active_payment_gateway',
                'value' => 'transflow',
                'type' => 'string',
                'label' => 'Active Payment Gateway',
                'group' => 'payment',
            ],

            // ── Notifications ───────────────────────────────────────────────
            [
                'key' => 'sms_enabled',
                'value' => '1',
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

            // ── Social ───────────────────────────────────────────────────────
            [
                'key' => 'social_facebook',
                'value' => '',
                'type' => 'string',
                'label' => 'Facebook URL',
                'group' => 'social',
            ],
            [
                'key' => 'social_instagram',
                'value' => '',
                'type' => 'string',
                'label' => 'Instagram URL',
                'group' => 'social',
            ],
            [
                'key' => 'social_tiktok',
                'value' => '',
                'type' => 'string',
                'label' => 'TikTok URL',
                'group' => 'social',
            ],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(['key' => $setting['key']], $setting);
        }
    }
}
