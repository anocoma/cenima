<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Cinema;
use App\Models\Combo;
use App\Models\Food;
use App\Models\Post;
use App\Models\SeatShowtime;
use App\Models\Showtime;
use App\Models\Slideshow;
use App\Models\Voucher;
use Illuminate\Http\Request;

class UpdateActiveController extends Controller
{
    public function branch(Request $request)
    {
        try {
            $branch = Branch::findOrFail($request->id);

            $branch->is_active = $request->is_active;
            $branch->save();
            $data = [
                'is_active' => $branch->is_active,
                'updated_date' => $branch->updated_at->format('d/m/Y'),
                'updated_time' => $branch->updated_at->format('H:i:s'),
            ];
            return response()->json(['success' => true, 'message' => 'Cập nhật thành công.', 'data' => $data]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Có lỗi xảy ra, vui lòng thử lại.']);
        }
    }
    public function cinema(Request $request)
    {
        try {
            $cinema = Cinema::findOrFail($request->id);

            $cinema->is_active = $request->is_active;
            $cinema->save();

            return response()->json(['success' => true, 'message' => 'Cập nhật thành công.', 'data' => $cinema]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Có lỗi xảy ra, vui lòng thử lại.', 'data' => $cinema]);
        }
    }
    public function food(Request $request)
    {
        try {
            $food = Food::findOrFail($request->id);

            $food->is_active = $request->is_active;
            $food->save();

            return response()->json(['success' => true, 'message' => 'Cập nhật thành công.', 'data' => $food]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Có lỗi xảy ra, vui lòng thử lại.', 'data' => $food]);
        }
    }
    public function combo(Request $request)
    {
        try {
            $combo = Combo::findOrFail($request->id);

            $combo->is_active = $request->is_active;
            $combo->save();

            return response()->json(['success' => true, 'message' => 'Cập nhật thành công.', 'data' => $combo]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Có lỗi xảy ra, vui lòng thử lại.', 'data' => $combo]);
        }
    }
    public function voucher(Request $request)
    {
        try {
            $voucher = Voucher::findOrFail($request->id);

            $voucher->is_active = $request->is_active;
            $voucher->save();

            return response()->json(['success' => true, 'message' => 'Cập nhật thành công.', 'data' => $voucher]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Có lỗi xảy ra, vui lòng thử lại.', 'data' => $voucher]);
        }
    }
    public function slideshow(Request $request)
    {
        $slideshow = Slideshow::findOrFail($request->id);

        // Không cho phép tắt slideshow hiện tại nếu nó đang được bật
        if ($slideshow->is_active && !$request->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn không thể tắt slideshow đang hoạt động!',
            ], 400);
        }

        if ($request->is_active) {
            // Tắt tất cả các slideshow khác
            Slideshow::where('is_active', 1)->update(['is_active' => 0]);
        }

        // Cập nhật trạng thái của slideshow hiện tại
        $slideshow->is_active = $request->is_active;
        $slideshow->save();

        return response()->json(['success' => true, 'message' => 'Cập nhật trạng thái thành công.']);
    }
    public function post(Request $request)
    {
        try {
            $post = Post::findOrFail($request->id);

            $post->is_active = $request->is_active;
            $post->save();

            return response()->json(['success' => true, 'message' => 'Cập nhật thành công.', 'data' => $post]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Có lỗi xảy ra, vui lòng thử lại.', 'data' => $post]);
        }
    }
    public function showtime(Request $request)
    {
        try {

            $showtime = Showtime::findOrFail($request->id);

            $timeNow = now();
            $seatShowtimes = SeatShowtime::where('showtime_id', $showtime->id)->pluck('status', 'id')->all();
            // dd($seatShowtime);
            $statusSeat = true;
            foreach ($seatShowtimes as $id => $status) {
                if ($status != "available") {
                    $statusSeat = false;
                    break;
                }
            }
            if ($statusSeat) {
                $showtime->is_active = $request->is_active;
                $showtime->save();

                $data = [
                    'is_active' => $showtime->is_active,
                ];

                return response()->json(['success' => true, 'message' => 'Cập nhật thành công.', 'data' => $data]);
            } else {
                return response()->json(['success' => false, 'message' => 'Cập nhật không thành công! Suất chiếu này đã/đang có người đặt!']);
            }
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Có lỗi xảy ra, vui lòng thử lại.']);
        }
    }




    //showtime và slideshow chưa xử lý
}
