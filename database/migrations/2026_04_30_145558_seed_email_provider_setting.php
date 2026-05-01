<?php

use App\Enums\SettingType;
use App\Models\Setting;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Setting::updateOrCreate(
            ['key' => 'email_primary_provider'],
            [
                'value' => 'smtp',
                'type' => SettingType::String,
                'group' => 'email',
                'label' => 'Email Primary Provider',
            ]
        );
    }

    public function down(): void
    {
        Setting::where('key', 'email_primary_provider')->delete();
    }
};
