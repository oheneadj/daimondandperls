<?php

declare(strict_types=1);

use App\Models\ActivityLog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

uses(RefreshDatabase::class);

function staleDate(): \Carbon\CarbonImmutable
{
    return now()->subDays(8);
}
function freshDate(): \Carbon\CarbonImmutable
{
    return now()->subDays(3);
}

it('deletes resolved error_logs older than 7 days', function () {
    DB::table('error_logs')->insert(['source' => 'test', 'message' => 'old resolved', 'resolved' => true, 'level' => 'error', 'created_at' => staleDate(), 'updated_at' => staleDate()]);
    DB::table('error_logs')->insert(['source' => 'test', 'message' => 'old unresolved', 'resolved' => false, 'level' => 'error', 'created_at' => staleDate(), 'updated_at' => staleDate()]);
    DB::table('error_logs')->insert(['source' => 'test', 'message' => 'recent resolved', 'resolved' => true, 'level' => 'error', 'created_at' => freshDate(), 'updated_at' => freshDate()]);

    $this->artisan('logs:purge')->assertSuccessful();

    expect(DB::table('error_logs')->count())->toBe(2)
        ->and(DB::table('error_logs')->where('message', 'old resolved')->exists())->toBeFalse()
        ->and(DB::table('error_logs')->where('message', 'old unresolved')->exists())->toBeTrue()
        ->and(DB::table('error_logs')->where('message', 'recent resolved')->exists())->toBeTrue();
});

it('deletes sms_logs older than 7 days', function () {
    DB::table('sms_logs')->insert(['to' => '0244000001', 'message' => 'stale sms', 'status' => 'sent', 'created_at' => staleDate(), 'updated_at' => staleDate()]);
    DB::table('sms_logs')->insert(['to' => '0244000002', 'message' => 'recent sms', 'status' => 'sent', 'created_at' => freshDate(), 'updated_at' => freshDate()]);

    $this->artisan('logs:purge')->assertSuccessful();

    expect(DB::table('sms_logs')->count())->toBe(1)
        ->and(DB::table('sms_logs')->where('message', 'stale sms')->exists())->toBeFalse()
        ->and(DB::table('sms_logs')->where('message', 'recent sms')->exists())->toBeTrue();
});

it('deletes activity_logs older than 7 days', function () {
    ActivityLog::create(['action' => 'stale.action', 'created_at' => staleDate()]);
    ActivityLog::create(['action' => 'recent.action', 'created_at' => freshDate()]);

    $this->artisan('logs:purge')->assertSuccessful();

    expect(ActivityLog::count())->toBe(1)
        ->and(ActivityLog::where('action', 'stale.action')->exists())->toBeFalse()
        ->and(ActivityLog::where('action', 'recent.action')->exists())->toBeTrue();
});

it('deletes expired sessions older than 7 days', function () {
    DB::table('sessions')->insert(['id' => 'stale-session', 'last_activity' => staleDate()->timestamp, 'payload' => 'x', 'user_id' => null, 'ip_address' => null, 'user_agent' => null]);
    DB::table('sessions')->insert(['id' => 'fresh-session', 'last_activity' => freshDate()->timestamp, 'payload' => 'x', 'user_id' => null, 'ip_address' => null, 'user_agent' => null]);

    $this->artisan('logs:purge')->assertSuccessful();

    expect(DB::table('sessions')->count())->toBe(1)
        ->and(DB::table('sessions')->where('id', 'stale-session')->exists())->toBeFalse()
        ->and(DB::table('sessions')->where('id', 'fresh-session')->exists())->toBeTrue();
});

it('does not delete anything on dry run', function () {
    DB::table('error_logs')->insert(['source' => 'test', 'message' => 'old', 'resolved' => true, 'level' => 'error', 'created_at' => staleDate(), 'updated_at' => staleDate()]);
    DB::table('sms_logs')->insert(['to' => '0244000001', 'message' => 'old sms', 'status' => 'sent', 'created_at' => staleDate(), 'updated_at' => staleDate()]);

    $this->artisan('logs:purge --dry-run')->assertSuccessful();

    expect(DB::table('error_logs')->count())->toBe(1)
        ->and(DB::table('sms_logs')->count())->toBe(1);
});
