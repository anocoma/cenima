@extends('admin.layouts.master')

@section('title')
    Thống kê rạp
@endsection

@section('content')
    <div class="row">
        <div class="col">
            <div class="h-100">
                <div class="row">
                    <div class="col-md-10">
                        <form action="{{ route('admin.statistical-cinemas') }}" method="GET">
                            <div class="row">
                                @if (Auth::user()->hasRole('System Admin'))
                                    <div class="col-md-2">
                                        <select name="branch_id" id="branch" class="form-select py-2 px-2">
                                            <option value="">Tất cả chi nhánh</option>
                                            @foreach ($branches as $branch)
                                                <option value="{{ $branch->id }}"
                                                    {{ request('branch_id') == $branch->id ? 'selected' : '' }}>
                                                    {{ $branch->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <select name="cinema_id" id="cinema" class="form-select">
                                            <option value="">Tất cả rạp</option>
                                            {{-- Populate cinemas based on selected branch using AJAX --}}
                                        </select>
                                    </div>
                                @endif
                                <div class="col-md-3">
                                    <input type="datetime-local" name="start_date" class="form-control"
                                        value="{{ old('start_date', $startDate) }}">
                                </div>
                                <div class="col-md-3">
                                    <input type="datetime-local" name="end_date" class="form-control"
                                        value="{{ old('end_date', $endDate) }}">
                                </div>
                                <div class="col-md-2">
                                    <button class="btn btn-success" type="submit">
                                        <i class="ri-equalizer-fill me-1 align-bottom"></i>Lọc
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="col-md-2" align="right">
                        <a href="" class="btn btn-primary mb-3 ">Tổng quan</a>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xl-12">
                        <div class="card">
                            <div class="card-header border-0 align-items-center">
                                <h4 class="card-title mb-0 flex-grow-1">Doanh thu theo rạp</h4>
                            </div><!-- end card header -->

                            <canvas id="revenueChartCinema" style="width: 300px; height: 100px;"></canvas>
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
            var selectedBranchId = "{{ request('branch_id', '') }}";
            var selectedCinemaId = "{{ request('cinema_id', '') }}";
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
        //Biểu đồ theo rạp
        document.addEventListener('DOMContentLoaded', function() {
            const revenueDataCinema = {
                labels: @json($revenueByCinema->pluck('cinema_name')), // Lấy danh sách tên rạp
                datasets: [{
                    label: 'Doanh thu (VNĐ)',
                    data: @json($revenueByCinema->pluck('total_revenue')), // Lấy doanh thu của từng rạp
                    backgroundColor: 'rgba(54, 162, 235, 0.6)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            };

            const ctx = document.getElementById('revenueChartCinema').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: revenueDataCinema,
                options: {
                    indexAxis: 'y', // Hiển thị biểu đồ cột ngang
                    responsive: true,
                    scales: {
                        x: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Doanh thu (VNĐ)'
                            }
                        },
                        y: {
                            title: {
                                display: true,
                                text: 'Rạp'
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });


        });
    </script>
@endsection
