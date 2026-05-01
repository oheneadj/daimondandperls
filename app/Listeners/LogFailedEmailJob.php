<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Models\EmailLog;
use Illuminate\Notifications\SendQueuedNotifications;
use Illuminate\Queue\Events\JobFailed;

class LogFailedEmailJob
{
    public function handle(JobFailed $event): void
    {
        // I only care about queued notification jobs that send mail.
        $payload = json_decode($event->job->getRawBody(), true);
        $commandClass = $payload['data']['commandName'] ?? null;

        if ($commandClass !== SendQueuedNotifications::class) {
            return;
        }

        EmailLog::create([
            'to' => 'unknown',
            'subject' => null,
            'mailer' => null,
            'message_id' => null,
            'status' => 'failed',
            'error_message' => $event->exception->getMessage(),
        ]);
    }
}
