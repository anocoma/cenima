<?php

use App\Models\Cinema;
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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('img_thumbnail', 500)->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->rememberToken();
            $table->text('address')->nullable();
            $table->string('gender')->default(User::GENDERS['2']);
            $table->date('birthday')->nullable();
            $table->string('service_id')->nullable();
            $table->string('service_name')->nullable();
            $table->string('type')->default(User::TYPE_MEMBER);         //admin(quản trị viên)/member(thành viên)
            $table->foreignIdFor(Cinema::class)->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
