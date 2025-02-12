<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StorePostRequest;
use App\Http\Requests\Admin\UpdatePostRequest;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    const PATH_VIEW = 'admin.posts.';
    const PATH_UPLOAD = 'posts';
    public function __construct()
    {
        $this->middleware('can:Danh sách bài viết')->only('index');
        $this->middleware('can:Thêm bài viết')->only(['create', 'store']);
        $this->middleware('can:Sửa bài viết')->only(['edit', 'update']);
        $this->middleware('can:Xóa bài viết')->only('destroy');
    }
    public function index()
    {
        //
        $posts = Post::query()->latest('id')->get();
        return view(self::PATH_VIEW . __FUNCTION__, compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        // dd('Đã đi vào create @');
        return view(self::PATH_VIEW . __FUNCTION__);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request)
    {
        try {
            $dataPost = [
                'title' => $request->title,
                'slug' => Str::slug($request->title),
                'description' => $request->description,
                'content' => $request->content,
                'is_active' => $request->has('is_active') ? 1 : 0,
                'user_id' => auth()->user()->id, // Thêm dòng này để gán user_id từ người dùng đăng nhập
                'view_count' => 0, // Khởi tạo view_count = 0
            ];

            // Xử lý upload ảnh nếu có
            if ($request->hasFile('img_post')) {
                $dataPost['img_post'] = $request->file('img_post')
                    ->storeAs(self::PATH_UPLOAD, Str::uuid() . '.' . $request->file('img_post')->getClientOriginalExtension());
            }

            // Tạo bài viết mới
            Post::create($dataPost);

            return redirect()->route('admin.posts.index')->with('success', 'Thêm mới bài viết thành công!');
        } catch (\Exception $e) {
            return back()->with('error', 'Có lỗi xảy ra, vui lòng thử lại.');
        }
    }
    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        //
        return view(self::PATH_VIEW . __FUNCTION__, compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        //
        return view(self::PATH_VIEW . __FUNCTION__, compact('post'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostRequest $request, Post $post)
    {
        try {
            $dataPost = [
                'title' => $request->title,
                'slug' => Str::slug($request->title),
                'description' => $request->description,
                'content' => $request->content,
                'is_active' => $request->has('is_active') ? 1 : 0,
            ];

            // Kiểm tra nếu có file ảnh mới
            if ($request->hasFile('img_post')) {
                // Xoá ảnh cũ nếu tồn tại
                if ($post->img_post && Storage::exists($post->img_post)) {
                    Storage::delete($post->img_post);
                }
                // Upload ảnh mới và lưu đường dẫn
                $dataPost['img_post'] = $request->file('img_post')
                    ->storeAs(self::PATH_UPLOAD, Str::uuid() . '.' . $request->file('img_post')->getClientOriginalExtension());
            } else {
                // Giữ lại ảnh cũ nếu không có ảnh mới
                $dataPost['img_post'] = $post->img_post;
            }

            // Cập nhật bài viết
            $post->update($dataPost);

            return redirect()
                ->back()
                ->with('success', 'Cập nhật thành công!');
        } catch (\Exception $e) {
            return back()->with('error', 'Có lỗi xảy ra, vui lòng thử lại.');
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        try {
            // Kiểm tra xem ảnh có tồn tại và có đường dẫn hợp lệ
            if ($post->img_post && Storage::exists($post->img_post)) {
                Storage::delete($post->img_post);
            }

            // Xóa bài viết
            $post->delete();

            return back()->with('success', 'Xóa thành công!');
        } catch (\Throwable $th) {
            return back()->with('error', 'Có lỗi xảy ra, vui lòng thử lại.');
        }
    }
}
