<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\Client\StoreContactRequest;
use Illuminate\Http\Request;
use App\Models\Contact;

class ContactController extends Controller
    {
        public function store(StoreContactRequest $request)
        {
            try {
                $data = $request->all();

                // Lưu dữ liệu vào bảng Contact
                Contact::query()->create($data);

                // Return success response for AJAX
                session()->flash('success', 'Thông tin liên hệ của bạn đã được gửi thành công!');

                // Trả về phản hồi thành công
                return response()->json(['success' => true]);
            } catch (\Throwable $th) {
                // Trả về thông báo lỗi dưới dạng JSON
                return response()->json([
                    'status' => 'error',
                    'message' => $th->getMessage()
                ], 500);
            }
        }
    }
