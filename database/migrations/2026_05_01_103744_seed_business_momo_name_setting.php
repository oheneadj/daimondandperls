<?php

use App\Enums\SettingType;
use App\Models\Setting;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Setting::updateOrCreate(
            ['key' => 'business_momo_name'],
            [
                'value' => '',
                'type' => SettingType::String,
                'group' => 'payment',
                'label' => 'Business MoMo Account Name',
            ]
        );
    }

    public function down(): void
    {
        Setting::where('key', 'business_momo_name')->delete();
    }
};
