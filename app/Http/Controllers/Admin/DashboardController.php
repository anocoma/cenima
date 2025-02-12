<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Showtime;
use App\Models\Ticket;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    // //
    // public function dashboard()
    // {

    //     $user = Auth::user();
    //     // Tổng doanh thu ngày hôm nay
    //     $todayRevenue = Ticket::whereDate('created_at', Carbon::today())->sum('total_price');

    //     // Doanh thu của một ngày cụ thể
    //     // $specificDayRevenue = Ticket::whereDate('created_at', '2024-11-07') // Ví dụ
    //     //     ->sum('total_price');

    //     // Tổng doanh thu tuần này
    //     $weekRevenue = Ticket::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
    //         ->sum('total_price');

    //     // Tổng doanh thu tháng này
    //     $monthRevenue = Ticket::whereMonth('created_at', Carbon::now()->month)
    //         ->whereYear('created_at', Carbon::now()->year)
    //         ->sum('total_price');

    //     // Tổng doanh thu năm nay
    //     $yearRevenue = Ticket::whereYear('created_at', Carbon::now()->year)
    //         ->sum('total_price');

    //     // return view(self::PATH_VIEW . __FUNCTION__);


    //     // doanh thu theo phim
    //     $startDate = '2024-05-01';
    //     $endDate = '2024-11-30';

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

    //     return view('admin.dashboard', compact('todayRevenue', 'weekRevenue', 'monthRevenue', 'yearRevenue', 'revenueTimeSlot'));
    // }

    public function overallRevenue()
    {
        $datas = Ticket::select(DB::raw('MONTH(created_at) as month, SUM(total_price) as total_prices, COUNT(*) as total_tickets'))
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->orderBy('month', 'asc')
            ->get();

        // Tạo mảng dữ liệu cho biểu đồ
        $months = [];
        $revenues = [];
        $tickets = [];

        foreach ($datas as $data) {
            $months[] = Carbon::createFromFormat('m', $data->month)->format('F'); // Chuyển số tháng thành tên tháng
            $revenues[] = $data->total_revenue;
            $tickets[] = $data->total_tickets;
        }
    }






    public function dashboard(Request $request)
    {
        $years = $this->getYearsForDropdown();
        $tickets = Ticket::select(
            DB::raw('YEAR(created_at) as year'),
            DB::raw('MONTH(created_at) as month'),
            DB::raw('SUM(total_price) as total_revenue')
        )
            ->groupBy(DB::raw('YEAR(created_at)'), DB::raw('MONTH(created_at)'))
            ->orderBy(DB::raw('YEAR(created_at)'))
            ->orderBy(DB::raw('MONTH(created_at)'))
            ->get();

        // Chuyển đổi dữ liệu thành dạng phù hợp cho biểu đồ
        $data = [
            'months' => ['Tháng 1', 'Tháng 2', 'Tháng 3', 'Tháng 4', 'Tháng 5', 'Tháng 6', 'Tháng 7', 'Tháng 8', 'Tháng 9', 'Tháng 10', 'Tháng 11', 'Tháng 12'],
            'revenue' => [],
            'total_year_revenue' => [] // Mảng để lưu tổng doanh thu theo năm
        ];
        foreach ($tickets as $ticket) {
            $month = $ticket->month - 1; // Để index từ 0 (tháng 1 là 0, tháng 12 là 11)
            $year = $ticket->year;
            if (!isset($data['revenue'][$year])) {
                $data['revenue'][$year] = array_fill(0, 12, 0); // Khởi tạo mảng doanh thu cho năm nếu chưa có
                $data['total_year_revenue'][$year] = 0; // Khởi tạo tổng doanh thu của năm
            }
            $data['revenue'][$year][$month] = $ticket->total_revenue;
            $data['total_year_revenue'][$year] += $ticket->total_revenue;
        }







        // Lấy dữ liệu tháng hiện tại
        $dayOfMonth = [now()->startOfMonth(), now()->endOfMonth()];
        $dayOfLastMonth = [now()->subMonth()->startOfMonth(), now()->subMonth()->endOfMonth()];

        $ticket = Ticket::whereBetween('created_at', $dayOfMonth)
            ->selectRaw('SUM(total_price) as total_revenue, COUNT(id) as total_sales')->first();
        $ticketLastMonth = Ticket::whereBetween('created_at', $dayOfLastMonth)
            ->selectRaw('SUM(total_price) as total_revenue, COUNT(id) as total_sales') ->first();

        $revenuePc = $this->calculatePc($ticket->total_revenue, $ticketLastMonth->total_revenue);
        $salesPc = $this->calculatePc($ticket->total_sales, $ticketLastMonth->total_sales);

        $user = User::whereBetween('created_at', $dayOfMonth) ->count();
        $userLastMonth = User::whereBetween('created_at', $dayOfLastMonth)->count();
        $userPc = $this->calculatePc($user, $userLastMonth);

        $showtime = Showtime::whereBetween('date', $dayOfMonth)->count();
        $showtimeLastMonth = Showtime::whereBetween('date', $dayOfLastMonth)->count();
        $showtimePc = $this->calculatePc($showtime, $showtimeLastMonth);




        $seatData = DB::table('ticket_seats as ts')
            ->join('seats as s', 'ts.seat_id', '=', 's.id')
            ->join('type_seats as t', 's.type_seat_id', '=', 't.id')
            ->select('t.name as seat_name', DB::raw('COUNT(*) as total'))
            ->groupBy('t.name')
            ->orderBy('t.id')
            ->get();
        // dd($currentMonthData);
        return view('admin.dashboard', compact('data','years', 'ticket', 'user', 'showtime', 'revenuePc', 'salesPc', 'userPc', 'showtimePc','seatData'));
    }
    private function calculatePc($currentValue, $previousValue)
    {
        return $previousValue > 0
            ? round((($currentValue - $previousValue) / $previousValue) * 100, 2)
            : 0;
    }


    public function getYearsForDropdown()
    {
        // Lấy năm nhỏ nhất từ trường created_at
        $minYear = DB::table('tickets')
            ->selectRaw('YEAR(MIN(created_at)) as year')
            ->value('year');

        // Lấy năm hiện tại
        $currentYear = Carbon::now()->year;

        // Tạo danh sách năm từ năm nhỏ nhất đến năm hiện tại
        $years = range($minYear, $currentYear);

        return $years;
    }
}
