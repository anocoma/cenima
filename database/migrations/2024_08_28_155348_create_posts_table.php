<?php

use App\Models\User;
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
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->constrained();
            $table->string('title');
            $table->string('slug');
            $table->string('img_post')->nullable();
            $table->text('description')->comment('Mô tả ngắn');
            $table->text('content')->comment('Nội dung chính');
            $table->boolean('is_active')->default(true);
            $table->integer('view_count')->default(0); // Thêm trường view_count, mặc định là 0
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
