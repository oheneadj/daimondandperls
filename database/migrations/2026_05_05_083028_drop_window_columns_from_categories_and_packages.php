<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $toCrop = array_filter(
                ['booking_window_enabled', 'delivery_day', 'cutoff_day', 'cutoff_time'],
                fn (string $col) => Schema::hasColumn('categories', $col)
            );
            if ($toCrop) {
                $table->dropColumn(array_values($toCrop));
            }
        });

        Schema::table('packages', function (Blueprint $table) {
            if (Schema::hasColumn('packages', 'category_id')) {
                $table->dropForeign(['category_id']);
                $table->dropColumn('category_id');
            }
            if (Schema::hasColumn('packages', 'window_exempt')) {
                $table->dropColumn('window_exempt');
            }
        });
    }

    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->boolean('booking_window_enabled')->default(false);
            $table->unsignedTinyInteger('delivery_day')->nullable();
            $table->unsignedTinyInteger('cutoff_day')->nullable();
            $table->time('cutoff_time')->nullable();
        });

        Schema::table('packages', function (Blueprint $table) {
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
            $table->boolean('window_exempt')->default(false);
        });
    }
};
