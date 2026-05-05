<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('booking_windows', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedTinyInteger('delivery_day'); // ISO 1=Mon…7=Sun
            $table->unsignedTinyInteger('cutoff_day');
            $table->time('cutoff_time');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('booking_windows');
    }
};
