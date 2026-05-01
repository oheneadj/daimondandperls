<?php

use App\Enums\SettingType;
use App\Models\Setting;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Setting::updateOrCreate(
            ['key' => 'payment_mode'],
            [
                'value' => 'online',
                'type' => SettingType::String,
                'group' => 'payment',
                'label' => 'Payment Mode',
            ]
        );

        Setting::updateOrCreate(
            ['key' => 'business_momo_network'],
            [
                'value' => '',
                'type' => SettingType::String,
                'group' => 'payment',
                'label' => 'Business MoMo Network',
            ]
        );

        Setting::updateOrCreate(
            ['key' => 'business_momo_number'],
            [
                'value' => '',
                'type' => SettingType::String,
                'group' => 'payment',
                'label' => 'Business MoMo Number',
            ]
        );
    }

    public function down(): void
    {
        Setting::whereIn('key', ['payment_mode', 'business_momo_network', 'business_momo_number'])->delete();
    }
};
