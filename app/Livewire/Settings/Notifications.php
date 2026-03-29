<?php

declare(strict_types=1);

namespace App\Livewire\Settings;

use App\Models\Setting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Notification settings')]
#[Layout('layouts.admin')]
class Notifications extends Component
{
    public string $notification_preference = 'email';

    public bool $sms_enabled = false;

    public bool $email_enabled = true;

    public function mount(): void
    {
        $this->notification_preference = Auth::user()->notification_preference?->value ?? Auth::user()->notification_preference ?? 'email';

        $smsSetting = Setting::firstOrCreate(
            ['key' => 'sms_enabled'],
            ['label' => 'System SMS Enabled', 'type' => 'boolean', 'value' => '0', 'group' => 'notifications']
        );
        $this->sms_enabled = (bool) $smsSetting->value;

        $emailSetting = Setting::firstOrCreate(
            ['key' => 'email_enabled'],
            ['label' => 'System Email Enabled', 'type' => 'boolean', 'value' => '1', 'group' => 'notifications']
        );
        $this->email_enabled = (bool) $emailSetting->value;
    }

    public function updateNotifications(): void
    {
        $validated = $this->validate([
            'notification_preference' => ['required', 'in:email,sms,both'],
            'sms_enabled' => ['boolean'],
            'email_enabled' => ['boolean'],
        ]);

        $user = Auth::user();
        $user->notification_preference = $validated['notification_preference'];
        $user->save();

        Setting::where('key', 'sms_enabled')->update(['value' => $validated['sms_enabled']]);
        Setting::where('key', 'email_enabled')->update(['value' => $validated['email_enabled']]);

        Cache::forget('app_settings');

        $this->dispatch('toast', type: 'success', message: 'Notification preferences successfully updated.');
    }

    public function render()
    {
        return view('livewire.settings.notifications');
    }
}
