<?php

declare(strict_types=1);

namespace App\Notifications\Channels;

class MailChannels
{
    // I return the active email mailer name from settings.
    // Adding a new provider = add a mailer to config/mail.php and a new option in admin settings.
    public static function primary(): string
    {
        return dpc_setting('email_primary_provider') ?? 'brevo';
    }
}
