<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Models\EmailLog;
use Illuminate\Mail\Events\MessageSent;

class LogSentEmail
{
    public function handle(MessageSent $event): void
    {
        $message = $event->sent->getOriginalMessage();
        $to = collect($message->getTo())->map(fn ($v, $k) => $k)->implode(', ');

        EmailLog::create([
            'to' => $to ?: 'unknown',
            'subject' => $message->getSubject(),
            'mailer' => $event->data['mailer'] ?? null,
            'message_id' => $event->sent->getMessageId(),
            'status' => 'sent',
        ]);
    }
}
