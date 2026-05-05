<?php

use App\Enums\SettingType;
use App\Models\Setting;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        $settings = [
            ['key' => 'loyalty_enabled',           'value' => '1',   'type' => SettingType::Boolean, 'label' => 'Loyalty Programme Enabled'],
            ['key' => 'loyalty_points_per_ghc',    'value' => '1',   'type' => SettingType::Integer, 'label' => 'Points Earned per GH₵1 Spent'],
            ['key' => 'loyalty_referral_bonus',    'value' => '50',  'type' => SettingType::Integer, 'label' => 'Referral Bonus Points'],
            ['key' => 'loyalty_redemption_rate',   'value' => '100', 'type' => SettingType::Integer, 'label' => 'Points Required per GH₵1 Discount'],
            ['key' => 'loyalty_max_redemption_pct', 'value' => '20',  'type' => SettingType::Integer, 'label' => 'Max Order % Payable with Points'],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                ['value' => $setting['value'], 'type' => $setting['type'], 'group' => 'loyalty', 'label' => $setting['label']]
            );
        }
    }

    public function down(): void
    {
        Setting::whereIn('key', [
            'loyalty_enabled',
            'loyalty_points_per_ghc',
            'loyalty_referral_bonus',
            'loyalty_redemption_rate',
            'loyalty_max_redemption_pct',
        ])->delete();
    }
};
