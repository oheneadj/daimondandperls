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
        Schema::table('packages', function (Blueprint $table) {
            $table->unsignedInteger('min_guests')->default(50)->after('serving_size');
            $table->json('features')->nullable()->after('min_guests');
            $table->boolean('is_popular')->default(false)->after('sort_order')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('packages', function (Blueprint $table) {
            $table->dropColumn(['min_guests', 'features', 'is_popular']);
        });
    }
};
