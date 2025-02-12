<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreContactRequest;
use Illuminate\Http\Request;
use App\Models\Contact;

class ContactController extends Controller
{

    const PATH_VIEW = 'admin.contacts.';
    public function __construct()
    {
        $this->middleware('can:Danh sách liên hệ')->only('index');
        $this->middleware('can:Thêm liên hệ')->only(['create', 'store']);
        $this->middleware('can:Sửa liên hệ')->only(['edit', 'update']);
        $this->middleware('can:Xóa liên hệ')->only('destroy');
    }


    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, Contact $contact)
    {
        $contacts = Contact::query()->latest('id')->get();
        return view(self::PATH_VIEW . __FUNCTION__, compact('contacts'));
    }

    /**
     * Store a newly created resource in storage.
     */


     public function create()
     {
        $status = Contact::STATUS;
        return view(self::PATH_VIEW . __FUNCTION__, compact('status'));
     }


    public function store(StoreContactRequest $request)
    {
        try{
            $data = $request->all();

            Contact::query()->create($data);

            return redirect()
                ->route('admin.contacts.index')
                ->with('success', 'Thêm thành công');
        }catch(\Throwable $th){
            return back()->with('error', $th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */

    public function show(Contact $contact)
    {
        return response()->json(['contact' => $contact]);
    }

    public function edit(Contact $contact){
        $status = Contact::STATUS;
        // Loại bỏ 'Chưa xử lí' nếu trạng thái hiện tại là 'Đã xử lí'
        if ($contact->status === 'resolved') {
            unset($status['pending']);
        }
        return view(self::PATH_VIEW . __FUNCTION__, compact('contact','status'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Contact $contact)
    {
        try{
            $data = $request->all();

            $contact->update($data);

            return redirect()
                ->back()
                ->with('success', 'Cập nhật thành công');
        }catch(\Throwable $th){
            return back()->with('error', $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Contact $contact)
    {
        try{
            $contact->delete();

            return back()->with('success', 'Xóa thành công');
        }catch(\Throwable $th){
            return back()->with('error', $th->getMessage());
        }
    }

    public function updateStatus(Request $request, Contact $contact)
    {
        try {
            $contact->update(['status' => $request->status]);
            return response()->json(['success' => 'Trạng thái cập nhật thành công!']);
        } catch (\Throwable $th) {
            return response()->json(['error' => 'Có lỗi xảy ra khi cập nhật trạng thái!'], 500);
        }
    }
}
