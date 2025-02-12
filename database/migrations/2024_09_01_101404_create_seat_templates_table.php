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
        Schema::create('seat_templates', function (Blueprint $table) {
            $table->id();
            $table->unsignedSmallInteger('matrix_id');
            $table->string('name')->unique(); // Tên của template
            $table->json('seat_structure')->nullable(); // Cấu trúc ghế lưu ở dạng JSON
            $table->unsignedTinyInteger('row_regular')->default(4);
            $table->unsignedTinyInteger('row_vip')->nullable();
            $table->unsignedTinyInteger('row_double')->nullable(2);
            $table->string('description')->nullable();
            $table->boolean('is_active')->default(false);
            $table->boolean('is_publish')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seat_templates');
    }
};
