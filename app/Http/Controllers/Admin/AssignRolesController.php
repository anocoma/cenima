<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateAssignRoleRequest;
// use DB;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class AssignRolesController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    const PATH_VIEW = 'admin.assign-roles.';
    const PATH_UPLOAD = 'assign-roles';
    public function index()
    {
        //
        $users = User::where('type', 'admin')->with('roles', 'cinema')->get(); // Lấy danh sách người dùng cùng với vai trò
        $roles = Role::all(); // Lấy tất cả các vai trò
        return view(self::PATH_VIEW . __FUNCTION__,  compact('users', 'roles'));
    }

    /**
     * Show the form for creating a new resource.
     */

    public function update(Request $request, User $user)
    {
        // dd($user);
        try {
            $user = User::find($request->user_id);
            if (!$request->has('role_id') || !is_array($request->role_id) || empty($request->role_id)) {
                // Xóa tất cả vai trò 
                $user->syncRoles([]);
                return redirect()
                ->back()
                ->with('success', 'Xóa vai trò thành công!');

            } else {
              
                $roles = Role::whereIn('id', $request->role_id)->get();
                $user->syncRoles($roles);
            }


            return redirect()
                ->back()
                ->with('success', 'Gán vai trò thành công!');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Gán vai trò không thành công: ' . $e->getMessage());
        }
    }


    public function destroy(string $id)
    {
        //
    }
}
