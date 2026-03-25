<?php

declare(strict_types=1);

namespace App\Livewire\Settings;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('General settings')]
class General extends Component
{
    public array $settings = [];

    protected array $keys = [
        'business_name' => ['label' => 'Business Name', 'type' => 'string', 'default' => 'Delicious Catering Co.'],
        'business_email' => ['label' => 'Business Email', 'type' => 'string', 'default' => 'info@catering.com'],
        'business_phone' => ['label' => 'Business Phone', 'type' => 'string', 'default' => '+233200000000'],
        'business_address' => ['label' => 'Business Address', 'type' => 'string', 'default' => 'Accra, Ghana'],
        'booking_ref_prefix' => ['label' => 'Booking Reference Prefix', 'type' => 'string', 'default' => 'CAT'],
    ];

    public function mount(): void
    {
        foreach ($this->keys as $key => $config) {
            $setting = Setting::firstOrCreate(
                ['key' => $key],
                [
                    'label' => $config['label'],
                    'type' => $config['type'],
                    'value' => $config['default'],
                    'group' => 'business',
                ]
            );
            $this->settings[$key] = $setting->value;
        }
    }

    public function updateSettings(): void
    {
        $this->validate([
            'settings.business_name' => ['required', 'string', 'max:100'],
            'settings.business_email' => ['required', 'email', 'max:150'],
            'settings.business_phone' => ['required', 'string', 'max:20'],
            'settings.business_address' => ['required', 'string', 'max:255'],
            'settings.booking_ref_prefix' => ['required', 'string', 'max:10'],
        ]);

        foreach ($this->settings as $key => $value) {
            Setting::where('key', $key)->update(['value' => $value]);
        }

        Cache::forget('app_settings');

        $this->dispatch('settings-updated');
    }

    public function render()
    {
        return view('livewire.settings.general');
    }
}
