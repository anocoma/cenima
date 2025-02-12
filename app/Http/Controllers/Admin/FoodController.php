<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreFoodRequest;
use App\Http\Requests\Admin\UpdateFoodRequest;
use App\Models\Food;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FoodController extends Controller
{
    const PATH_VIEW = 'admin.food.';
    const PATH_UPLOAD = 'food';

    /**
     * Display a listing of the resource.
     */
    public function __construct()
    {
        $this->middleware('can:Danh sách đồ ăn')->only('index');
        $this->middleware('can:Thêm đồ ăn')->only(['create', 'store']);
        $this->middleware('can:Sửa đồ ăn')->only(['edit', 'update']);
        $this->middleware('can:Xóa đồ ăn')->only('destroy');
    }

    public function index()
    {
        $data = Food::query()->latest('id')->get();
        return view(self::PATH_VIEW . __FUNCTION__, compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $types = Food::TYPES;
        return view(self::PATH_VIEW . __FUNCTION__, compact('types'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreFoodRequest $request)
    {
        try {
            $data = $request->all();
            $data['is_active'] ??= 0;
            if ($data['img_thumbnail']) {
                $data['img_thumbnail'] = Storage::put(self::PATH_UPLOAD, $data['img_thumbnail']);
            }

            Food::query()->create($data);

            return redirect()
                ->route('admin.food.index')
                ->with('success', 'Thêm thành công!');
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Food $food)
    {
        $types = Food::TYPES;
        return view(self::PATH_VIEW . __FUNCTION__, compact('food', 'types'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFoodRequest $request, Food $food)
    {
        try {
            $data = $request->all();
            $data['is_active'] ??= 0;

            // Kiểm tra nếu người dùng có tải lên ảnh mới
            if (!empty($data['img_thumbnail'])) {
                // Lưu ảnh mới và lấy đường dẫn
                $data['img_thumbnail'] = Storage::put(self::PATH_UPLOAD, $data['img_thumbnail']);

                // Lưu lại đường dẫn của ảnh hiện tại để so sánh sau
                $ImgThumbnailCurrent = $food->img_thumbnail;
            } else {
                // Nếu không có ảnh mới, giữ nguyên ảnh cũ
                unset($data['img_thumbnail']);
            }

            $food->update($data);

            // Nếu có ảnh mới và ảnh mới khác với ảnh cũ, xóa ảnh cũ khỏi hệ thống
            if (!empty($ImgThumbnailCurrent) && ($data['img_thumbnail'] ?? null) != $ImgThumbnailCurrent && Storage::exists($ImgThumbnailCurrent)) {
                Storage::delete($ImgThumbnailCurrent);
            }

            return redirect()
                ->back()
                ->with('success', 'Cập nhật thành công!');
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Food $food){
        try {
            if ($food->combos()->count() > 0) {
                return back()->with('error', 'Không thể xóa đồ ăn vì đã có combo đang sử dụng.');
            }
            $food->delete();
            if ($food->img_thumbnail && Storage::exists($food->img_thumbnail)) {
                Storage::delete($food->img_thumbnail);
            }

            return back()->with('success', 'Xóa đồ ăn thành công');
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }
}
