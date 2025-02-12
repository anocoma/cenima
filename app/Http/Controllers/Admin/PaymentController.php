<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StorePaymentRequest;
use App\Http\Requests\Admin\UpdatePaymentRequest;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    const PATH_VIEW = 'admin.payments.';
    public function __construct()
    {
        $this->middleware('can:Danh sách thanh toán')->only('index');
        $this->middleware('can:Thêm thanh toán')->only(['create', 'store']);
        $this->middleware('can:Sửa thanh toán')->only(['edit', 'update']);
        $this->middleware('can:Xóa thanh toán')->only('destroy');
    }
    public function index()
    {
        $payments = Payment::query()->latest('id')->get();
        return view(self::PATH_VIEW. __FUNCTION__, compact('payments'));
    }

    public function create()
    {
        return view(self::PATH_VIEW. __FUNCTION__);
    }
    public function store(StorePaymentRequest $request){
        try{
            $data = $request->all();

            Payment::query()->create($data);

            return redirect()
                ->route('admin.payments.index')
                ->with('success', 'Thêm thành công');
        }catch(\Throwable $th){
            return back()->with('error', $th->getMessage());
        }
    }   
    public function show(string $id){

    }
    public function edit(Payment $payment){
        return view(self::PATH_VIEW . __FUNCTION__, compact('payment'));
    }
    public function update(UpdatePaymentRequest $request, Payment $payment){
        try{
            $data = $request->all();

            $payment->update($data);

            return redirect()
                ->back()
                ->with('success', 'Cập nhật thành công');
        }catch(\Throwable $th){
            return back()->with('error', $th->getMessage());
        }
    }
    public function destroy(Payment $payment){
        try{
            $payment->delete();

            return back()->with('success', 'Xóa thành công');
        }catch(\Throwable $th){
            return back()->with('error', $th->getMessage());
        }
    }
}
