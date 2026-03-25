<?php

declare(strict_types=1);

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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->unique()->constrained()->onDelete('cascade');
            $table->string('gateway');
            $table->string('method');
            $table->string('gateway_reference', 100)->nullable()->unique()->index();
            $table->json('gateway_response')->nullable();
            $table->decimal('amount', 10, 2);
            $table->char('currency', 3)->default('GHS');
            $table->string('status')->default('initiated')->index();
            $table->timestamp('paid_at')->nullable()->index();
            $table->foreignId('verified_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
