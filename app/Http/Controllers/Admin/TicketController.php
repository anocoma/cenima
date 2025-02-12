<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Cinema;
use App\Models\Movie;
use App\Models\Ticket;
use App\Models\TicketSeat;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Milon\Barcode\Facades\DNS1DFacade as DNS1D;


class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    const PATH_VIEW = 'admin.tickets.';

    public function __construct()
    {
        $this->middleware('can:Danh sách hóa đơn')->only('index');
        $this->middleware('can:Thêm hóa đơn')->only(['create', 'store']);
        $this->middleware('can:Sửa hóa đơn')->only(['edit', 'update']);
        $this->middleware('can:Xóa hóa đơn')->only('destroy');
        $this->middleware('can:Quét hóa đơn')->only('scan', 'processScan');
        $this->middleware('can:Xem chi tiết hóa đơn')->only('show');
    }

    public function index(Request $request)
    {

        // dd(session()->all());
        $user = Auth::user();

        if ($user->hasRole('System Admin')) {
            // Thiết lập giá trị mặc định
            $defaultBranchId = Branch::where('is_active', 1)->first()?->id ?? null;
            $defaultCinemaId = Cinema::where('branch_id', $defaultBranchId)->where('is_active', 1)->first()?->id ?? null;
            $defaultDate = now()->format('Y-m-d');
            $defaultMovie = null;
            $defaultStatus = null;
        } else {
            // Thiết lập giá trị mặc định
            $defaultBranchId = $user->cinema->branch_id;
            $defaultCinemaId = $user->cinema_id;
            $defaultDate = now()->format('Y-m-d');
            $defaultMovie = null;
            $defaultStatus = null;
        }


        // Lấy giá trị từ request, nếu có thay đổi sẽ lưu vào session
        if ($request->hasAny(['branch_id', 'cinema_id', 'date', 'movie_id', 'is_active'])) {
            $branchId = $request->input('branch_id', $defaultBranchId);
            $cinemaId = $request->input('cinema_id', $defaultCinemaId);
            $date = $request->input('date', $defaultDate);
            $movieId = $request->input('movie_id', $defaultMovie);
            $status = $request->input('status', $defaultStatus);

            // Lưu giá trị lọc vào session
            session([
                'ticket.branch_id' => $branchId,
                'ticket.cinema_id' => $cinemaId,
                'ticket.date' => $date,
                'ticket.movie_id' => $movieId,
                'ticket.status' => $status
            ]);
        } else {
            // Lấy giá trị từ session hoặc giá trị mặc định nếu session chưa tồn tại
            $branchId = session('ticket.branch_id', $defaultBranchId);
            $cinemaId = session('ticket.cinema_id', $defaultCinemaId);
            $date = session('ticket.date', $defaultDate);
            $movieId = session('ticket.movie_id', $defaultMovie);
            $status = session('ticket.status', $defaultStatus);
        }

        // Lấy danh sách các chi nhánh, rạp và phim theo bộ lọc
        $branches = Branch::where('is_active', 1)->pluck('name', 'id')->all();
        $cinemas = Cinema::where('branch_id', $branchId)->where('is_active', 1)->pluck('name', 'id')->all();
        $movies = Movie::where('is_active', 1)->pluck('name', 'id')->all();

        // Truy vấn danh sách vé dựa trên bộ lọc
        $tickets = Ticket::with(['user', 'cinema', 'movie', 'room', 'showtime'])
            ->when($cinemaId, fn($query) => $query->where('cinema_id', $cinemaId))
            // ->when($date, fn($query) => $query->whereDate('created_at', $date))
            ->when($date, function ($query) use ($date) {
                $query->whereHas('showtime', fn($q) => $q->whereDate('start_time', $date));
            })
            ->when($movieId !== null, fn($query) => $query->where('movie_id', $movieId))
            ->when($status !== null && $status !== '', function ($query) use ($status) {
                switch ($status) {
                    case '0':
                        $query->where('status', 'Chưa xuất vé');
                        break;
                    case '1':
                        $query->where('status', 'Đã xuất vé');
                        break;
                    case '2':
                        $query->where('status', 'Đã hết hạn');
                        break;
                }
            })
            ->latest('id')
            ->get()
            ->groupBy('code');

        // Tạo mã vạch cho các vé
        $barcodes = [];
        foreach ($tickets as $code => $group) {
            $barcodes[$code] = DNS1D::getBarcodeHTML($code, 'C128', 1.5, 50);
        }

        return view(self::PATH_VIEW . __FUNCTION__, compact(
            'tickets',
            'cinemas',
            'branches',
            'movies',
            'barcodes',
            'branchId',
            'cinemaId',
            'date',
            'movieId',
            'status'
        ));
    }


    //     public function index(Request $request)
    // {

    //         $user = Auth::user();
    //         if ($user->cinema_id == "") {
    //             $defaultBranchId = 1;
    //             $defaultCinemaId = 1;
    //             $defaultDate = now()->format('Y-m-d');
    //             $defaultIsActive = null;
    //         } else {
    //             $defaultBranchId = $user->cinema->branch_id;
    //             $defaultCinemaId = $user->cinema_id;
    //             $defaultDate = now()->format('Y-m-d');
    //             $defaultIsActive = null;
    //         }


    //         // Lấy giá trị từ session hoặc sử dụng mặc định nếu session chưa có
    //         $branchId = $request->input('branch_id', session('showtime.branch_id', $defaultBranchId));
    //         $cinemaId = $request->input('cinema_id', session('showtime.cinema_id', $defaultCinemaId));
    //         $date = $request->input('date', session('showtime.date', $defaultDate));
    //         $isActive = $request->input('is_active', session('showtime.is_active', $defaultIsActive));

    //         // Lưu vào session
    //         session([
    //             'showtime.branch_id' => $branchId,
    //             'showtime.cinema_id' => $cinemaId,
    //             'showtime.date' => $date,
    //             'showtime.is_active' => $isActive
    //         ]);

    //         $branches = Branch::where('is_active', '1')->pluck('name', 'id')->all();
    //         $cinemas = Cinema::where('branch_id', $branchId)->where('is_active', '1')->pluck('name', 'id')->all();
    //         $movies = Movie::where('is_active', '1')->pluck('name', 'id')->all();


    //         // dd($branches, $cinemas, $movies);


    //         $tickets = Ticket::with(['user', 'cinema', 'movie', 'room', 'showtime'])
    //             ->latest('id');

    //         $tickets = $tickets->get()->groupBy('code');

    //         $barcodes = [];
    //         foreach ($tickets as $code => $group) {
    //             $barcodes[$code] = DNS1D::getBarcodeHTML($code, 'C128', 1.5, 50);
    //         }

    //         return view(self::PATH_VIEW . __FUNCTION__, compact('tickets', 'cinemas', 'branches', 'movies', 'barcodes'));
    //     }



    /*public function updateStatus(Request $request, Ticket $ticket)
    {
        $request->validate([
            'status' => 'required',
        ]);

        if ($ticket->status == 'Đã xuất vé') {
            return response()->json([
                'success' => false,
                'message' => 'Vé đã hoàn tất, không thể chỉnh sửa.'
            ]);
        }

        // Tạo biến thời gian hiện tại với múi giờ Asia/Ho_Chi_Minh
        $now = Carbon::now('Asia/Ho_Chi_Minh');

        // Kiểm tra nếu vé đã hết hạn
        if ($ticket->expiry < $now) {
            $ticket->status = 'Đã hết hạn';
            $ticket->save();

            return response()->json([
                'success' => false,
                'message' => 'Vé đã hết hạn và trạng thái đã được cập nhật thành "Đã hết hạn".'
            ]);
        }

        // Nếu vé chưa hết hạn, tiếp tục cập nhật trạng thái theo yêu cầu
        $ticket->status = $request->status;
        $ticket->save();

        return response()->json(['success' => true]);
    }*/

    public function confirm(Request $request, Ticket $ticket)
    {
        if ($ticket->status == Ticket::NOT_ISSUED && $ticket->expiry > now()) {
            $ticket->update([
                'status' => Ticket::ISSUED
            ]);
        }
        session()->flash('confirm', true);

        return response()->json([
            'success' => true,
            'message' => 'Thay đổi trạng thái thành công!'
        ]);
    }

    /*public function print(Ticket $ticket)
    {
        $ticket->load([
            'movie.movieVersions',
            'room',
            'ticketCombos.combo.food',
            'ticketSeats.seat.typeSeat',
            'cinema.branch',
            'user'
        ]);

        $viewData = [
            'ticket' => $ticket,
            'barcode' => DNS1D::getBarcodeHTML($ticket->code, 'C128', 1.5, 50),
            'totalPriceSeat' => $this->calculateTotalSeatPrice($ticket),
            'totalComboPrice' => $this->calculateTotalComboPrice($ticket),
            'ratingDescription' => $this->getRatingDescription($ticket->movie->rating)
        ];

        return view(self::PATH_VIEW . 'order', $viewData);
        // Tải về file PDF
    }*/
    /*public function printCombo(Ticket $ticket)
        {
            $oneTicket = Ticket::with(['ticketCombos','cinema.branch'])->findOrFail($ticket->id);
            $barcode = DNS1D::getBarcodeHTML($oneTicket->code, 'C128', 1.5, 50);
            return view(self::PATH_VIEW . __FUNCTION__, compact('ticket','oneTicket','barcode'));
        }*/

    public function scan()
    {
        return view(self::PATH_VIEW . __FUNCTION__);
    }


    public function processScan(Request $request)
    {
        $ticketCode = $request->input('code');

        $ticket = Ticket::where('code', $ticketCode)->first();

        if ($ticket) {
            return response()->json([
                'success' => true,
                'message' => 'Thao tác thành công',
                'redirect_url' => route('admin.tickets.show', $ticket)
            ]);
        }



        // $now = Carbon::now();
        // if ($now > $ticket->expiry) {
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'Vé này đã hết hạn.',
        //     ]);
        // }

        // switch ($ticket->status) {
        //     case 'Chưa xuất vé':
        //         return response()->json([
        //             'success' => true,
        //             'message' => 'QR code đã được xử lý thành công!',
        //             'redirect_url' => route('admin.tickets.show', $ticket)
        //         ]);

        //     case 'Đã xuất vé':
        //         return response()->json([
        //             'success' => true,
        //             'message' => 'Vé này đã được suất rồi.',
        //             'redirect_url' => route('admin.tickets.show', $ticket)
        //         ]);

        //     default:
        //         return response()->json([
        //             'success' => false,
        //             'message' => 'Vé không hợp lệ.',
        //         ]);
        // }
        return response()->json([
            'success' => false,
            'message' => 'Mã vé không hợp lệ.',
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Ticket $ticket)
    {
        $oneTicket = $ticket->load(['ticketCombos.combo', 'showtime', 'cinema']);
        $totalPriceSeat = $ticket->ticketSeats->sum('price');
        $totalComboPrice = $ticket->ticketCombos->sum('price');
        $barcode = DNS1D::getBarcodeHTML($ticket->code, 'C128', 1.5, 50);
        return view(self::PATH_VIEW . __FUNCTION__, compact('ticket', 'oneTicket', 'totalPriceSeat', 'barcode', 'totalComboPrice'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
