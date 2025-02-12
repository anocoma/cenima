@extends('admin.layouts.master')

@section('title')
    Thống kê phim
@endsection

@section('content')
    <div class="row">
        <div class="col">
            <div class="h-100">

                <form action="{{ route('admin.statistical-movies') }}" method="GET" class="mb-3">
                    @include('admin.layouts.components.statistical-filter')
                </form>

                <div class="row">
                    <div class="col-xl-12">
                        <div class="card">
                            <div class="card-header border-0 align-items-center">
                                <h4 class="card-title mb-0 flex-grow-1">Doanh thu phim
                                    @if (Auth::user()->cinema_id != '')
                                        - {{ Auth::user()->cinema->name }}
                                    @endif
                                </h4>
                            </div><!-- end card header -->

                            <div class="card-header p-0 border-0 bg-light-subtle">
                                <div class="row g-0 text-center">
                                    <div class="col-6 col-sm-6">
                                        <div class="p-3 border border-dashed border-start-0">
                                            <h5 class="mb-1"><span class="counter-value"
                                                    data-target="{{ $totalMovies }}">0</span>
                                            </h5>
                                            <p class="text-muted mb-0">Tổng phim</p>
                                        </div>
                                    </div>
                                    <!--end col-->
                                    {{-- <div class="col-6 col-sm-4">
                                        <div class="p-3 border border-dashed border-start-0">
                                            <h5 class="mb-1"><span class="counter-value" data-target="150">0</span>
                                            </h5>
                                            <p class="text-muted mb-0">Tổng hóa đơn</p>
                                        </div>
                                    </div> --}}
                                    <!--end col-->
                                    <div class="col-6 col-sm-6">
                                        <div class="p-3 border border-dashed border-start-0">
                                            <h5 class="mb-1"><span class="counter-value"
                                                    data-target="{{ $totalRevenue }}">0</span>VNĐ
                                            </h5>
                                            <p class="text-muted mb-0">Tổng doanh thu</p>
                                        </div>
                                    </div>
                                </div>
                            </div><!-- end card header -->

                            <canvas id="revenueChartByMovies" height="460"></canvas>

                        </div><!-- end card -->
                    </div><!-- end col -->
                </div>
            </div> <!-- end .h-100-->

        </div> <!-- end col -->
    </div>
@endsection

@section('script-libs')
    <!-- linecharts init -->
    <script src="{{ asset('theme/admin/assets/js/pages/apexcharts-line.init.js') }}"></script>
@endsection

@section('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Lấy giá trị branchId và cinemaId từ phía server
            var selectedBranchId = "{{ session('statistical.branch_id', '') }}";
            var selectedCinemaId = "{{ session('statistical.cinema_id', '') }}";
            var isLoading = false; // Cờ để kiểm tra trạng thái đang tải

            // Xử lý sự kiện thay đổi chi nhánh
            $('#branch').on('change', function() {
                var branchId = $(this).val();
                var cinemaSelect = $('#cinema');

                // Đặt lại giá trị của dropdown rạp về mặc định khi chọn chi nhánh khác
                cinemaSelect.empty();

                if (branchId) {
                    if (!isLoading) {
                        isLoading = true; // Đánh dấu đang tải dữ liệu
                        cinemaSelect.html(
                            '<option value="">Đang tải...</option>'); // Hiển thị "Đang tải..."

                        $.ajax({
                            url: "{{ env('APP_URL') }}/api/cinemas/" + branchId,
                            method: 'GET',
                            success: function(data) {
                                cinemaSelect.empty();
                                cinemaSelect.append(
                                    '<option value="">Tất cả rạp</option>'
                                ); // Hiển thị "Tất cả rạp" sau khi tải

                                $.each(data, function(index, cinema) {
                                    cinemaSelect.append('<option value="' + cinema.id +
                                        '">' + cinema.name + '</option>');
                                });

                                // Chọn lại rạp nếu có selectedCinemaId và branchId khớp
                                if (selectedCinemaId && branchId == selectedBranchId) {
                                    cinemaSelect.val(selectedCinemaId);
                                }
                                isLoading = false; // Kết thúc quá trình tải
                            },
                            error: function() {
                                cinemaSelect.html(
                                    '<option value="">Không thể tải danh sách rạp</option>');
                                isLoading = false; // Kết thúc quá trình tải
                            }
                        });
                    }
                } else {
                    cinemaSelect.empty();
                    cinemaSelect.append('<option value="">Tất cả rạp</option>');
                }
            });

            // Kích hoạt thay đổi chi nhánh để load rạp nếu có selectedBranchId
            if (selectedBranchId) {
                $('#branch').val(selectedBranchId).trigger('change');
            } else {
                // Nếu không có selectedBranchId, hiển thị "Tất cả rạp"
                var cinemaSelect = $('#cinema');
                if (!cinemaSelect.val()) { // Kiểm tra nếu không có giá trị rạp nào được chọn
                    cinemaSelect.empty();
                    cinemaSelect.append('<option value="">Tất cả rạp</option>');
                }
            }
        });
    </script>


    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>

    <script>
        // thống kê doanh thu theo phim
        const revenueChartByMovies = document.getElementById('revenueChartByMovies').getContext('2d');
        const revenueChartByMoviesData = {
            labels: @json($revenueByMovies->pluck('name')),
            datasets: [{
                label: 'Doanh thu (VNĐ)',
                data: @json($revenueByMovies->pluck('total_revenue')),
                backgroundColor: 'rgba(10, 179, 156, 0.9)',
                borderColor: 'rgba(10, 179, 156, 0.9)',
                borderWidth: 1
            }]
        };

        new Chart(revenueChartByMovies, {
            type: 'bar', // Biểu đồ cột
            data: revenueChartByMoviesData,
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Doanh thu (VNĐ)',
                            font: {
                                size: 14 // Tăng cỡ chữ tại đây
                            }
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Tên phim',
                            font: {
                                size: 14 // Tăng cỡ chữ tại đây
                            }
                        }
                    }
                }
            }
        });
    </script>
@endsection
