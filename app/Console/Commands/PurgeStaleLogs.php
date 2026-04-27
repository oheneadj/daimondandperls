<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\ActivityLog;
use App\Models\ErrorLog;
use App\Models\SmsLog;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class PurgeStaleLogs extends Command
{
    protected $signature = 'logs:purge
                            {--dry-run : Show counts without deleting}';

    protected $description = 'Purge stale log entries older than 7 days';

    public function handle(): int
    {
        $cutoff = now()->subDays(7);
        $dryRun = $this->option('dry-run');

        $targets = [
            'error_logs (resolved)' => fn () => ErrorLog::query()
                ->where('resolved', true)
                ->where('created_at', '<', $cutoff),

            'sms_logs' => fn () => SmsLog::query()
                ->where('created_at', '<', $cutoff),

            'activity_logs' => fn () => ActivityLog::query()
                ->where('created_at', '<', $cutoff),

            'sessions' => fn () => DB::table('sessions')
                ->where('last_activity', '<', $cutoff->timestamp),
        ];

        $rows = [];

        foreach ($targets as $label => $query) {
            $count = $query()->count();

            if (! $dryRun && $count > 0) {
                $query()->delete();
            }

            $rows[] = [$label, number_format($count), $dryRun ? 'skipped' : 'deleted'];
        }

        $this->table(['Table', 'Rows', 'Action'], $rows);

        if ($dryRun) {
            $this->line('  <comment>Dry run — nothing was deleted.</comment>');
        }

        return self::SUCCESS;
    }
}
