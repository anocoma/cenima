<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreRoomRequest;
use App\Models\Branch;
use App\Models\Cinema;
use App\Models\Room;
use App\Models\Seat;
use App\Models\SeatTemplate;
use App\Models\TypeRoom;
use App\Models\TypeSeat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class RoomController extends Controller
{
    const PATH_VIEW = 'admin.rooms.';
    const PATH_UPLOAD = 'rooms';
    public function __construct()
    {
        $this->middleware('can:Danh sách phòng chiếu')->only('index');
        $this->middleware('can:Thêm phòng chiếu')->only(['create', 'store']);
        $this->middleware('can:Sửa phòng chiếu')->only(['edit', 'update']);
        $this->middleware('can:Xóa phòng chiếu')->only('destroy');
        $this->middleware('can:Xem chi tiết phòng chiếu')->only('show');
    }
    public function index()
    {

        if (!session()->has('rooms.selected_tab')) {
            session(['rooms.selected_tab' => 'publish']); // Tab mặc định
        }

        $rooms = Room::query()->with(['typeRoom', 'cinema', 'seats'])->latest('id')->get();
        $branches = Branch::where('is_active',1)->get();
        $typeRooms = TypeRoom::pluck('name', 'id')->all();
        if (Auth::user()->cinema_id == "") {
            $cinemas = Cinema::all();
        } else {
            $cinemas = Cinema::where('id', Auth::user()->cinema_id)->get();
        }


        $seatTemplates = SeatTemplate::where('is_publish', 1)
            ->where('is_active', 1)
            ->pluck('name', 'id')
            ->all();
        return view(self::PATH_VIEW . __FUNCTION__, compact('rooms', 'branches', 'typeRooms', 'cinemas', 'seatTemplates'));
    }


    public function show(Room $room)
    {
        $matrixKey = array_search($room->matrix_id, array_column(SeatTemplate::MATRIXS, 'id'));
        $matrixSeat = SeatTemplate::MATRIXS[$matrixKey];
        $seats = Seat::where(['room_id' => $room->id])->get();
        $typeRooms = TypeRoom::pluck('name', 'id')->all();
        return view(self::PATH_VIEW . __FUNCTION__, compact('typeRooms', 'room', 'seats', 'matrixSeat'));
    }

    public function edit(Room $room)
    {
        $matrixSeat = SeatTemplate::getMatrixById($room->seatTemplate->matrix_id);
        $seats = Seat::where(['room_id' => $room->id])->get();
        $seatMap = [];
        foreach ($seats as $seat) {
            $seatMap[$seat->coordinates_y][$seat->coordinates_x] = $seat;
        }
        $typeRooms = TypeRoom::pluck('name', 'id')->all();
        $typeSeats = TypeSeat::pluck('name', 'id')->all();
        $totalSeat = Seat::getTotalSeat($room->id);
        $seatBroken = Seat::getTotalSeat($room->id,0);
        return view(self::PATH_VIEW . __FUNCTION__, compact('typeRooms', 'room', 'seatMap', 'matrixSeat', 'typeSeats','totalSeat','seatBroken'));
    }
    public function update(Request $request, Room $room)
    {

        try {
            DB::transaction(function () use ($request, $room) {
                if ($request->action == "publish" && !$room->is_publish) {

                    $room->update([
                        'is_publish' => 1,
                        'is_active' => 1,
                    ]);

                    $dataSeats = $request->seats;

                    $seats = Seat::whereIn('id', array_keys($dataSeats))->get();

                    foreach ($seats as $seat) {
                        $seat->update([
                            'is_active' => $dataSeats[$seat->id],
                        ]);
                    }
                } else {
                    $room->update([
                        'is_active' => isset($request->is_active) ? 1 : 0,
                    ]);
                    $dataSeats = $request->seats;

                    $seats = Seat::whereIn('id', array_keys($dataSeats))->get();

                    foreach ($seats as $seat) {
                        $seat->update([
                            'is_active' => $dataSeats[$seat->id] ?? 0,
                        ]);
                    }
                }
            });


            return redirect()->back()->with('success', 'Thao tác thành công!');
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }
    public function destroy(Room $room)
    {
        try {
            if (!$room->is_publish || $room->showtimes()->doesntExist()) {
                Seat::where('room_id', $room->id)->delete();
                $room->delete();
                return redirect()->back()->with('success', 'Thao tác thành công!');
            }
            return redirect()->back()->with('error', 'Phòng chiếu đã đi vào sử dụng, không thể xóa!');


        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }

    public  function selectedTab(Request $request){
        $tabKey = $request->tab_key;
        session(['rooms.selected_tab' => $tabKey]);
        return response()->json(['message' => 'Tab saved', 'tab' => $tabKey]);
    }
}
