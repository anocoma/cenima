<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateMyAcountRequest;
use App\Http\Requests\Admin\UpdateUserRequest;
use App\Models\User;
use App\Notifications\PasswordChanged;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;

class MyAccountController extends Controller
{
    public
    const PATH_VIEW = 'admin.my-accounts.';
    const PATH_UPLOAD = 'users';
    public function show(){
        $userID = Auth::user()->id;
        $user = User::findOrFail($userID);
        return view(self::PATH_VIEW . __FUNCTION__, compact('user'));
    }
    public function edit(){
        $userID = Auth::user()->id;
        $user = User::findOrFail($userID);
        $genders = User::GENDERS;
        return view(self::PATH_VIEW . __FUNCTION__, compact('user','genders'));
    }

    public function update(UpdateMyAcountRequest $request)
    {
        $userID = Auth::user()->id;
        $user = User::findOrFail($userID);
        try {
            $dataUser = $request->all();


            if ($request->img_thumbnail) {
                $dataUser['img_thumbnail'] = Storage::put(self::PATH_UPLOAD, $request->img_thumbnail);
                // Lưu lại đường dẫn của ảnh hiện tại để so sánh sau
                $ImgThumbnailCurrent = $user->img_thumbnail;
            }

            $user->update($dataUser);

            // Nếu có ảnh mới và ảnh mới khác với ảnh cũ, xóa ảnh cũ khỏi hệ thống
            if (!empty($ImgThumbnailCurrent) && ($dataMovie['img_thumbnail'] ?? null) != $ImgThumbnailCurrent && Storage::exists($ImgThumbnailCurrent)) {
                Storage::delete($ImgThumbnailCurrent);
            }

            return redirect()
                ->back()
                ->with('success', 'Cập nhật thành công!');
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }

    public function changePassword(Request $request)
    {

        $request->validate([
            'old_password' =>'required',
            'password' => 'required|min:8|max:30|confirmed',
        ],[
            'old_password.required' =>'Vui lòng nhập mật khẩu hiện tại.',
            'password.required' =>'Vui lòng nhập mật khẩu.',
            'password.min' =>'Mật khẩu tối thiểu phải 8 ký tự.',
            'password.max' =>'Mật khẩu không được quá 30 ký tự.',
            'password.confirmed' =>'Mật khẩu và xác nhận mật khẩu không trùng khớp.',
        ]);

        $user = User::findOrFail(Auth::user()->id);

        // Kiểm tra xem mật khẩu hiện tại có khớp không
        if (!Hash::check($request->old_password, $user->password)) {
            return response()->json([
                'errors' => [
                    'old_password' => ['Mật khẩu hiện tại không chính xác.'],
                ]
            ], 422); // 422 là mã trạng thái cho dữ liệu không hợp lệ
        }

        // Cập nhật mật khẩu mới
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        // Gửi thông báo thay đổi mật khẩu thành công qua email
        Notification::send($user, new PasswordChanged());

        // Lưu thông báo vào session
        session()->flash('success', 'Đổi mật khẩu thành công!');

        // Trả về phản hồi thành công
        return response()->json(['success' => true]);
    }
}
