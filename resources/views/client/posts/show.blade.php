@extends('client.layouts.master')

@section('title')
    {{ $post->title }}
@endsection

@section('content')
<div class="hs_blog_detail_main_wrapper" style="padding: 50px 0;"> <!-- Thêm khoảng cách -->
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="hs_blog_detail_cont_main_wrapper" style="background-color: #f9f9f9; padding: 30px; border-radius: 10px; box-shadow: 0 4px 8px rgba(0,0,0,0.1);">

                    <h2 style="margin-bottom: 15px;" class='title-post'>{{ $post->title }}</h2>
                    <ul class="post-meta">
                        <li><i class="fa fa-calendar"></i> {{ $post->created_at->timezone('Asia/Ho_Chi_Minh')->format('d/m/Y - H:i:s') }}</li>
                        <li>{{ $post->user->name ?? 'Không có người đăng' }}</li>
                        {{-- <li><i class="fa fa-eye"></i> {{ $post->view_count }} lượt xem</li> --}}
                        <li><i class="fa fa-eye"></i> {{ number_format($post->view_count) }} lượt xem</li>
                    </ul>
                    <p >{{ $post->description }}</p>
                    <div class="hs_blog_detail_img_main_wrapper">
                        @php
                            $url = $post->img_post;
                            if (!\Str::contains($url, 'http')) {
                                $url = Storage::url($url);
                            }
                        @endphp
                        <img src="{{ $url }}" alt="{{ $post->title }}" class="responsive-img" />
                    </div>
                    <div class="hs_blog_detail_body" style="margin-top: 30px;">
                        {!! $post->content !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
    <style>
        .hs_blog_detail_main_wrapper {
            margin-top: 60px;
            margin-bottom: 60px;
        }
        .container {
            max-width: 75%;
            margin: 0 auto;
        }

        .hs_blog_detail_img_main_wrapper {
            text-align: center;
            margin-bottom: 30px;
            margin-top: 30px;
        }

        .hs_blog_detail_img_main_wrapper img {
            width: 100%;
            min-height: 400px;
            object-fit: contain;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .hs_blog_detail_cont_main_wrapper {
            background-color: #fff;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .hs_blog_detail_body {
            font-size: 16px;
            line-height: 1.8;
            color: #333;
            margin-top: 20px;
        }

        .hs_blog_detail_body p {
            margin-bottom: 15px;
        }

        .hs_blog_detail_body h3 {
            font-size: 24px;
            color: #ff6600;
            margin-top: 30px;
            margin-bottom: 15px;
        }

        .post-meta {
            list-style: none;
            padding: 0;
            margin-bottom: 20px;
            display: flex;
            flex-wrap: nowrap;
            align-items: center;
        }

        .post-meta li {
            display: inline-block;
            margin-right: 15px;
            font-size: 16px;
        }

        .post-meta li:last-child {
            margin-right: 0;
        }
    </style>
@endsection
