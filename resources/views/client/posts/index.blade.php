@extends('client.layouts.master')

@section('title')
    Tin tức
@endsection

@section('content')
<div class="hs_blog_categories_main_wrapper">
    <div class="container">
        <div class="row">
            <!-- Lặp qua 6 bài viết -->
            @foreach($posts as $post)
            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12" style="margin-bottom: 30px;">
                <div class="hs_blog_box1_main_wrapper">
                    <div class="hs_blog_box1_img_wrapper">
                        @php
                        $url = $post->img_post;
                        if (!\Str::contains($url, 'http')) {
                            $url = Storage::url($url);
                        }
                        @endphp
                        <img src="{{ $url }}" alt="Chưa có ảnh" />
                    </div>
                    <div class="hs_blog_box1_cont_main_wrapper">
                        <div class="hs_blog_cont_heading_wrapper">
                            {{-- <ul>
                                <li>{{ $post->created_at->timezone('Asia/Ho_Chi_Minh')->format('d/m/Y - H:i:s') }}</li>
                                <li>{{ $post->user->name ?? 'Không có người đăng' }}</li>
                                <li><i class="fa fa-eye"></i> {{ $post->view_count }} lượt xem</li>
                            </ul> --}}
                            <h2>{{ Str::limit($post->title, 30) }}</h2>
                            <p>{{ Str::limit($post->description, 100) }}</p>
                            <h5><a href="{{ route('posts.show', $post->slug) }}">Đọc thêm <i class="fa fa-long-arrow-right"></i></a></h5>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Phân trang -->
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="pager_wrapper prs_blog_pagi_wrapper">
                    {{ $posts->links('pagination::bootstrap-4') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
    <style>
        .hs_blog_box1_main_wrapper {
            padding: 20px;
            border-radius: 10px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            height: 500px;
        }
        .hs_blog_cont_heading_wrapper p {
            color: #666;
            font-size: 14px;
            margin-bottom: 15px;
        }
        .pager_wrapper .pagination {
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .pager_wrapper .pagination li {
        list-style: none;
    }

    .pager_wrapper .pagination li a,
    .pager_wrapper .pagination li span {
        display: flex;
        justify-content: center;
        align-items: center;
        width: 40px;
        height: 40px;
        margin: 0 5px;
        text-decoration: none;
        color: #fff;
        background-color: #ff6600;
        border-radius: 5px;
        font-size: 16px;
        transition: background-color 0.3s ease;
    }

    .pager_wrapper .pagination li.active span {
        background-color: #0066cc;
    }

    .pager_wrapper .pagination li:hover a {
        background-color: #ff9900;
    }

    .pager_wrapper .pagination li.disabled span {
        background-color: #ccc;
    }
    </style>
@endsection

