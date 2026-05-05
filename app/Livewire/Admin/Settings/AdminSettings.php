<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Settings;

use App\Enums\SettingType;
use App\Jobs\OptimiseImage;
use App\Models\Setting;
use App\Traits\HasAdminAuthorization;
use Illuminate\Contracts\View\View;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
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

    // ── Business Info ─────────────────────────────────────────────────────────

    public ?string $business_name = '';

    public ?string $business_address = '';

    public ?string $business_phone = '';

    public ?string $business_whatsapp = '';

    public ?string $business_email = '';

    public $business_logo;

    public ?string $current_logo_path = null;

    // ── Bank Details ──────────────────────────────────────────────────────────

    public ?string $bank_name = '';

    public ?string $account_name = '';

    public ?string $account_number = '';

    public ?string $branch_code = '';

    // ── Social Media ──────────────────────────────────────────────────────────

    public ?string $social_facebook = '';

    public ?string $social_instagram = '';

    public ?string $social_tiktok = '';

    // ── Payment ───────────────────────────────────────────────────────────────

    /** Which payment gateway is active: 'transflow' or 'moolre' */
    public string $active_payment_gateway = 'transflow';

    /** Whether payment is processed online via gateway or offline/manually */
    public string $payment_mode = 'online';

    public string $business_momo_network = '';

    public string $business_momo_number = '';

    public string $business_momo_name = '';

    /** Which SMS provider is primary: 'gaintsms' or 'mnotify' */
    public string $sms_primary_provider = 'gaintsms';

    /** Which email provider is primary: 'smtp' */
    public string $email_primary_provider = 'smtp';

    // ── Notifications ─────────────────────────────────────────────────────────

    public bool $email_notifications = false;

    public bool $sms_notifications = false;

    public string $notification_preference = 'email';

    // ── Loyalty ───────────────────────────────────────────────────────────────

    public bool $loyalty_enabled = true;

    public int $loyalty_points_per_ghc = 1;

    public int $loyalty_referral_bonus = 50;

    public int $loyalty_redemption_rate = 100;

    public int $loyalty_max_redemption_pct = 20;

    // ── Reviews ───────────────────────────────────────────────────────────────

    public bool $review_enabled = true;

    public int $review_points_reward = 25;

    // ── Booking / Delivery ────────────────────────────────────────────────────

    /** @var array<int, string> */
    public array $delivery_locations = [];

    public bool $locationModalOpen = false;

    public ?int $editingLocationIndex = null;

    public string $locationName = '';

    public int $event_lead_days = 0;

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

        $this->bank_name = $settings->get('bank_name')?->value ?? '';
        $this->account_name = $settings->get('account_name')?->value ?? '';
        $this->account_number = $settings->get('account_number')?->value ?? '';
        $this->branch_code = $settings->get('branch_code')?->value ?? '';

        $this->social_facebook = $settings->get('social_facebook')?->value ?? '';
        $this->social_instagram = $settings->get('social_instagram')?->value ?? '';
        $this->social_tiktok = $settings->get('social_tiktok')?->value ?? '';

        $this->active_payment_gateway = $settings->get('active_payment_gateway')?->value ?? 'transflow';
        $this->payment_mode = $settings->get('payment_mode')?->value ?? 'online';
        $this->business_momo_network = $settings->get('business_momo_network')?->value ?? '';
        $this->business_momo_number = $settings->get('business_momo_number')?->value ?? '';
        $this->business_momo_name = $settings->get('business_momo_name')?->value ?? '';
        $this->sms_primary_provider = $settings->get('sms_primary_provider')?->value ?? 'gaintsms';
        $this->email_primary_provider = $settings->get('email_primary_provider')?->value ?? 'smtp';

        $this->email_notifications = (bool) ($settings->get('email_enabled')?->value ?? false);
        $this->sms_notifications = (bool) ($settings->get('sms_enabled')?->value ?? false);
        $this->notification_preference = Auth::user()->notification_preference?->value ?? 'email';

        $this->delivery_locations = $settings->get('delivery_locations')?->value ?? [];
        $this->event_lead_days = (int) ($settings->get('event_lead_days')?->value ?? 0);

        $this->review_enabled = (bool) ($settings->get('review_enabled')?->value ?? true);
        $this->review_points_reward = (int) ($settings->get('review_points_reward')?->value ?? 25);

        $this->loyalty_enabled = (bool) ($settings->get('loyalty_enabled')?->value ?? true);
        $this->loyalty_points_per_ghc = (int) ($settings->get('loyalty_points_per_ghc')?->value ?? 1);
        $this->loyalty_referral_bonus = (int) ($settings->get('loyalty_referral_bonus')?->value ?? 50);
        $this->loyalty_redemption_rate = (int) ($settings->get('loyalty_redemption_rate')?->value ?? 100);
        $this->loyalty_max_redemption_pct = (int) ($settings->get('loyalty_max_redemption_pct')?->value ?? 20);
    }

    public function setTab(string $tab): void
    {
        $this->tab = $tab;
    }

    // ── Savers ────────────────────────────────────────────────────────────────

    public function saveBusinessInfo(): void
    {
        $this->validate([
            'business_name' => 'required|string|max:255',
            'business_address' => 'required|string|max:500',
            'business_phone' => 'required|string|max:20',
            'business_whatsapp' => 'nullable|string|max:20',
            'business_email' => 'required|email|max:255',
            'business_logo' => 'nullable|image|max:2048',
        ]);

        if ($this->business_logo instanceof UploadedFile) {
            $path = $this->business_logo->store('logos', 'public');
            $this->updateSetting('business_logo', $path, SettingType::String, 'business');
            $this->current_logo_path = $path;
            $this->business_logo = null;

            // I dispatch after the setting row exists so the job can update it by ID
            $logoSetting = Setting::firstWhere('key', 'business_logo');
            if ($logoSetting) {
                OptimiseImage::dispatch(
                    disk: 'public',
                    path: (string) $path,
                    modelClass: Setting::class,
                    modelId: $logoSetting->id,
                    modelColumn: 'value',
                );
            }
        }

        $this->updateSetting('business_name', $this->business_name, SettingType::String, 'business');
        $this->updateSetting('business_address', $this->business_address, SettingType::String, 'business');
        $this->updateSetting('business_phone', $this->business_phone, SettingType::String, 'business');
        $this->updateSetting('business_whatsapp', $this->business_whatsapp, SettingType::String, 'business');
        $this->updateSetting('business_email', $this->business_email, SettingType::String, 'business');

        $this->dispatch('banner', style: 'success', message: 'Business information updated successfully.');
    }

    public function saveBankDetails(): void
    {
        $this->validate([
            'bank_name' => 'required|string|max:255',
            'account_name' => 'required|string|max:255',
            'account_number' => 'required|string|max:50',
            'branch_code' => 'nullable|string|max:20',
        ]);

        $this->updateSetting('bank_name', $this->bank_name, SettingType::String, 'bank');
        $this->updateSetting('account_name', $this->account_name, SettingType::String, 'bank');
        $this->updateSetting('account_number', $this->account_number, SettingType::String, 'bank');
        $this->updateSetting('branch_code', $this->branch_code, SettingType::String, 'bank');

        $this->dispatch('banner', style: 'success', message: 'Bank details updated successfully.');
    }

    public function saveSocialLinks(): void
    {
        $this->validate([
            'social_facebook' => 'nullable|url|max:255',
            'social_instagram' => 'nullable|url|max:255',
            'social_tiktok' => 'nullable|url|max:255',
        ]);

        $this->updateSetting('social_facebook', $this->social_facebook, SettingType::String, 'social');
        $this->updateSetting('social_instagram', $this->social_instagram, SettingType::String, 'social');
        $this->updateSetting('social_tiktok', $this->social_tiktok, SettingType::String, 'social');

        $this->dispatch('banner', style: 'success', message: 'Social media links updated.');
    }

    public function savePaymentGateway(): void
    {
        $this->validate([
            'active_payment_gateway' => 'required|in:transflow,moolre',
        ]);

        $this->updateSetting('active_payment_gateway', $this->active_payment_gateway, SettingType::String, 'payment');

        $this->dispatch('banner', style: 'success', message: 'Active payment gateway updated.');
    }

    public function savePaymentMode(): void
    {
        $this->validate([
            'payment_mode' => 'required|in:online,offline',
        ]);

        $this->updateSetting('payment_mode', $this->payment_mode, SettingType::String, 'payment');

        $this->dispatch('banner', style: 'success', message: 'Payment mode updated.');
    }

    public function saveBusinessMomoDetails(): void
    {
        $this->validate([
            'business_momo_network' => 'required|string|max:50',
            'business_momo_number' => ['required', 'regex:/^(?:\+233|0)\d{9}$/'],
            'business_momo_name' => 'required|string|max:100',
        ]);

        $this->updateSetting('business_momo_network', $this->business_momo_network, SettingType::String, 'payment');
        $this->updateSetting('business_momo_number', $this->business_momo_number, SettingType::String, 'payment');
        $this->updateSetting('business_momo_name', $this->business_momo_name, SettingType::String, 'payment');

        $this->dispatch('banner', style: 'success', message: 'Business MoMo details updated.');
    }

    public function saveSmsProvider(): void
    {
        $this->validate([
            'sms_primary_provider' => 'required|in:gaintsms,mnotify',
        ]);

        $this->updateSetting('sms_primary_provider', $this->sms_primary_provider, SettingType::String, 'sms');

        $this->dispatch('banner', style: 'success', message: 'Primary SMS provider updated.');
    }

    public function saveEmailProvider(): void
    {
        $this->validate([
            'email_primary_provider' => 'required|in:smtp,brevo',
        ]);

        $this->updateSetting('email_primary_provider', $this->email_primary_provider, SettingType::String, 'email');

        $this->dispatch('banner', style: 'success', message: 'Primary email provider updated.');
    }

    // ── Delivery Locations ────────────────────────────────────────────────────

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
        if ($index >= \count($this->delivery_locations) - 1) {
            return;
        }

        [$this->delivery_locations[$index], $this->delivery_locations[$index + 1]] =
            [$this->delivery_locations[$index + 1], $this->delivery_locations[$index]];

        $this->persistDeliveryLocations();
    }

    private function persistDeliveryLocations(): void
    {
        $this->updateSetting('delivery_locations', json_encode(array_values($this->delivery_locations)), SettingType::Json, 'booking');
    }

    // ── Event Settings ────────────────────────────────────────────────────────

    public function saveEventSettings(): void
    {
        $this->validate([
            'event_lead_days' => ['required', 'integer', 'min:0', 'max:28'],
        ], [
            'event_lead_days.max' => 'Maximum lead time is 4 weeks (28 days).',
        ]);

        $this->updateSetting('event_lead_days', $this->event_lead_days, SettingType::Integer, 'event');

        $this->dispatch('banner', style: 'success', message: 'Event booking settings updated.');
    }

    // ── Loyalty ───────────────────────────────────────────────────────────────

    public function saveLoyaltySettings(): void
    {
        $this->validate([
            'loyalty_points_per_ghc' => ['required', 'integer', 'min:1'],
            'loyalty_referral_bonus' => ['required', 'integer', 'min:0'],
            'loyalty_redemption_rate' => ['required', 'integer', 'min:1'],
            'loyalty_max_redemption_pct' => ['required', 'integer', 'min:1', 'max:100'],
        ]);

        $this->updateSetting('loyalty_enabled', $this->loyalty_enabled, SettingType::Boolean, 'loyalty');
        $this->updateSetting('loyalty_points_per_ghc', $this->loyalty_points_per_ghc, SettingType::Integer, 'loyalty');
        $this->updateSetting('loyalty_referral_bonus', $this->loyalty_referral_bonus, SettingType::Integer, 'loyalty');
        $this->updateSetting('loyalty_redemption_rate', $this->loyalty_redemption_rate, SettingType::Integer, 'loyalty');
        $this->updateSetting('loyalty_max_redemption_pct', $this->loyalty_max_redemption_pct, SettingType::Integer, 'loyalty');

        $this->dispatch('banner', style: 'success', message: 'Loyalty settings updated.');
    }

    // ── Reviews ───────────────────────────────────────────────────────────────

    public function saveReviewSettings(): void
    {
        $this->validate([
            'review_points_reward' => ['required', 'integer', 'min:0', 'max:1000'],
        ]);

        $this->updateSetting('review_enabled', $this->review_enabled ? '1' : '0', SettingType::Boolean, 'reviews');
        $this->updateSetting('review_points_reward', (string) $this->review_points_reward, SettingType::Integer, 'reviews');

        $this->dispatch('banner', style: 'success', message: 'Review settings saved.');
    }

    // ── Notification Toggles ──────────────────────────────────────────────────

    public function updatedEmailNotifications($value): void
    {
        $this->updateSetting('email_enabled', $value, SettingType::Boolean, 'notifications');
        $this->dispatch('banner', style: 'success', message: 'Notification preferences updated.');
    }

    public function updatedSmsNotifications($value): void
    {
        $this->updateSetting('sms_enabled', $value, SettingType::Boolean, 'notifications');
        $this->dispatch('banner', style: 'success', message: 'Notification preferences updated.');
    }

    public function saveNotificationPreference(): void
    {
        $this->validate([
            'notification_preference' => ['required', 'in:email,sms,both'],
        ]);

        Auth::user()->update([
            'notification_preference' => $this->notification_preference,
        ]);

        $this->dispatch('banner', style: 'success', message: 'Your notification preference has been saved.');
    }

    // ── System Stats ──────────────────────────────────────────────────────────

    #[Computed]
    public function productionChecklist(): array
    {
        $appUrl = config('app.url', '');
        $cacheDriver = config('cache.default', '');
        $sessionDriver = config('session.driver', '');
        $queueDriver = config('queue.default', '');
        $logLevel = config('logging.channels.'.config('logging.default').'.level', config('logging.level', 'debug'));
        $sitemapExists = file_exists(public_path('sitemap.xml'));
        $opcacheEnabled = function_exists('opcache_get_status') && (opcache_get_status(false)['opcache_enabled'] ?? false);
        $dbDriver = DB::connection()->getDriverName();
        $failedJobs = DB::table('failed_jobs')->count();
        $secureCookie = config('session.secure', false);

        return [
            [
                'label' => 'Production environment',
                'detail' => 'APP_ENV = '.config('app.env'),
                'pass' => App::isProduction(),
                'warn' => false,
            ],
            [
                'label' => 'Debug mode disabled',
                'detail' => 'APP_DEBUG = '.(config('app.debug') ? 'true' : 'false'),
                'pass' => ! config('app.debug'),
                'warn' => false,
            ],
            [
                'label' => 'HTTPS app URL',
                'detail' => $appUrl,
                'pass' => str_starts_with($appUrl, 'https://'),
                'warn' => false,
            ],
            [
                'label' => 'Production database (MySQL/PostgreSQL)',
                'detail' => 'Driver: '.strtoupper($dbDriver),
                'pass' => in_array($dbDriver, ['mysql', 'pgsql', 'mariadb']),
                'warn' => false,
            ],
            [
                'label' => 'Secure session cookie',
                'detail' => 'SESSION_SECURE_COOKIE = '.($secureCookie ? 'true' : 'false'),
                'pass' => (bool) $secureCookie,
                'warn' => false,
            ],
            [
                'label' => 'Cache driver (file or redis)',
                'detail' => 'CACHE_STORE = '.$cacheDriver,
                'pass' => in_array($cacheDriver, ['file', 'redis']),
                'warn' => $cacheDriver === 'database',
            ],
            [
                'label' => 'Session driver (file or redis)',
                'detail' => 'SESSION_DRIVER = '.$sessionDriver,
                'pass' => in_array($sessionDriver, ['file', 'redis']),
                'warn' => $sessionDriver === 'database',
            ],
            [
                'label' => 'Queue driver configured',
                'detail' => 'QUEUE_CONNECTION = '.$queueDriver,
                'pass' => $queueDriver !== 'sync',
                'warn' => false,
            ],
            [
                'label' => 'No failed queue jobs',
                'detail' => $failedJobs.' failed job'.($failedJobs !== 1 ? 's' : ''),
                'pass' => $failedJobs === 0,
                'warn' => false,
            ],
            [
                'label' => 'Log level (warning or error)',
                'detail' => 'LOG_LEVEL = '.$logLevel,
                'pass' => in_array($logLevel, ['warning', 'error', 'critical', 'alert', 'emergency']),
                'warn' => $logLevel === 'notice',
            ],
            [
                'label' => 'PHP OPcache enabled',
                'detail' => $opcacheEnabled ? 'Enabled' : 'Disabled',
                'pass' => $opcacheEnabled,
                'warn' => false,
            ],
            [
                'label' => 'Sitemap generated',
                'detail' => $sitemapExists ? 'sitemap.xml exists' : 'sitemap.xml not found',
                'pass' => $sitemapExists,
                'warn' => false,
            ],
            [
                'label' => 'Security headers middleware',
                'detail' => 'SecurityHeaders registered',
                'pass' => true,
                'warn' => false,
            ],
        ];
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

                return "{$days}d {$hours}h";
            }
        }

        return 'N/A';
    }

    // ── Helpers ───────────────────────────────────────────────────────────────

    private function updateSetting(string $key, mixed $value, SettingType $type = SettingType::String, ?string $group = null): void
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

    public function render(): View
    {
        return view('livewire.admin.settings.admin-settings');
    }
}
