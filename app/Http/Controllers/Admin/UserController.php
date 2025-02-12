<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreUserRequest;
use App\Http\Requests\Admin\UpdateUserRequest;
use App\Models\Cinema;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    const PATH_VIEW = 'admin.users.';
    const PATH_UPLOAD = 'users';

    public function __construct()
    {
        $this->middleware('can:Danh sách tài khoản')->only('index');
        $this->middleware('can:Thêm tài khoản')->only(['create', 'store']);
        $this->middleware('can:Sửa tài khoản')->only(['edit', 'update']);
        $this->middleware('can:Xóa tài khoản')->only('destroy');
    }
    public function index()
    {
        $admin = User::where('type', 'admin')->with('cinema')->get();
        $users = User::where('type', 'member')->with('cinema')->get();
        $roles = Role::all();
        return view(self::PATH_VIEW . __FUNCTION__, compact('admin', 'roles','users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $typeAdmin = User::TYPE_ADMIN;
        $typeMember = User::TYPE_MEMBER;
        $genders = User::GENDERS;
        $roles = Role::all();
        $cinemas = Cinema::where('is_active', '1')->first('branch_id')->get();
        return view(self::PATH_VIEW . __FUNCTION__, compact(['typeAdmin', 'typeMember', 'genders', 'cinemas', 'roles']));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        try {
            // dd($request->all());
            $dataUser = $request->all();
            $dataUser['type'] = "admin";
            $dataUser['cinema_id'] = $request->cinema_id;
            if ($request->img_thumbnail) {
                $dataUser['img_thumbnail'] = Storage::put(self::PATH_UPLOAD, $request->img_thumbnail);
            }

            // Tạo người dùng mới
            $user = User::create($dataUser);

            // Gán vai trò cho người dùng
            if ($request->has('role_id')) {
                $user->assignRole($request->role_id);
            }

            return redirect()
                ->route('admin.users.index')
                ->with('success', 'Thêm mới thành công!');
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        $typeAdmin = User::TYPE_ADMIN;
        $typeMember = User::TYPE_MEMBER;
        $genders = User::GENDERS;
        $roles = Role::all();
        $cinemas = Cinema::where('is_active', '1')->first('branch_id')->get();
        return view(self::PATH_VIEW . __FUNCTION__, compact(['typeAdmin', 'typeMember', 'genders', 'user', 'roles', 'cinemas']));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $typeAdmin = User::TYPE_ADMIN;
        $typeMember = User::TYPE_MEMBER;
        $genders = User::GENDERS;
        $roles = Role::all();
        $cinemas = Cinema::where('is_active', '1')->first('branch_id')->get();

        return view(self::PATH_VIEW . __FUNCTION__, compact(['typeAdmin', 'typeMember', 'genders', 'user', 'cinemas', 'roles']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        try {
            $dataUser = $request->all();
            $dataUser['cinema_id'] = $request->cinema_id;
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

            if ($request->has('role_id')) {
                $user->roles()->sync($request->role_id);
            } else {
                // Nếu không có vai trò nào được chọn, xóa tất cả vai trò hiện tại
                $user->roles()->detach();
            }

            return redirect()
                ->back()
                ->with('success', 'Cập nhật thành công!');
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }
    public function resetPassword(Request $request, User $user)
    {

        //validate
        $request->validate([
            'password' => 'required|min:8|max:30|confirmed',
        ], [
            'password.required' => 'Vui lòng nhập mật khẩu.',
            'password.min' => 'Mật khẩu tối thiểu phải 8 ký tự.',
            'password.max' => 'Mật khẩu không được quá 30 ký tự.',
            'password.confirmed' => 'Mật khẩu và xác nhận mật khẩu không trùng khớp.',
        ]);

        try {
            $user->update([
                'password' => $request->password
            ]);
            return redirect()
                ->back()
                ->with('success', 'Đổi mật khẩu thành công!');
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        try {

            $user->delete();

            if ($user->img_thumbnail && Storage::exists($user->img_thumbnail)) {
                Storage::delete($user->img_thumbnail);
            }

            return back()
                ->with('success', 'Thao tác thành công!');
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }
}
