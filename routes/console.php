<?php

use Illuminate\Support\Facades\Schedule;

// Process queued jobs (emails, notifications) — runs every minute, stops when empty
Schedule::command('queue:work --stop-when-empty --max-time=55')
    ->everyMinute()
    ->withoutOverlapping()
    ->runInBackground();

// Cancel pending bookings that haven't been paid within 24 hours
Schedule::command('booking:cleanup-abandoned')->hourly();
