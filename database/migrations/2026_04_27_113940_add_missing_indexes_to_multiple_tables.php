<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // booking_items had zero indexes despite being joined/filtered on every dashboard and reports query
        Schema::table('booking_items', function (Blueprint $table) {
            $table->index('booking_id');
            $table->index('package_id');
            $table->index('scheduled_date'); // queried by date on dashboard and next-week schedule
        });

        // customers.user_id FK was added without an index
        Schema::table('customers', function (Blueprint $table) {
            $table->index('user_id');
        });

        // contact_messages.status is read on every admin page load (sidebar badge + dashboard)
        Schema::table('contact_messages', function (Blueprint $table) {
            $table->index('status');
            $table->index('created_at');
        });

        // sms_logs.booking_id FK was added without an index
        Schema::table('sms_logs', function (Blueprint $table) {
            $table->index('booking_id');
        });
    }

    public function down(): void
    {
        Schema::table('booking_items', function (Blueprint $table) {
            $table->dropIndex(['booking_id']);
            $table->dropIndex(['package_id']);
            $table->dropIndex(['scheduled_date']);
        });

        Schema::table('customers', function (Blueprint $table) {
            $table->dropIndex(['user_id']);
        });

        Schema::table('contact_messages', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['created_at']);
        });

        Schema::table('sms_logs', function (Blueprint $table) {
            $table->dropIndex(['booking_id']);
        });
    }
};
