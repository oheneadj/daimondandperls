<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('category_package', function (Blueprint $table) {
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->foreignId('package_id')->constrained()->cascadeOnDelete();
            $table->primary(['category_id', 'package_id']);
        });

        // Copy existing one-to-many data into the pivot
        DB::statement('
            INSERT INTO category_package (category_id, package_id)
            SELECT category_id, id FROM packages
            WHERE category_id IS NOT NULL AND deleted_at IS NULL
        ');
    }

    public function down(): void
    {
        Schema::dropIfExists('category_package');
    }
};
