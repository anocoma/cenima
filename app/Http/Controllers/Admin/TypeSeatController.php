<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreTypeSeatRequest;
use App\Http\Requests\Admin\UpdateTypeSeatRequest;
use App\Models\TypeSeat;
use Illuminate\Http\Request;

class TypeSeatController extends Controller
{
    const PATH_VIEW = 'admin.type_seats.'; // Sử dụng snake_case cho tên thư mục.

    public function index()
    {
        $typeSeats = TypeSeat::query()->latest('id')->get(); // Đổi tên biến cho dễ hiểu.
        return view(self::PATH_VIEW . __FUNCTION__, compact('typeSeats')); // Sử dụng tên biến mới.
    }

    public function create()
    {
        return view(self::PATH_VIEW . __FUNCTION__);
    }

    public function store(StoreTypeSeatRequest $request)
    {
        try {
            $data = $request->all();

            TypeSeat::query()->create($data);

            return redirect()
                ->route('admin.type_seats.index')
                ->with('success', 'Thêm thành công!');
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }

    public function show(string $id)
    {
        // Nên thêm code để xử lý phương thức này hoặc xóa bỏ nếu không dùng.
    }

    public function edit(TypeSeat $typeSeat) // Sử dụng camelCase cho biến.
    {
        return view(self::PATH_VIEW . __FUNCTION__, compact('typeSeat')); // Sử dụng tên biến mới.
    }

    public function update(UpdateTypeSeatRequest $request, TypeSeat $typeSeat) // Sử dụng camelCase.
    {
        try {
            $data = $request->all();

            $typeSeat->update($data); // Sử dụng tên biến mới.

            return redirect()
                ->back()
                ->with('success', 'Cập nhật thành công!');
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }

    public function destroy(TypeSeat $typeSeat) // Sử dụng camelCase.
    {
        try {
            $typeSeat->delete(); // Sử dụng tên biến mới.
            
            return back()
                ->with('success', 'Xóa thành công');
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }
}
