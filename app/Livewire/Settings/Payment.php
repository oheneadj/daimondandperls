<?php

declare(strict_types=1);

namespace App\Livewire\Settings;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Payment settings')]
class Payment extends Component
{
    public array $settings = [];

    protected array $keys = [
        'payment_gateway' => ['label' => 'Active Payment Gateway', 'type' => 'string', 'default' => 'paystack'],
        'paystack_public_key' => ['label' => 'Paystack Public Key', 'type' => 'string', 'default' => ''],
        'paystack_secret_key' => ['label' => 'Paystack Secret Key', 'type' => 'string', 'default' => ''],
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
                    'group' => 'payment',
                ]
            );
            $this->settings[$key] = $setting->value;
        }
    }

    public function updateSettings(): void
    {
        $this->validate([
            'settings.payment_gateway' => ['required', 'string', 'in:paystack,manual'],
            'settings.paystack_public_key' => ['nullable', 'string', 'max:100'],
            'settings.paystack_secret_key' => ['nullable', 'string', 'max:100'],
        ]);

        foreach ($this->settings as $key => $value) {
            Setting::where('key', $key)->update(['value' => $value]);
        }

        Cache::forget('app_settings');

        $this->dispatch('settings-updated');
    }

    public function render()
    {
        return view('livewire.settings.payment');
    }
}
