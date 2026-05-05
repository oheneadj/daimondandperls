<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained()->cascadeOnDelete();
            $table->foreignId('customer_id')->nullable()->nullOnDelete()->constrained();
            $table->string('token', 60)->unique();
            $table->unsignedTinyInteger('stars')->nullable();
            $table->string('author_name', 100)->nullable();
            $table->string('reviewer_phone', 20)->nullable();
            $table->text('message')->nullable();
            $table->boolean('is_approved')->default(false);
            $table->unsignedInteger('points_awarded')->default(0);
            $table->string('friend_name', 100)->nullable();
            $table->string('friend_phone', 20)->nullable()->unique();
            $table->timestamp('friend_sms_sent_at')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamps();

            $table->index('booking_id');
            $table->index('is_approved');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
