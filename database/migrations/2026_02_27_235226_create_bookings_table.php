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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('reference', 20)->unique()->index();
            $table->foreignId('customer_id')->constrained()->onDelete('restrict');
            $table->foreignId('package_id')->constrained()->onDelete('restrict');

            // Event details
            $table->date('event_date')->nullable()->index();
            $table->time('event_start_time')->nullable();
            $table->time('event_end_time')->nullable();
            $table->string('event_type')->nullable();
            $table->string('event_type_other', 100)->nullable();

            // Financials
            $table->decimal('package_price', 10, 2);
            $table->decimal('total_amount', 10, 2);

            // Statuses
            $table->string('status')->default('pending')->index();
            $table->string('payment_status')->default('unpaid')->index();

            // Admin fields
            $table->text('admin_notes')->nullable();
            $table->string('cancelled_reason', 255)->nullable();
            $table->foreignId('confirmed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
