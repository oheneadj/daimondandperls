<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('categories', function (Blueprint $table): void {
            $table->boolean('booking_window_enabled')->default(false)->after('slug');
            $table->unsignedTinyInteger('delivery_day')->nullable()->after('booking_window_enabled'); // 0=Sun…6=Sat
            $table->unsignedTinyInteger('cutoff_day')->nullable()->after('delivery_day');             // 0=Sun…6=Sat
            $table->time('cutoff_time')->nullable()->after('cutoff_day');                             // e.g. 06:00:00
        });
    }

    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table): void {
            $table->dropColumn(['booking_window_enabled', 'delivery_day', 'cutoff_day', 'cutoff_time']);
        });
    }
};
