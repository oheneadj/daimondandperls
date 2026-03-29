<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('booking_items', function (Blueprint $table) {
            $table->string('package_name')->nullable()->after('package_id');
            $table->text('package_description')->nullable()->after('package_name');
        });

        DB::statement('
            UPDATE booking_items
            SET package_name = (SELECT name FROM packages WHERE packages.id = booking_items.package_id),
                package_description = (SELECT description FROM packages WHERE packages.id = booking_items.package_id)
            WHERE package_id IS NOT NULL
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('booking_items', function (Blueprint $table) {
            $table->dropColumn(['package_name', 'package_description']);
        });
    }
};
