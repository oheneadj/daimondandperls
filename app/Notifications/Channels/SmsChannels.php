<?php

declare(strict_types=1);

namespace App\Notifications\Channels;

class SmsChannels
{
    /** Channel class for the configured primary provider. */
    public static function primary(): string
    {
        return self::resolve(dpc_setting('sms_primary_provider', 'gaintsms'));
    }

    /** Channel class for the secondary (resend) provider. */
    public static function secondary(): string
    {
        $primary = dpc_setting('sms_primary_provider', 'gaintsms');

        return self::resolve($primary === 'gaintsms' ? 'mnotify' : 'gaintsms');
    }

    /** Returns [primary] for initial sends, [secondary] for resends. */
    public static function forOtp(bool $isResend): array
    {
        return [$isResend ? self::secondary() : self::primary()];
    }

    private static function resolve(string $provider): string
    {
        return match ($provider) {
            'mnotify' => MNotifyChannel::class,
            default => GaintSmsChannel::class,
        };
    }
}
