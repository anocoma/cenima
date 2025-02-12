<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Cinema;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
// use App\Models\Ticket;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class StatisticalController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:Danh sách thống kê')->only('statisticalRevenue', 'statisticalCinemas', 'statisticalMovies', 'statisticalTickets');
    }

    // public function revenue()
    // {
    //     $branches = Branch::all();


    //     // doanh thu theo phim
    //     $startDate = '2023-11-01';
    //     $endDate = '2024-11-30';

    //     $revenueByMovies = Ticket::join('movies', 'tickets.movie_id', '=', 'movies.id')
    //         ->whereBetween('tickets.created_at', [$startDate, $endDate])
    //         ->select('movies.name', DB::raw('SUM(tickets.total_price) as total_revenue'))
    //         ->groupBy('movies.id', 'movies.name')
    //         ->get();

    //     //doanh thu theo khung giờ chiếu
    //     $timeSlots = [
    //         ['start' => '09:00:00', 'end' => '13:00:00', 'label' => '9:00 - 13:00'],
    //         ['start' => '13:00:00', 'end' => '18:00:00', 'label' => '13:00 - 18:00'],
    //         ['start' => '18:00:00', 'end' => '24:00:00', 'label' => '18:00 - 24:00'],
    //     ];

    //     $revenueTimeSlot = [];
    //     foreach ($timeSlots as $slot) {
    //         $totalRevenue = Ticket::join('showtimes', 'tickets.showtime_id', '=', 'showtimes.id')
    //             ->whereBetween('tickets.created_at', [$startDate, $endDate])
    //             ->whereTime('showtimes.start_time', '>=', $slot['start'])
    //             ->whereTime('showtimes.start_time', '<', $slot['end'])
    //             ->sum('tickets.total_price');

    //         $revenueTimeSlot[] = [
    //             'label' => $slot['label'],
    //             'revenue' => (float)$totalRevenue, // Chuyển sang kiểu số thực
    //         ];
    //     }

    //     // dd($revenueByMovies);


    //     // Thống kê doanh thu theo Ngày/Tháng/Năm
    //     $dailyRevenue = Ticket::selectRaw("DATE(created_at) as date, SUM(total_price) as total_revenue")
    //         ->groupBy('date')->orderBy('date', 'asc')->get();
    //     $weeklyRevenue = Ticket::selectRaw("WEEK(created_at) as week, SUM(total_price) as total_revenue")
    //         ->groupBy('week')->orderBy('week', 'asc')->get();
    //     $monthlyRevenue = Ticket::selectRaw("MONTH(created_at) as month, SUM(total_price) as total_revenue")
    //         ->groupBy('month')->orderBy('month', 'asc')->get();
    //     $yearlyRevenue = Ticket::selectRaw("YEAR(created_at) as year, SUM(total_price) as total_revenue")
    //         ->groupBy('year')->orderBy('year', 'asc')->get();

    //     //THống kê theo rạp 
    //     $revenueByCinema = Ticket::join('cinemas', 'tickets.cinema_id', '=', 'cinemas.id')
    //         ->select('cinemas.name as cinema_name', DB::raw('SUM(tickets.total_price) as total_revenue'))
    //         ->groupBy('cinemas.name')
    //         ->orderBy('total_revenue', 'desc')
    //         ->get();

    //     return view('admin.statisticals.revenue', compact('revenueByMovies', 'branches', 'dailyRevenue', 'weeklyRevenue', 'monthlyRevenue', 'yearlyRevenue', 'revenueByCinema', 'revenueTimeSlot'));
    // }


    public function statisticalRevenue(Request $request)
    {
        $user = Auth::user();
        $branches = Branch::where('is_active', 1)->get();

        // Kiểm tra xem có bộ lọc nào từ request hay không
        $isFiltering = $request->hasAny(['branch_id', 'cinema_id', 'start_date', 'end_date']);

        // Lấy thông tin từ session hoặc giá trị mặc định
        $startDate = $request->input('start_date', session('statistical.start_date', Carbon::now()->subDays(30)->format('Y-m-d')));
        $endDate = $request->input('end_date', session('statistical.end_date', Carbon::now()->format('Y-m-d')));
        $branchId = $request->input('branch_id', session('statistical.branch_id'));
        $cinemaId = $request->input('cinema_id', session('statistical.cinema_id'));


        $timeRange = $request->input('time_range', 'daily');



        // Khởi tạo doanh thu dựa trên lựa chọn lọc
        $query = Ticket::whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate);
        // whereBetween('created_at', [$startDate, $endDate]);


        if (!$user->hasRole('System Admin')) {
            // Lọc theo cinema_id của user
            $cinemaId = $user->cinema_id;
            $query->where('tickets.cinema_id', $cinemaId);
        } else {
            // Lọc theo chi nhánh nếu có
            if ($branchId) {
                $cinemaIds = Cinema::where('branch_id', $branchId)->pluck('id');
                $query->whereIn('tickets.cinema_id', $cinemaIds);
            }

            // Lọc theo rạp nếu có
            if ($cinemaId) {
                $query->where('tickets.cinema_id', $cinemaId);
            }
        }

        // Lưu thông tin vào session khi có bộ lọc
        if ($isFiltering) {
            session([
                'statistical.branch_id' => $branchId,
                'statistical.cinema_id' => $cinemaId,
                'statistical.start_date' => $startDate,
                'statistical.end_date' => $endDate,
            ]);
        }


        $revenueData = [];
        if ($timeRange == 'daily') {
            $revenueData = $query->selectRaw("DATE(created_at) as date, SUM(total_price) as total_revenue")
                ->groupBy('date')
                ->orderBy('date', 'asc')
                ->get();
        } elseif ($timeRange == 'weekly') {
            $revenueData = $query->selectRaw("WEEK(created_at) as week, SUM(total_price) as total_revenue")
                ->groupBy('week')
                ->orderBy('week', 'asc')
                ->get();
        } elseif ($timeRange == 'monthly') {
            $revenueData = $query->selectRaw("MONTH(created_at) as month, YEAR(created_at) as year, SUM(total_price) as total_revenue")
                ->groupBy('year', 'month')
                ->orderBy('year', 'asc')
                ->orderBy('month', 'asc')
                ->get();
        } elseif ($timeRange == 'yearly') {
            $revenueData = $query->selectRaw("YEAR(created_at) as year, SUM(total_price) as total_revenue")
                ->groupBy('year')
                ->orderBy('year', 'asc')
                ->get();
        }

        $dailyRevenue = $query->selectRaw("DATE(created_at) as date, SUM(total_price) as total_revenue")
            ->groupBy('date')->orderBy('date', 'asc')->get();
        $weeklyRevenue = $query->selectRaw("WEEK(created_at) as week, SUM(total_price) as total_revenue")
            ->groupBy('week')->orderBy('week', 'asc')->get();
        $monthlyRevenue = $query->selectRaw("MONTH(created_at) as month, SUM(total_price) as total_revenue")
            ->groupBy('month')->orderBy('month', 'asc')->get();
        $yearlyRevenue = $query->selectRaw("YEAR(created_at) as year, SUM(total_price) as total_revenue")
            ->groupBy('year')->orderBy('year', 'asc')->get();

        return view('admin.statisticals.statistical-revenue', compact('branches', 'branchId', 'cinemaId', 'revenueData', 'timeRange', 'startDate', 'endDate', 'dailyRevenue', 'weeklyRevenue', 'monthlyRevenue', 'yearlyRevenue'));
    }



    public function statisticalCinemas(Request $request)
    {
        $branches = Branch::all();
        $startDate = $request->input('start_date', Carbon::now()->subDays(30)->startOfDay()->format('Y-m-d\TH:i'));
        $endDate = $request->input('end_date', Carbon::now()->endOfDay()->format('Y-m-d\TH:i'));

        // Khởi tạo query thống kê doanh thu theo rạp
        $query = Ticket::join('cinemas', 'tickets.cinema_id', '=', 'cinemas.id')
            ->select('cinemas.name as cinema_name', DB::raw('SUM(tickets.total_price) as total_revenue'))
            ->groupBy('cinemas.id', 'cinemas.name')
            ->orderBy('total_revenue', 'desc');

        // Áp dụng lọc theo chi nhánh nếu có
        if ($request->has('branch_id') && $request->input('branch_id') != '') {
            $cinemaIds = Cinema::where('branch_id', $request->input('branch_id'))->pluck('id');
            $query->whereIn('tickets.cinema_id', $cinemaIds);
        }

        // Áp dụng lọc theo rạp nếu có
        if ($request->has('cinema_id') && $request->input('cinema_id') != '') {
            $query->where('tickets.cinema_id', $request->input('cinema_id'));
        }

        // Áp dụng lọc theo ngày nếu có
        if (
            $request->has('start_date') && $request->has('end_date') &&
            $request->input('start_date') != '' && $request->input('end_date') != ''
        ) {
            $startDate = $request->input('start_date');
            $endDate = $request->input('end_date');
            $query->whereBetween('tickets.created_at', [$startDate, $endDate]);
        } else {
            $query->whereBetween('tickets.created_at', [$startDate, $endDate]);
        }

        // Lấy kết quả doanh thu theo rạp
        $revenueByCinema = $query->get();

        return view('admin.statisticals.statistical-cinemas', compact('branches', 'revenueByCinema', 'startDate', 'endDate'));
    }

    public function statisticalMovies(Request $request)
    {
        // dd(session()->all());
        $user = Auth::user();
        $branches = Branch::where('is_active', 1)->get();

        // Kiểm tra xem có bộ lọc nào từ request hay không
        $isFiltering = $request->hasAny(['branch_id', 'cinema_id', 'start_date', 'end_date']);

        // Lấy thông tin từ session hoặc giá trị mặc định
        $startDate = $request->input('start_date', session('statistical.start_date', Carbon::now()->subDays(30)->format('Y-m-d')));
        $endDate = $request->input('end_date', session('statistical.end_date', Carbon::now()->format('Y-m-d')));
        $branchId = $request->input('branch_id', session('statistical.branch_id'));
        $cinemaId = $request->input('cinema_id', session('statistical.cinema_id'));

        $query = Ticket::join('movies', 'tickets.movie_id', '=', 'movies.id')
            ->select('movies.name', DB::raw('SUM(tickets.total_price) as total_revenue'))
            ->groupBy('movies.id', 'movies.name');

        if (!$user->hasRole('System Admin')) {
            // Lọc theo cinema_id của user
            $cinemaId = $user->cinema_id;
            $query->where('tickets.cinema_id', $cinemaId);
        } else {
            // Lọc theo chi nhánh nếu có
            if ($branchId) {
                $cinemaIds = Cinema::where('branch_id', $branchId)->pluck('id');
                $query->whereIn('tickets.cinema_id', $cinemaIds);
            }

            // Lọc theo rạp nếu có
            if ($cinemaId) {
                $query->where('tickets.cinema_id', $cinemaId);
            }
        }

        // Lọc theo khoảng thời gian
        $query->whereDate('tickets.created_at', '>=', $startDate)
            ->whereDate('tickets.created_at', '<=', $endDate);


        // Lấy kết quả doanh thu theo phim
        $revenueByMovies = $query->get();
        // dd($revenueByMovies->toArray());


        // Thống kê tổng phim
        $totalMovies = $revenueByMovies->count('name');
        // dd($totalMovies);

        // Thống kê tổng doanh thu
        $totalRevenue = $revenueByMovies->sum('total_revenue');
        // dd($totalRevenue);

        // Lưu thông tin vào session khi có bộ lọc
        if ($isFiltering) {
            session([
                'statistical.branch_id' => $branchId,
                'statistical.cinema_id' => $cinemaId,
                'statistical.start_date' => $startDate,
                'statistical.end_date' => $endDate,
            ]);
        }

        return view('admin.statisticals.statistical-movies', compact(
            'revenueByMovies',
            'branches',
            'startDate',
            'endDate',
            'totalMovies',
            'totalRevenue',
            'branchId',
            'cinemaId'
        ));
    }



    public function statisticalTickets(Request $request)
    {
        // dd(session()->all());
        $user = Auth::user();
        $branches = Branch::where('is_active', 1)->get();

        // Kiểm tra xem có bộ lọc nào từ request hay không
        $isFiltering = $request->hasAny(['branch_id', 'cinema_id', 'start_date', 'end_date']);

        // Lấy thông tin từ session hoặc giá trị mặc định
        $startDate = $request->input('start_date', session('statistical.start_date', Carbon::now()->subDays(30)->format('Y-m-d')));
        $endDate = $request->input('end_date', session('statistical.end_date', Carbon::now()->format('Y-m-d')));
        $branchId = $request->input('branch_id', session('statistical.branch_id'));
        $cinemaId = $request->input('cinema_id', session('statistical.cinema_id'));

        // Khởi tạo truy vấn và áp dụng các điều kiện lọc
        $query = Ticket::select(
            DB::raw("DATE(created_at) as date"),
            DB::raw("SUM(CASE WHEN status = 'Chưa xuất vé' THEN 1 ELSE 0 END) as pending"),
            DB::raw("SUM(CASE WHEN status = 'Đã xuất vé' THEN 1 ELSE 0 END) as completed"),
            DB::raw("SUM(CASE WHEN status = 'Đã hết hạn' THEN 1 ELSE 0 END) as expired")
        )
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate);

        // Lọc theo vai trò người dùng
        if (!$user->hasRole('System Admin')) {
            $query->where('cinema_id', $user->cinema_id);
        } else {
            // Lọc theo chi nhánh nếu có
            if ($branchId) {
                $cinemaIds = Cinema::where('branch_id', $branchId)->pluck('id');
                $query->whereIn('cinema_id', $cinemaIds);
            }

            // Lọc theo rạp nếu có
            if ($cinemaId) {
                $query->where('cinema_id', $cinemaId);
            }
        }

        // Thực hiện truy vấn sau khi áp dụng tất cả các bộ lọc
        $query = $query->groupBy(DB::raw("DATE(created_at)"))
            ->orderBy('date', 'ASC')
            ->get();

        // Lưu thông tin vào session khi có bộ lọc
        if ($isFiltering) {
            session([
                'statistical.branch_id' => $branchId,
                'statistical.cinema_id' => $cinemaId,
                'statistical.start_date' => $startDate,
                'statistical.end_date' => $endDate,
            ]);
        }

        // Chuyển dữ liệu thành dạng phù hợp cho Chart.js
        $labels = $query->pluck('date')->toArray();       // Các ngày
        $pending = $query->pluck('pending')->toArray();   // Vé 'Chưa xuất vé'
        $completed = $query->pluck('completed')->toArray(); // Vé 'Đã xuất vé'
        $expired = $query->pluck('expired')->toArray();   // Vé 'Đã hết hạn'

        return view('admin.statisticals.statistical-tickets', compact(
            'branches',
            'branchId',
            'cinemaId',
            'startDate',
            'endDate',
            'labels',
            'pending',
            'completed',
            'expired'
        ));
    }


    public function statisticalCombos(Request $request)
    {
        $user = Auth::user();
        $branches = Branch::where('is_active', 1)->get();

        $startDate = $request->input('start_date', session('statistical.start_date', Carbon::now()->subDays(30)->format('Y-m-d')));
        $endDate = $request->input('end_date', session('statistical.end_date', Carbon::now()->format('Y-m-d')));
        $branchId = $request->input('branch_id', session('statistical.branch_id'));
        $cinemaId = $request->input('cinema_id', session('statistical.cinema_id'));

        // Truy vấn kết hợp bộ lọc
        $query = DB::table('ticket_combos')
            ->join('combos', 'ticket_combos.combo_id', '=', 'combos.id')
            ->join('tickets', 'ticket_combos.ticket_id', '=', 'tickets.id') // cần join với bảng tickets để có cinema_id
            ->select(
                'combos.name',
                DB::raw('CONCAT(SUM(ticket_combos.quantity), " lượt - ", FORMAT(SUM(ticket_combos.price), 0), " VND") as summary')
            )
            ->whereDate('ticket_combos.created_at', '>=', $startDate)
            ->whereDate('ticket_combos.created_at', '<=', $endDate)
            ->groupBy('combos.name');

        // Áp dụng lọc theo vai trò
        if (!$user->hasRole('System Admin')) {
            $query->where('tickets.cinema_id', $user->cinema_id);
        } else {
            if ($branchId) {
                $cinemaIds = Cinema::where('branch_id', $branchId)->pluck('id');
                $query->whereIn('tickets.cinema_id', $cinemaIds);
            }

            if ($cinemaId) {
                $query->where('tickets.cinema_id', $cinemaId);
            }
        }

        // Thực thi truy vấn
        $comboStatistics = $query->get();

        // dd($comboStatistics->toArray());

        // Lưu vào session khi lọc
        session([
            'statistical.branch_id' => $branchId,
            'statistical.cinema_id' => $cinemaId,
            'statistical.start_date' => $startDate,
            'statistical.end_date' => $endDate,
        ]);

        return view('admin.statisticals.statistical-combos', compact(
            'branches',
            'branchId',
            'cinemaId',
            'startDate',
            'endDate',
            'comboStatistics'
        ));
    }
}
