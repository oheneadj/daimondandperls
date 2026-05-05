<?php

declare(strict_types=1);

use App\Enums\SettingType;
use App\Models\Setting;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        $settings = [
            ['key' => 'review_enabled', 'value' => '1', 'type' => SettingType::Boolean, 'label' => 'Review System Enabled'],
            ['key' => 'review_points_reward', 'value' => '25', 'type' => SettingType::Integer, 'label' => 'Points Awarded per Review'],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                ['value' => $setting['value'], 'type' => $setting['type'], 'group' => 'reviews', 'label' => $setting['label']]
            );
        }
    }

    public function down(): void
    {
        Setting::whereIn('key', ['review_enabled', 'review_points_reward'])->delete();
    }
};
