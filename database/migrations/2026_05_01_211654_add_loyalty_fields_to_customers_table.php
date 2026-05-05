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
        Schema::table('customers', function (Blueprint $table) {
            $table->string('referral_code', 12)->nullable()->unique()->after('user_id');
            $table->foreignId('referred_by_id')->nullable()->after('referral_code')->constrained('customers')->nullOnDelete();
            $table->unsignedInteger('loyalty_points')->default(0)->after('referred_by_id');
        });
    }

    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropForeign(['referred_by_id']);
            $table->dropColumn(['referral_code', 'referred_by_id', 'loyalty_points']);
        });
    }
};
