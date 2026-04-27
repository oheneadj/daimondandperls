<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() !== 'sqlite') {
            Schema::table('payment_logs', function (Blueprint $table) {
                $table->dropForeign('fk_pl_payment_id');
            });
        }

        Schema::table('payment_logs', function (Blueprint $table) {
            // Make payment_id nullable — many events happen before a Payment record exists.
            $table->unsignedBigInteger('payment_id')->nullable()->change();

            $table->string('gateway', 30)->nullable()->after('payment_id');
            $table->string('direction', 10)->nullable()->after('gateway');       // outbound | inbound
            $table->string('booking_reference')->nullable()->after('direction');
            $table->string('level', 20)->nullable()->after('booking_reference'); // info | warning | error
            $table->string('error_code')->nullable()->after('level');
            $table->text('error_message')->nullable()->after('error_code');
            $table->string('network', 50)->nullable()->after('error_message');
            $table->string('payer_number', 20)->nullable()->after('network');
            $table->text('raw_request')->nullable()->after('payer_number');
            $table->text('raw_response')->nullable()->after('raw_request');
            $table->integer('http_status')->nullable()->after('raw_response');
            $table->integer('duration_ms')->nullable()->after('http_status');

            $table->index('booking_reference');
            $table->index('gateway');
            $table->index('level');
        });

        if (DB::getDriverName() !== 'sqlite') {
            Schema::table('payment_logs', function (Blueprint $table) {
                $table->foreign('payment_id', 'fk_pl_payment_id')->references('id')->on('payments')->onDelete('cascade');
            });
        }

        // File-based SQLite drops indexes when ->change() recreates the table.
        // In-memory SQLite (tests) and MySQL both keep the index, so we guard before adding.
        $indexExists = false;
        if (DB::getDriverName() === 'sqlite') {
            $indexExists = collect(DB::select("SELECT name FROM sqlite_master WHERE type='index' AND tbl_name='payment_logs'"))
                ->pluck('name')
                ->contains('payment_logs_payment_id_index');
        }

        if (DB::getDriverName() === 'sqlite' && ! $indexExists) {
            Schema::table('payment_logs', function (Blueprint $table) {
                $table->index('payment_id');
            });
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() !== 'sqlite') {
            Schema::table('payment_logs', function (Blueprint $table) {
                $table->dropForeign('fk_pl_payment_id');
            });
        }

        Schema::table('payment_logs', function (Blueprint $table) {
            $table->dropIndex(['booking_reference']);
            $table->dropIndex(['gateway']);
            $table->dropIndex(['level']);

            $table->dropColumn([
                'gateway', 'direction', 'booking_reference', 'level',
                'error_code', 'error_message', 'network', 'payer_number',
                'raw_request', 'raw_response', 'http_status', 'duration_ms',
            ]);

            $table->unsignedBigInteger('payment_id')->nullable(false)->change();
        });

        if (DB::getDriverName() !== 'sqlite') {
            Schema::table('payment_logs', function (Blueprint $table) {
                $table->foreign('payment_id', 'fk_pl_payment_id')->references('id')->on('payments')->onDelete('cascade');
            });
        }
    }
};
