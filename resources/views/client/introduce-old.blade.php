
@extends('client.layouts.master')

@section('title')
    Giới thiệu
@endsection

@section('content')
@php
    // Truy vấn dữ liệu từ bảng site_settings
    use App\Models\SiteSetting;
    $settings = SiteSetting::first();
@endphp

<div class="introduce-wrapper">
    <div class="introduce-container">
        <div class="introduce-main-content">
            <!-- Ảnh đại diện và thông tin website -->
            @if($settings->introduction_image)
            {{-- Kiểm tra xem có phải là ảnh mặc định hay không --}}
            @if(Str::startsWith($settings->introduction_image, 'theme/client/images/'))
                <img src="{{ asset($settings->introduction_image) }}" alt="introduction_image" class="introduce-logo">
            @else
                <img src="{{ Storage::url($settings->introduction_image) }}" alt="introduction_image" class="introduce-logo">
            @endif
            @else
                {{-- Hiển thị ảnh mặc định nếu không có ảnh nào --}}
                <img src="{{ asset('theme/client/images/header/P.svg') }}" alt="introduction_image" class="introduce-logo">
            @endif
            <p>{!! $settings->introduction !!}</p>

            <!-- Thông tin liên hệ -->
            <h4>Thông tin liên hệ</h4>
            <p><strong>Email:</strong> {{ $settings->email}}</p>
            <p><strong>Số điện thoại:</strong> {{ $settings->phone}}</p>
            <p><strong>Giờ làm việc:</strong> {{ $settings->working_hours}}</p>
        </div>
    </div>
</div>

<style>
    .introduce-wrapper { margin-top: 120px; margin-bottom: 20px; }
    .introduce-container { width: 80%; margin: 0 auto; background-color: #fff; border: 1px solid #ddd; padding: 20px; }
    .introduce-main-content img.introduce-logo { width: 100%; height: 400px; display: block; margin-bottom: 20px; }
    .introduce-main-content p { margin-bottom: 15px; line-height: 1.6; }
</style>
@endsection




