<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Settings;

use App\Models\Setting;
use App\Traits\HasAdminAuthorization;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Queue;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.admin')]
#[Title('Application Settings')]
class AdminSettings extends Component
{
    use HasAdminAuthorization;
    use WithFileUploads;

    #[Url]
    public string $tab = 'company';

    // Business Info
    public ?string $business_name = '';

    public ?string $business_address = '';

    public ?string $business_phone = '';

    public ?string $business_whatsapp = '';

    public ?string $business_email = '';

    public $business_logo;

    public ?string $current_logo_path = null;

    // Payment Gateway
    public ?string $paystack_public_key = '';

    public ?string $paystack_secret_key = '';

    // Notification Preferences
    public bool $email_notifications = false;

    public bool $sms_notifications = false;

    // Delivery Locations
    /** @var array<int, string> */
    public array $delivery_locations = [];

    public bool $locationModalOpen = false;

    public ?int $editingLocationIndex = null;

    public string $locationName = '';

    // Bank Details
    // Event Booking Settings
    public int $event_lead_days = 0;

    public ?string $bank_name = '';

    public ?string $account_name = '';

    public ?string $account_number = '';

    public ?string $branch_code = '';

    public function mount(): void
    {
        $this->authorizePermission('manage_settings');
        $settings = Setting::all()->keyBy('key');

        $this->business_name = $settings->get('business_name')?->value ?? '';
        $this->business_address = $settings->get('business_address')?->value ?? '';
        $this->business_phone = $settings->get('business_phone')?->value ?? '';
        $this->business_whatsapp = $settings->get('business_whatsapp')?->value ?? '';
        $this->business_email = $settings->get('business_email')?->value ?? '';
        $this->current_logo_path = $settings->get('business_logo')?->value;

        $this->paystack_public_key = $settings->get('paystack_public_key')?->value ?? '';
        $this->paystack_secret_key = $settings->get('paystack_secret_key')?->value ?? '';

        $this->email_notifications = (bool) ($settings->get('email_enabled')?->value ?? false);
        $this->sms_notifications = (bool) ($settings->get('sms_enabled')?->value ?? false);

        $this->event_lead_days = (int) ($settings->get('event_lead_days')?->value ?? 0);

        $this->delivery_locations = $settings->get('delivery_locations')?->value ?? [];

        $this->bank_name = $settings->get('bank_name')?->value ?? '';
        $this->account_name = $settings->get('account_name')?->value ?? '';
        $this->account_number = $settings->get('account_number')?->value ?? '';
        $this->branch_code = $settings->get('branch_code')?->value ?? '';
    }

    public function setTab(string $tab): void
    {
        $this->tab = $tab;
    }

    public function saveBusinessInfo(): void
    {
        $this->validate([
            'business_name' => 'required|string|max:255',
            'business_address' => 'required|string|max:500',
            'business_phone' => 'required|string|max:20',
            'business_whatsapp' => 'nullable|string|max:20',
            'business_email' => 'required|email|max:255',
            'business_logo' => 'nullable|image|max:2048', // 2MB Max
        ]);

        $logo = $this->business_logo;
        if ($logo instanceof \Illuminate\Http\UploadedFile) {
            $path = $logo->store('logos', 'public');
            $this->updateSetting('business_logo', $path, \App\Enums\SettingType::String, 'business');
            $this->current_logo_path = $path;
            $this->business_logo = null;
        }

        $this->updateSetting('business_name', $this->business_name, \App\Enums\SettingType::String, 'business');
        $this->updateSetting('business_address', $this->business_address, \App\Enums\SettingType::String, 'business');
        $this->updateSetting('business_phone', $this->business_phone, \App\Enums\SettingType::String, 'business');
        $this->updateSetting('business_whatsapp', $this->business_whatsapp, \App\Enums\SettingType::String, 'business');
        $this->updateSetting('business_email', $this->business_email, \App\Enums\SettingType::String, 'business');

        $this->dispatch('banner', style: 'success', message: 'Business information updated successfully.');
    }

    public function savePaymentSettings(): void
    {
        $this->validate([
            'paystack_public_key' => 'required|string',
            'paystack_secret_key' => 'required|string',
        ]);

        $this->updateSetting('paystack_public_key', $this->paystack_public_key, \App\Enums\SettingType::String, 'payment');
        $this->updateSetting('paystack_secret_key', $this->paystack_secret_key, \App\Enums\SettingType::String, 'payment');

        $this->dispatch('banner', style: 'success', message: 'Payment gateway credentials updated.');
    }

    public function openAddLocationModal(): void
    {
        $this->editingLocationIndex = null;
        $this->locationName = '';
        $this->locationModalOpen = true;
    }

    public function openEditLocationModal(int $index): void
    {
        $this->editingLocationIndex = $index;
        $this->locationName = $this->delivery_locations[$index] ?? '';
        $this->locationModalOpen = true;
    }

    public function saveLocationModal(): void
    {
        $this->validate([
            'locationName' => ['required', 'string', 'max:100'],
        ], [
            'locationName.required' => 'Please enter a location name.',
        ]);

        if ($this->editingLocationIndex !== null) {
            $this->delivery_locations[$this->editingLocationIndex] = trim($this->locationName);
        } else {
            $this->delivery_locations[] = trim($this->locationName);
        }

        $this->persistDeliveryLocations();
        $this->locationModalOpen = false;
        $this->locationName = '';
        $this->editingLocationIndex = null;

        $this->dispatch('banner', style: 'success', message: 'Delivery location saved.');
    }

    public function removeDeliveryLocation(int $index): void
    {
        array_splice($this->delivery_locations, $index, 1);
        $this->delivery_locations = array_values($this->delivery_locations);
        $this->persistDeliveryLocations();

        $this->dispatch('banner', style: 'success', message: 'Delivery location removed.');
    }

    public function moveLocationUp(int $index): void
    {
        if ($index <= 0) {
            return;
        }

        [$this->delivery_locations[$index - 1], $this->delivery_locations[$index]] =
            [$this->delivery_locations[$index], $this->delivery_locations[$index - 1]];

        $this->persistDeliveryLocations();
    }

    public function moveLocationDown(int $index): void
    {
        if ($index >= count($this->delivery_locations) - 1) {
            return;
        }

        [$this->delivery_locations[$index], $this->delivery_locations[$index + 1]] =
            [$this->delivery_locations[$index + 1], $this->delivery_locations[$index]];

        $this->persistDeliveryLocations();
    }

    private function persistDeliveryLocations(): void
    {
        $this->updateSetting('delivery_locations', json_encode(array_values($this->delivery_locations)), \App\Enums\SettingType::Json, 'booking');
    }

    public function saveEventSettings(): void
    {
        $this->validate([
            'event_lead_days' => ['required', 'integer', 'min:0', 'max:28'],
        ], [
            'event_lead_days.max' => 'Maximum lead time is 4 weeks (28 days).',
        ]);

        $this->updateSetting('event_lead_days', $this->event_lead_days, \App\Enums\SettingType::Integer, 'event');

        $this->dispatch('banner', style: 'success', message: 'Event booking settings updated.');
    }

    public function saveBankDetails(): void
    {
        $this->validate([
            'bank_name' => 'required|string|max:255',
            'account_name' => 'required|string|max:255',
            'account_number' => 'required|string|max:50',
            'branch_code' => 'nullable|string|max:20',
        ]);

        $this->updateSetting('bank_name', $this->bank_name, \App\Enums\SettingType::String, 'bank');
        $this->updateSetting('account_name', $this->account_name, \App\Enums\SettingType::String, 'bank');
        $this->updateSetting('account_number', $this->account_number, \App\Enums\SettingType::String, 'bank');
        $this->updateSetting('branch_code', $this->branch_code, \App\Enums\SettingType::String, 'bank');

        $this->dispatch('banner', style: 'success', message: 'Company bank details updated successfully.');
    }

    public function updatedEmailNotifications($value): void
    {
        $this->updateSetting('email_enabled', $value, \App\Enums\SettingType::Boolean, 'notifications');
        $this->dispatch('banner', style: 'success', message: 'Notification preferences updated successfully.');
    }

    public function updatedSmsNotifications($value): void
    {
        $this->updateSetting('sms_enabled', $value, \App\Enums\SettingType::Boolean, 'notifications');
        $this->dispatch('banner', style: 'success', message: 'Notification preferences updated successfully.');
    }

    private function updateSetting(string $key, mixed $value, \App\Enums\SettingType $type = \App\Enums\SettingType::String, ?string $group = null): void
    {
        Setting::updateOrCreate(
            ['key' => $key],
            [
                'value' => $value,
                'type' => $type,
                'group' => $group,
                'label' => str($key)->replace('_', ' ')->title()->value(),
            ]
        );
    }

    #[Computed]
    public function systemStats(): array
    {
        return [
            'server' => [
                'php' => PHP_VERSION,
                'os' => PHP_OS_FAMILY,
                'server' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown',
                'uptime' => $this->getUptime(),
            ],
            'database' => [
                'connection' => config('database.default'),
                'driver' => $driver = DB::connection()->getDriverName(),
                'version' => match ($driver) {
                    'sqlite' => DB::select('select sqlite_version() as version')[0]->version ?? 'Unknown',
                    'mysql', 'pgsql' => DB::select('select version() as version')[0]->version ?? 'Unknown',
                    default => 'Unknown',
                },
                'status' => 'Healthy',
            ],
            'queue' => [
                'driver' => config('queue.default'),
                'pending' => Queue::size(),
                'failed' => DB::table('failed_jobs')->count(),
            ],
            'app' => [
                'version' => App::version(),
                'env' => App::environment(),
                'debug' => config('app.debug'),
                'url' => config('app.url'),
            ],
        ];
    }

    private function getUptime(): string
    {
        if (PHP_OS_FAMILY === 'Linux') {
            $uptime = @file_get_contents('/proc/uptime');
            if ($uptime) {
                $uptimeSeconds = (float) $uptime;
                $days = (int) floor($uptimeSeconds / 86400);
                $hours = (int) floor(($uptimeSeconds / 3600) % 24);

                return (string) $days.'d '.(string) $hours.'h';
            }
        }

        return 'N/A';
    }

    public function render(): View
    {
        return view('livewire.admin.settings.admin-settings');
    }
}
