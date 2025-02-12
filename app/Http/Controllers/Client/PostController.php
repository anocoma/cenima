<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;

class PostController extends Controller
{
    public function index()
    {
        // Lấy 6 bài viết mới nhất, có is_active = 1 và phân trang
        $posts = Post::where('is_active', 1)->latest()->paginate(6); 

        // Truyền biến $posts vào view
        return view('client.posts.index', compact('posts'));
    }

    public function show($slug)
    {
        // Tìm bài viết dựa trên slug thay vì id
        $post = Post::where('slug', $slug)->firstOrFail();

        // Tăng lượt xem
        $post->increment('view_count');

        // Hiển thị bài viết
        return view('client.posts.show', compact('post'));
    }
}
