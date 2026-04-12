<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('error_logs', function (Blueprint $table) {
            $table->id();
            $table->string('source', 100)->index();
            $table->string('context', 100)->nullable();
            $table->string('level', 20)->default('error');
            $table->string('booking_reference', 50)->nullable()->index();
            $table->string('error_code', 50)->nullable();
            $table->text('message');
            $table->string('network', 20)->nullable();
            $table->string('payer_number', 20)->nullable();
            $table->json('payload')->nullable();
            $table->boolean('resolved')->default(false)->index();
            $table->text('resolution_note')->nullable();
            $table->foreignId('resolved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('error_logs');
    }
};
