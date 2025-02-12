<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StorePermissionRequest;
use App\Http\Requests\Admin\UpdatePermissionRequest;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    const PATH_VIEW = 'admin.permissions.';
    const PATH_UPLOAD = 'permissions';
    // public function index()
    // {
    //     //
    //     $permissions = Permission::latest('id')->get();
    //     return view(self::PATH_VIEW . __FUNCTION__, compact('permissions'));
    // }

    // public function create()
    // {
    //     //
    //     return view(self::PATH_VIEW . __FUNCTION__);
    // }

    // public function store(StorePermissionRequest $request)
    // {
    //     //
    //     try {
    //         Permission::create(['name' => $request->name]);
    //         return redirect()
    //             ->route('admin.permissions.index')
    //             ->with('success', 'Thêm mới thành công!');
    //     } catch (\Throwable $th) {
    //         return back()->with('error', $th->getMessage());
    //     }
    // }

    // /**
    //  * Display the specified resource.
    //  */
    // public function show(string $id)
    // {
    //     //
    // }

    // /**
    //  * Show the form for editing the specified resource.
    //  */
    // public function edit(Permission $permission)
    // {
    //     //
    //     return view(self::PATH_VIEW . __FUNCTION__, compact('permission'));
    // }

    // /**
    //  * Update the specified resource in storage.
    //  */
    // public function update(UpdatePermissionRequest $request, Permission $permission)
    // {
    //     //
    //     try {
    //         $permission->update(['name' => $request->name]);
    //         return redirect()
    //             ->route('admin.permissions.index')
    //             ->with('success', 'Cập nhật thành công!');
    //     } catch (\Throwable $th) {
    //         return back()->with('error', $th->getMessage());
    //     }
    // }

    // /**
    //  * Remove the specified resource from storage.
    //  */
    // public function destroy(Permission $permission)
    // {
    //     //
    //     try {
    //         $permission->delete();
    //         return redirect()
    //             ->route('admin.permissions.index')
    //             ->with('success', 'Xóa thành công!');
    //     } catch (\Throwable $th) {
    //         return back()->with('error', $th->getMessage());
    //     }
    // }
}
