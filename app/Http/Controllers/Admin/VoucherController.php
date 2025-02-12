<?php

namespace App\Http\Controllers\Admin;

use App\Events\VoucherCreated;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreVoucherRequest;
use App\Http\Requests\Admin\UpdateVoucherRequest;
use App\Models\User;
use App\Models\UserVoucher;
use App\Models\Voucher;
use App\Models\VoucherConfig;
use Carbon\Carbon;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class VoucherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    const PATH_VIEW = 'admin.vouchers.';

    public function __construct()
    {
        $this->middleware('can:Danh sách vouchers')->only('index');
        $this->middleware('can:Thêm vouchers')->only(['create', 'store']);
        $this->middleware('can:Sửa vouchers')->only(['edit', 'update']);
        $this->middleware('can:Xóa vouchers')->only('destroy');
    }

    public function index()
    {
        $data = Voucher::where(function($query) {
            $query->whereNot(function($q) {
                $q->where('type', 2);
            });
        })->get();
        $discount = VoucherConfig::getValue('birthday_voucher', 50000);
        return view(self::PATH_VIEW . __FUNCTION__, compact('data','discount'));
    }

    public function updateDiscount(Request $request)
    {
        $request->validate([
            'discount' => 'required|numeric|min:1000'
        ]);

        VoucherConfig::updateValue('birthday_voucher', $request->discount);

        return redirect()->back()->with('success', 'Đã cập nhật giá trị giảm giá');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::all();

        $uniqueCode = $this->generateCode();

        return view(self::PATH_VIEW . __FUNCTION__, compact('users', 'uniqueCode'));
    }

    public function generateCode()
    {
        do {
            $code = strtoupper(Str::random(10));
            $codeExist = Voucher::where('code', $code)->exists();
        } while ($codeExist);
        return $code;
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreVoucherRequest $request)
    {
        $data = $request->all();

        $data['start_date_time'] = Carbon::parse($request->input('start_date_time'), 'Asia/Ho_Chi_Minh')->format('Y-m-d H:i:s');
        $data['end_date_time'] = Carbon::parse($request->input('end_date_time'), 'Asia/Ho_Chi_Minh')->format('Y-m-d H:i:s');
        $data['is_active'] = $request->has('is_active') ? 1 : 0;
        $data['is_publish'] = $request->has('is_publish') ? 1 : 0;

        DB::beginTransaction();

        try {
            $voucher = Voucher::create($data);

            // Gán voucher cho tất cả các user có trong hệ thống
            $users = User::all();
            $userVouchers = [];
            foreach ($users as $user) {
                $userVouchers[] = [
                    'user_id' => $user->id,
                    'voucher_id' => $voucher->id,
                    'usage_count' => 0,
                ];
            }

            // Chèn dữ liệu vào bảng UserVoucher
            UserVoucher::insert($userVouchers);

            // Kiểm tra điều kiện broadcast
            if ($voucher->is_publish && $voucher->is_active) {
                broadcast(new VoucherCreated($voucher))->toOthers();
            }

            // Commit transaction nếu không có lỗi
            DB::commit();

            return redirect()->route('admin.vouchers.index')->with('success', 'Thêm thành công!');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('admin.vouchers.index')->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
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
    public function edit(string $id)
    {
        $voucher = Voucher::query()->findOrFail($id);
        /*dd($voucher->start_date_time);*/
        return view(self::PATH_VIEW . __FUNCTION__, compact('voucher'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateVoucherRequest $request, string $id)
    {
        try {
            $data = $request->all();

            $data['start_date_time'] = Carbon::parse($request->input('start_date_time'), 'Asia/Ho_Chi_Minh')->format('Y-m-d H:i:s');
            $data['end_date_time'] = Carbon::parse($request->input('end_date_time'), 'Asia/Ho_Chi_Minh')->format('Y-m-d H:i:s');
            $data['is_active'] = $request->has('is_active') ? 1 : 0;
            $data['is_publish'] = $request->has('is_publish') ? 1 : 0;

            $voucher = Voucher::query()->findOrFail($id);

            $voucher->update($data);

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
    public function destroy(string $id)
    {
        $voucher = Voucher::findOrFail($id);

        $voucher->delete();
        return redirect()
            ->route('admin.vouchers.index')
            ->with('success', 'Xóa thành công!');
    }
}
