<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreRankRequest;
use App\Http\Requests\Admin\UpdateRankRequest;
use App\Models\Membership;
use App\Models\Rank;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class RankController extends Controller
{
    const PATH_VIEW = 'admin.ranks.';
    public function __construct()
    {
        $this->middleware('can:Thẻ thành viên')->only('index', 'store', 'update', 'destroy', 'updateRankMembership');
    }
    public function index()
    {
        $ranks = Rank::orderBy('total_spent', 'asc')->get();
        return view(self::PATH_VIEW . __FUNCTION__, compact('ranks'));
    }
    public function store(StoreRankRequest $request)
    {
        try {
            if (Rank::count() < Rank::MAX_RANK) {
                DB::transaction(function () use ($request) {

                    $dataRank = [
                        'name' => $request->name,
                        'total_spent' => $request->total_spent,
                        'ticket_percentage' => $request->ticket_percentage,
                        'combo_percentage' => $request->combo_percentage
                    ];
                    Rank::create($dataRank);
                    $this->updateRankMembership();
                });
                return redirect()->back()->with('success', 'Thao tác thành công!');
            }
            return redirect()->back()->with('error', 'Số lượng cấp bậc đã đạt đến tối đa, không thể thêm mới.');
        } catch (\Exception $e) {
            return back()->with('error', 'Có lỗi xảy ra, vui lòng thử lại.');
        }
    }
    public function update(UpdateRankRequest $request, Rank $rank)
    {
        try {
            DB::transaction(function () use ($request, $rank) {

                $dataRank = [
                    'name' => $request->name,
                    'ticket_percentage' => $request->ticket_percentage,
                    'combo_percentage' => $request->combo_percentage,
                ];

                // Chỉ thêm `total_spent` nếu không phải là rank mặc định
                if (!$rank->is_default) {
                    $dataRank['total_spent'] = $request->total_spent;
                }

                $rank->update($dataRank);
                $this->updateRankMembership();
            });

            session()->flash('success', 'Thao tác thành công');
            return response()->json(['message' => "Thao tác thành công"], Response::HTTP_OK); // 200

        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR); // 500
        }
    }



    public function destroy(Rank $rank)
    {
        // try {
        // Kiểm tra xem tổng số bản ghi trong bảng Rank có nhỏ hơn 2 không
        DB::transaction(function () use ($rank) {
            if (Rank::count() <= 2) {
                return redirect()->back()->with('error', 'Số lượng cấp bậc đã đạt đến tối tiểu, không thể xóa');
            }

            // Kiểm tra nếu $rank có is_default = true
            if ($rank->is_default) {
                return redirect()->back()->with('error', 'Không thể xóa cấp bậc mặc định.');
            }

            // Thực hiện xóa nếu các điều kiện thỏa mãn
            $rank->delete();
            $this->updateRankMembership();
        });

        return redirect()->back()->with('success', 'Xóa cấp bậc thành công!');
        // } catch (\Exception $e) {
        //     return redirect()->back()->with('error', 'Có lỗi xảy ra, vui lòng thử lại.');
        // }
    }
    public function updateRankMembership()
    {

        $ranks = Rank::orderBy('total_spent', 'asc')->get();

        // Lấy tất cả các membership cần kiểm tra và cập nhật
        $memberships = Membership::all();

        foreach ($memberships as $membership) {
            // Tìm rank phù hợp nhất với tổng chi tiêu của từng membership
            //newRank sẽ là rank cao nhất mà membership có thể đạt được dựa trên total_spent
            $newRank = $ranks->last(function ($rank) use ($membership) {
                return $membership->total_spent >= $rank->total_spent;
            });

            // Kiểm tra nếu rank mới khác rank hiện tại
            if ($newRank && $membership->rank_id !== $newRank->id) {
                $membership->rank_id = $newRank->id;
                $membership->save();
            }
        }
    }
}
