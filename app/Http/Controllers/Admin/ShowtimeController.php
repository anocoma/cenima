<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreShowtimeRequest;
use App\Http\Requests\Admin\UpdateShowtimeRequest;
use App\Models\Branch;
use App\Models\Cinema;
use App\Models\Movie;
use App\Models\MovieVersion;
use App\Models\Room;
use App\Models\Seat;
use App\Models\SeatShowtime;
use App\Models\SeatTemplate;
use App\Models\Showtime;
use App\Models\TypeRoom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class ShowtimeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    const PATH_VIEW = 'admin.showtimes.';
    const PATH_UPLOAD = 'showtimes';
    public function __construct()
    {
        $this->middleware('can:Danh sách suất chiếu')->only('index');
        $this->middleware('can:Thêm suất chiếu')->only(['create', 'store']);
        $this->middleware('can:Sửa suất chiếu')->only(['edit', 'update']);
        $this->middleware('can:Xóa suất chiếu')->only('destroy');
        $this->middleware('can:Xem chi tiết suất chiếu')->only('show');
    }


    public function index(Request $request)
    {

        $user = Auth::user();
        if ($user->cinema_id == "") {
            $defaultBranchId = Branch::where('is_active', 1)->first()?->id ?? null;
            $defaultCinemaId = Cinema::where('branch_id', $defaultBranchId)->where('is_active', 1)->first()?->id ?? null;
            $defaultDate = now()->format('Y-m-d');
            $defaultIsActive = null;
        } else {
            $defaultBranchId = $user->cinema->branch_id;

            $defaultCinemaId = $user->cinema_id;
            // dd($defaultCinemaId);
            $defaultDate = now()->format('Y-m-d');
            $defaultIsActive = null;
        }


        // Lấy giá trị từ session hoặc sử dụng mặc định nếu session chưa có
        if ($user->cinema_id != "") {
            $branchId = $user->cinema->branch_id;
            $cinemaId = $user->cinema_id;
        } else {
            $branchId = $request->input('branch_id', session('showtime.branch_id', $defaultBranchId));
            $cinemaId = $request->input('cinema_id', session('showtime.cinema_id', $defaultCinemaId));
        }
        $date = $request->input('date', session('showtime.date', $defaultDate));
        $isActive = $request->input('is_active', session('showtime.is_active', $defaultIsActive));

        // Lưu vào session
        session([
            'showtime.branch_id' => $branchId,
            'showtime.cinema_id' => $cinemaId,
            'showtime.date' => $date,
            'showtime.is_active' => $isActive
        ]);

        // dd(sess)

        //Thiếu where is_active
        $branches = Branch::where('is_active', '1')->get();
        $cinemas = Cinema::where('branch_id', $branchId)->where('is_active', '1')->get();

        $showtimesQuery = Showtime::where('cinema_id', $cinemaId)
            ->whereDate('date', $date);

        if ($isActive !== null) {
            $showtimesQuery->where('is_active', $isActive);
        }
        $showtimes = $showtimesQuery->with(['movie', 'room', 'movieVersion'])->latest('id')->get();
        // dd($showtimes);

        $timeNow = Carbon::now('Asia/Ho_Chi_Minh')->format('d-m-Y H:i:s');
        return view(self::PATH_VIEW . __FUNCTION__, compact('showtimes', 'branches', 'cinemas', 'timeNow', 'branchId', 'cinemaId', 'date', 'isActive'));
    }


    public function create()
    {

        $movies = Movie::where('is_active', '1')->where('is_publish', '1')->get();
        $typeRooms = TypeRoom::all();
        $branches = Branch::where('is_active', '1')->get();
        $user = auth()->user();

        $rooms = Room::with('typeRoom', 'seats')->where('is_active', '1')->where('cinema_id', $user->cinema_id)->get();

        $cleaningTime = Showtime::CLEANINGTIME;
        return view(self::PATH_VIEW . __FUNCTION__, compact('movies', 'typeRooms', 'cleaningTime', 'branches', 'rooms'));
    }


    public function store(StoreShowtimeRequest $request)
    {
        try {
            DB::transaction(function () use ($request) {
                $movieVersion = MovieVersion::find($request->movie_version_id);
                $room = Room::find($request->room_id);
                $typeRoom = TypeRoom::find($room->type_room_id);
                $movie = Movie::find($request->movie_id);
                $movieDuration = $movie ? $movie->duration : 0;
                $cleaningTime = Showtime::CLEANINGTIME;
                $user = auth()->user();
                if ($user->cinema_id != "") {
                    $branchId = $user->cinema->branch_id;
                    $cinemaId = $user->cinema_id;
                } else {
                    $branchId = $request->branch_id;
                    $cinemaId = $request->cinema_id;
                }


                // Lấy các suất chiếu hiện có trong phòng và ngày được chọn
                $existingShowtimes = Showtime::where('room_id', $request->room_id)
                    ->where('date', $request->date)
                    ->get();

                $dateShowtime = Carbon::parse($request->date);
                if (!$dateShowtime->between($movie->release_date, $movie->end_date)) {
                    // dd('ko nằm trong khoảng này');
                    $movie->is_special = "1";
                    $movie->save();
                }
                session([
                    'showtime.branch_id' => $branchId,
                    'showtime.cinema_id' => $cinemaId,
                    'showtime.date' => $request->date,
                    // 'showtime.is_active' => 0,
                ]);

                if ($request->has('auto_generate_showtimes')) {
                    //
                    $inputStartHour = $request->input('start_hour'); // Giờ mở cửa
                    $inputEndHour = $request->input('end_hour'); // Giờ đóng cửa

                    //giờ mở cửa, giờ đóng cửa 
                    $startTime = \Carbon\Carbon::parse($request->date . ' ' . $inputStartHour);
                    $endOfDay = \Carbon\Carbon::parse($request->date . ' ' . $inputEndHour);

                    // Kiểm tra nếu giờ mở cửa hoặc đóng cửa trong quá khứ
                    if ($startTime->isPast() || $endOfDay->isPast()) {
                        return back()->with('error', "Giờ mở cửa và giờ đóng cửa phải nằm trong tương lai.");
                    }

                    // Lặp nếu giờ mở cửa < giờ đóng cửa
                    while ($startTime->lt($endOfDay)) {

                        $endTime = $startTime->copy()->addMinutes($movieDuration + $cleaningTime);

                        // Biến kiểm tra trùng lặp
                        $isOverlap = false;


                        foreach ($existingShowtimes as $showtime) {
                            if (
                                $endTime->gt($showtime->start_time) ||
                                $endTime->between($showtime->start_time, $showtime->end_time)
                            ) {
                                $isOverlap = true; // Đánh dấu là bị trùng
                                break;             // Thoát khỏi foreach
                            }
                        }
                        if ($isOverlap) {
                            break;
                        }

                        // foreach ($existingShowtimes as $showtime) {
                        //     if ($startTime->lt($showtime->end_time) && $endTime->gt($showtime->start_time)) {
                        //         throw new \Exception("Thời gian chiếu bị trùng lặp với suất chiếu khác.");
                        //     }
                        //     // if (
                        //     //     $endTime->gt($showtime->start_time) ||
                        //     //     $endTime->between($showtime->start_time, $showtime->end_time)
                        //     // ) {
                        //     //     dd('Bị trùng vs suất đang có');
                        //     //     break;
                        //     // }
                        // }


                        $dataShowtimes = [
                            'cinema_id' => $request->cinema_id ?? $user->cinema_id,
                            'room_id' => $request->room_id,
                            'slug' => Showtime::generateCustomRandomString(),
                            'format' => $typeRoom->name . ' ' . $movieVersion->name,
                            'movie_version_id' => $request->movie_version_id,
                            'movie_id' => $request->movie_id,
                            'date' => $request->date,
                            'start_time' => $startTime->format('Y-m-d H:i'),
                            'end_time' => $endTime->format('Y-m-d H:i'),
                            // 'is_active' => $request->has('is_active') ? 1 : 0,
                            'is_active' => 1,
                        ];

                        $showtime = Showtime::create($dataShowtimes);


                        $seats = Seat::where('room_id', $room->id)->get();
                        $seatShowtimes = [];
                        foreach ($seats as $seat) {
                            $cinemaPrice = $room->cinema->surcharge;
                            $moviePrice = $movie->surcharge;
                            $typeRoomPrice = $typeRoom->surcharge;
                            $typeSeat = $seat->typeSeat->price;

                            $price = $cinemaPrice + $moviePrice + $typeRoomPrice + $typeSeat;
                            $status = $seat->is_active == 0 ? 'broken' : 'available';

                            $seatShowtimes[] = [
                                'showtime_id' => $showtime->id,
                                'seat_id' => $seat->id,
                                'status' => $status,
                                'price' => $price
                            ];
                        }

                        SeatShowtime::insert($seatShowtimes);


                        //startTime suất chiếu mới
                        $startTime = $endTime;

                        // Làm tròn startTime đến số đẹp chia hết cho 5
                        $minute = $startTime->minute;
                        $roundedMinute = ceil($minute / 5) * 5;
                        $startTime->minute($roundedMinute)->second(0);

                        // Nếu làm tròn phút vượt quá 59, tăng giờ và đặt phút về 00
                        if ($roundedMinute >= 60) {
                            $startTime->addHour()->minute(0);
                        }


                        
                    }
                } else {
                    if (empty($request->start_time)) {
                        return back()->with('error', 'Bạn phải nhập ít nhất một Giờ chiếu khi thêm suất chiếu thủ công.');
                    }
                    // Thêm suất chiếu theo cách thủ công
                    foreach ($request->start_time as $i => $startTimeChild) {
                        $startTime = \Carbon\Carbon::parse($request->date . ' ' . $startTimeChild);

                        $endTime = $startTime->copy()->addMinutes($movieDuration + $cleaningTime);

                        foreach ($existingShowtimes as $showtime) {
                            if ($startTime->lt($showtime->end_time) && $endTime->gt($showtime->start_time)) {
                                throw new \Exception("Thời gian chiếu bị trùng lặp với suất chiếu khác.");
                            }
                        }

                        $dataShowtimes = [
                            'cinema_id' => $request->cinema_id ?? $user->cinema_id,
                            'room_id' => $request->room_id,
                            'slug' => Showtime::generateCustomRandomString(),
                            'format' => $typeRoom->name . ' ' . $movieVersion->name,
                            'movie_version_id' => $request->movie_version_id,
                            'movie_id' => $request->movie_id,
                            'date' => $request->date,
                            'start_time' => $startTime->format('Y-m-d H:i'),
                            'end_time' => $endTime->format('Y-m-d H:i'),
                            // 'is_active' => $request->has('is_active') ? 1 : 0,
                            'is_active' => 1,
                        ];

                        $showtime = Showtime::create($dataShowtimes);

                        $seats = Seat::where('room_id', $room->id)->get();
                        $seatShowtimes = [];
                        foreach ($seats as $seat) {
                            $cinemaPrice = $room->cinema->surcharge;
                            $moviePrice = $movie->surcharge;
                            $typeRoomPrice = $typeRoom->surcharge;
                            $typeSeat = $seat->typeSeat->price;

                            $price = $cinemaPrice + $moviePrice + $typeRoomPrice + $typeSeat;
                            $status = $seat->is_active == 0 ? 'broken' : 'available';

                            $seatShowtimes[] = [
                                'showtime_id' => $showtime->id,
                                'seat_id' => $seat->id,
                                'status' => $status,
                                'price' => $price
                            ];
                        }
                        SeatShowtime::insert($seatShowtimes);
                    }
                }
            });

            session()->flash('success', 'Thêm mới thành công!');
            return response()->json(['success' => true, 'message' => 'Thêm mới thành công!']);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'error' => $th->getMessage()], 500);
        }
    }


    public function show(Showtime $showtime)
    {
        //dd($showtime);
        $showtime->load(['room.cinema', 'room.seatTemplate', 'movieVersion', 'movie', 'seats']);

        $matrixSeat = SeatTemplate::getMatrixById($showtime->room->seatTemplate->matrix_id);
        $seats =  $showtime->seats;

        $seatMap = [];
        foreach ($seats as $seat) {
            $seatMap[$seat->coordinates_y][$seat->coordinates_x] = $seat;
        }
        // dd($matrixSeat);
        // dd($showtime->toArray());

        return view(self::PATH_VIEW . __FUNCTION__, compact('showtime', 'matrixSeat', 'seats', 'seatMap'));
    }



    public function edit(Showtime $showtime)
    {
        $timeNow = now();
        $seatShowtimes = SeatShowtime::where('showtime_id', $showtime->id)->pluck('status', 'id')->all();
        // dd($seatShowtime);
        $statusSeat = true;
        foreach ($seatShowtimes as $id => $status) {
            if ($status != "available") {
                $statusSeat = false;
                break;
            }
        }
        if ($statusSeat) {
            if (!$timeNow->greaterThan($showtime->start_time)) {
                $showtimes = Showtime::with(['room', 'movieVersion'])->get();

                $movies = Movie::where('is_active', '1')->get();
                $user = auth()->user();
                if ($user->cinema_id == "") {
                    $rooms = Room::where('is_active', '1')->with(['cinema'])->first('id')->get();
                } else {
                    $rooms = Room::with('typeRoom', 'seats')->where('is_active', '1')->where('cinema_id', $user->cinema_id)->get();
                }
                $movieVersions = MovieVersion::all();
                $cinemas = Cinema::where('is_active', '1')->with(['branch'])->first('id')->get();
                $branches = Branch::where('is_active', '1')->get();

                $movieDuration = $showtime->movie->duration;


                $cleaningTime = Showtime::CLEANINGTIME;
                return view(self::PATH_VIEW . __FUNCTION__, compact('movies', 'rooms', 'movieVersions', 'cinemas', 'cleaningTime', 'branches', 'showtime', 'movieDuration'));
            } else {
                return redirect()
                    ->route('admin.showtimes.index')
                    ->with('error', 'Bạn không được sửa suất chiếu trong quá khứ!');
            }
        } else {
            return redirect()
                ->route('admin.showtimes.index')
                ->with('error', 'Sửa không thành công! Suất chiếu này đã/đang có người đặt!');
        }
    }

    public function update(UpdateShowtimeRequest $request, Showtime $showtime)
    {

        try {
            $movieVersion = MovieVersion::find($request->movie_version_id);
            $room = Room::find($request->room_id);
            $typeRoom = TypeRoom::find($room->type_room_id);
            $movie = Movie::find($request->movie_id);
            $movieDuration = $movie ? $movie->duration : 0;
            $cleaningTime = Showtime::CLEANINGTIME;
            $user = auth()->user();

            $startTime = \Carbon\Carbon::parse($request->date . ' ' . $request->start_time);
            $endTime = $startTime->copy()->addMinutes($movieDuration + $cleaningTime);

            $dataShowtimes = [
                // 'cinema_id' => isset($request->cinema_id) ? $request->cinema_id : $user->cinema_id,
                'room_id' => $request->room_id,
                'format' => $typeRoom->name . ' ' . $movieVersion->name,
                'movie_version_id' => $request->movie_version_id,
                'movie_id' => $request->movie_id,
                'date' => $request->date,
                'start_time' => $startTime->format('Y-m-d H:i'), // Định dạng start_time
                'end_time' => $endTime->format('Y-m-d H:i'), // Định dạng end_time
                'is_active' => isset($request->is_active) ? 1 : 0,
            ];



            $showtime->update($dataShowtimes);


            return redirect()
                ->back()
                ->with('success', 'Cập nhật thành công!');
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }


    public function destroy(Showtime $showtime)
    {

        try {

            $timeNow = now();
            $seatShowtimes = SeatShowtime::where('showtime_id', $showtime->id)->pluck('status', 'id')->all();
            // dd($seatShowtime);
            $statusSeat = true;
            foreach ($seatShowtimes as $id => $status) {
                if ($status != "available") {
                    $statusSeat = false;
                    break;
                }
            }
            if ($statusSeat) {
                if (!$timeNow->greaterThan($showtime->start_time)) {
                    $showtime->delete();

                    return redirect()
                        ->route('admin.showtimes.index')
                        ->with('success', 'Xóa thành công!');
                } else {
                    return redirect()
                        ->route('admin.showtimes.index')
                        ->with('error', 'Bạn không được sửa suất chiếu trong quá khứ!');
                }
            } else {
                return redirect()
                    ->route('admin.showtimes.index')
                    ->with('error', 'Xóa không thành công! Suất chiếu này đã/đang có người đặt!');
            }
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }
}
