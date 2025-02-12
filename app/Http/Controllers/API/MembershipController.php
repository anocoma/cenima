<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Membership;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class MembershipController extends Controller
{
    public function applyPoint(Request $request)
    {
        try {
            // Xác thực dữ liệu đầu vào
            $validator = Validator::make($request->all(), [
                'use_points' => [
                    'required',
                    'integer',
                    'min:' . Membership::MIN_USE_POINT,
                    'regex:/^(0|[1-9][0-9]*000)$/', //regax số phải chia hết 1000
                ],
            ], [
                'use_points.required' => 'Số điểm sử dụng là bắt buộc.',
                'use_points.integer' => 'Số điểm sử dụng phải là một số nguyên.',
                'use_points.min' => 'Số điểm sử dụng phải lớn hơn hoặc bằng ' . Membership::MIN_USE_POINT . '.',
                'use_points.regex' => 'Số điểm sử dụng phải chia hết cho 1000.',
            ]);

            // Kiểm tra xem có lỗi không
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'error' => $validator->errors(),

                ], Response::HTTP_UNPROCESSABLE_ENTITY); // 422
            }
            $userId = auth()->id();
            if (session()->has('customer')) {
                $userId = session('customer');
            }
            $membership = Membership::where('user_id', $userId)->first();

            // Kiểm tra xem số điểm sử dụng có lớn hơn số điểm hiện có không
            if ($membership->points < $request->use_points) {
                return response()->json([
                    'success' => false,
                    'message' => 'Số điểm sử dụng vượt quá số điểm bạn hiện có.',
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
            $point_discount = $request->use_points * Membership::CONVERSION_RATE;
            $paymentPoint = [
                'user_id' => $userId,
                'membership_code' => $membership->code,
                'total_points' => $membership->points,
                'use_points' => $request->use_points,
                'point_discount' => $point_discount
            ];
            session(['payment_point' => $paymentPoint]);
            return response()->json([
                'success' => true,
                'use_points' => $request->use_points,
                'point_discount' => $point_discount,
            ]);
        } catch (\Exception $e) {
            // Xử lý ngoại lệ
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra. Vui lòng thử lại sau.',
                'error' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    public function cancelPoint(Request $request)
    {
        $request->session()->forget('payment_point');
        return response()->json(['message' => 'Hủy sử dụng điểm thành công.']);
    }
}
