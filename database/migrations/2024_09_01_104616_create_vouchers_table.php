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
        Schema::create('vouchers', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('title');
            $table->string('description')->nullable();
            $table->dateTime('start_date_time');
            $table->dateTime('end_date_time');
            $table->unsignedInteger('discount');
            $table->unsignedInteger('quantity');
            $table->unsignedTinyInteger('limit')->default(1);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_publish')->default(true);
            $table->integer('type')->default(1)->comment('1: hệ thống, 2: sinh nhật');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vouchers');
    }
};
