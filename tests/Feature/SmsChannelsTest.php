<?php

declare(strict_types=1);

use App\Models\Setting;
use App\Models\SmsLog;
use App\Models\User;
use App\Notifications\Channels\GaintSmsChannel;
use App\Notifications\Channels\MNotifyChannel;
use App\Notifications\Channels\SmsChannels;
use App\Notifications\OtpNotification;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

// ── SmsChannels routing ────────────────────────────────────────────────────────

test('SmsChannels defaults to GaintSmsChannel as primary when no setting exists', function (): void {
    expect(SmsChannels::primary())->toBe(GaintSmsChannel::class);
});

test('SmsChannels returns MNotifyChannel as secondary when GaintSMS is primary', function (): void {
    expect(SmsChannels::secondary())->toBe(MNotifyChannel::class);
});

test('SmsChannels returns MNotifyChannel as primary when set in settings', function (): void {
    Setting::create(['key' => 'sms_primary_provider', 'value' => 'mnotify']);
    Cache::flush();

    expect(SmsChannels::primary())->toBe(MNotifyChannel::class);
    expect(SmsChannels::secondary())->toBe(GaintSmsChannel::class);
});

test('SmsChannels forOtp uses primary for initial send', function (): void {
    $channels = SmsChannels::forOtp(false);

    expect($channels)->toHaveCount(1)
        ->and($channels[0])->toBe(GaintSmsChannel::class);
});

test('SmsChannels forOtp uses secondary for resends', function (): void {
    $channels = SmsChannels::forOtp(true);

    expect($channels)->toHaveCount(1)
        ->and($channels[0])->toBe(MNotifyChannel::class);
});

// ── OtpNotification channel selection ─────────────────────────────────────────

test('OtpNotification routes initial send through primary channel', function (): void {
    $notification = new OtpNotification('123456', isResend: false);
    $user = User::factory()->customer()->create();

    expect($notification->via($user))->toBe([GaintSmsChannel::class]);
});

test('OtpNotification routes resend through secondary channel', function (): void {
    $notification = new OtpNotification('123456', isResend: true);
    $user = User::factory()->customer()->create();

    expect($notification->via($user))->toBe([MNotifyChannel::class]);
});

// ── MNotifyChannel send & logging ─────────────────────────────────────────────

test('MNotifyChannel logs with provider mnotify on successful send', function (): void {
    config([
        'services.mnotify.sender_id' => 'DPCatering',
        'services.mnotify.api_key' => 'test-key',
    ]);

    Http::fake([
        'api.mnotify.com/*' => Http::response(['status' => 'success', 'code' => '2000', 'summary' => ['_id' => 'MSG-001']], 200),
    ]);

    $user = User::factory()->customer()->create(['phone' => '0244000001']);

    $notification = new class extends Notification
    {
        public function toSms(object $notifiable): string
        {
            return 'Test OTP message';
        }
    };

    (new MNotifyChannel)->send($user, $notification);

    $log = SmsLog::latest()->first();
    expect($log)->not->toBeNull()
        ->and($log->provider)->toBe('mnotify')
        ->and($log->status)->toBe('sent')
        ->and($log->to)->toBe('0244000001');
});

test('MNotifyChannel logs failed send when API returns error status', function (): void {
    config([
        'services.mnotify.sender_id' => 'DPCatering',
        'services.mnotify.api_key' => 'test-key',
    ]);

    Http::fake([
        'api.mnotify.com/*' => Http::response(['status' => 'error', 'code' => '1002', 'message' => 'Invalid key'], 200),
    ]);

    $user = User::factory()->customer()->create(['phone' => '0244000002']);

    $notification = new class extends Notification
    {
        public function toSms(object $notifiable): string
        {
            return 'Test message';
        }
    };

    (new MNotifyChannel)->send($user, $notification);

    $log = SmsLog::latest()->first();
    expect($log->provider)->toBe('mnotify')
        ->and($log->status)->toBe('failed');
});

// ── GaintSmsChannel provider logging ──────────────────────────────────────────

test('GaintSmsChannel logs with provider gaintsms on successful send', function (): void {
    config([
        'services.gaintsms.sender_id' => 'D&PCatering',
        'services.gaintsms.api_token' => 'test-token',
    ]);

    Http::fake([
        'api.giantsms.com/*' => Http::response(['status' => true, 'data' => ['message_id' => 'abc123']], 200),
    ]);

    $user = User::factory()->customer()->create(['phone' => '0244000003']);

    $notification = new class extends Notification
    {
        public function toSms(object $notifiable): string
        {
            return 'Test message';
        }
    };

    (new GaintSmsChannel)->send($user, $notification);

    $log = SmsLog::latest()->first();
    expect($log->provider)->toBe('gaintsms')
        ->and($log->status)->toBe('sent')
        ->and($log->message_id)->toBe('abc123');
});
