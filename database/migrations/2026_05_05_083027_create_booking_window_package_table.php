<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('booking_window_package', function (Blueprint $table) {
            $table->foreignId('booking_window_id')->constrained()->cascadeOnDelete();
            $table->foreignId('package_id')->constrained()->cascadeOnDelete();
            $table->primary(['booking_window_id', 'package_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('booking_window_package');
    }
};
