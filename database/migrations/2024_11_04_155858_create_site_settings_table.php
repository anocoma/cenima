<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSiteSettingsTable extends Migration
{
    public function up()
    {
        Schema::create('site_settings', function (Blueprint $table) {
            $table->id();
            $table->string('website_logo')->nullable(); // Logo website
            $table->string('site_name')->nullable(); // Tên website
            $table->string('brand_name')->nullable(); // Tên thương hiệu
            $table->string('slogan')->nullable(); // Slogan
            $table->string('phone')->nullable(); // Số điện thoại
            $table->string('email')->nullable(); // Email liên hệ
            $table->string('headquarters')->nullable(); // Trụ sở chính
            $table->string('business_license')->nullable(); // Giấy phép kinh doanh
            $table->string('working_hours')->nullable(); // Thời gian làm việc
            $table->string('facebook_link')->nullable(); // Link Facebook
            $table->string('youtube_link')->nullable(); // Link YouTube
            $table->string('instagram_link')->nullable(); // Link Instagram
            $table->string('privacy_policy_image')->nullable(); // Ảnh đại diện cho Chính sách Bảo mật
            $table->text('privacy_policy')->nullable(); // Chính sách Bảo mật
            $table->string('terms_of_service_image')->nullable(); // Ảnh đại diện cho Điều khoản Dịch vụ
            $table->text('terms_of_service')->nullable(); // Điều khoản Dịch vụ
            $table->string('introduction_image')->nullable(); // Ảnh đại diện cho Phần giới thiệu
            $table->text('introduction')->nullable(); // Phần giới thiệu
            $table->string('copyright')->nullable(); // Bản quyền
            $table->timestamps(); // Thời gian tạo và cập nhật
        });
    }

    public function down()
    {
        Schema::dropIfExists('site_settings');
    }
}
