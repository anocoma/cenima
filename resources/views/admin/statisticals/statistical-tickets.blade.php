@extends('admin.layouts.master')

@section('title')
    Thống kê hóa đơn
@endsection

@section('content')
    <div class="row">
        <div class="col">
            <div class="h-100">

                <form action="{{ route('admin.statistical-tickets') }}" method="GET" class="mb-3">
                    @include('admin.layouts.components.statistical-filter')
                </form>

                <div class="row">
                    {{-- <div class="col-xl-4">
                        <div class="card card-height-100">
                            <div class="card-header align-items-center">
                                <h4 class="card-title mb-0 flex-grow-1">Doanh thu theo khung giờ chiếu </h4>
                            </div>

                            <div class="card-body">
                                <canvas id="revenueChartTimeSlot"></canvas>
                            </div>
                        </div> 
                    </div>  --}}

                    <div class="col-xl-12">
                        <div class="card">
                            <div class="card-header align-items-center d-flex">
                                <h4 class="card-title mb-0 flex-grow-1">Thống kê hóa đơn
                                    @if (Auth::user()->cinema_id != '')
                                        - {{ Auth::user()->cinema->name }}
                                    @endif
                                </h4>
                            </div><!-- end card header -->

                            <canvas id="invoiceStatusChart" height="490"></canvas>
                        </div> <!-- .card-->
                    </div> <!-- .col-->
                </div> <!-- end row-->
            </div> <!-- end .h-100-->

        </div> <!-- end col -->
    </div>
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

    {{-- <script>
        // thống kê doanh thu theo khung giờ chiếu
        const revenueChartTimeSlot = document.getElementById('revenueChartTimeSlot').getContext('2d');
        const revenueChartTimeSlotData = {
            labels: @json(array_column($revenueTimeSlot, 'label')),
            datasets: [{
                label: 'Doanh thu (VNĐ)',
                data: @json(array_column($revenueTimeSlot, 'revenue')),
                backgroundColor: [
                    'rgb(247, 184, 75)',
                    'rgb(10, 179, 156)',
                    'rgb(240, 101, 72)'
                ]
            }]
        };

        new Chart(revenueChartTimeSlot, {
            type: 'doughnut', // Biểu đồ tròn
            data: revenueChartTimeSlotData,
            options: {
                responsive: true,
                plugins: {
                    datalabels: {
                        formatter: (value, context) => {
                            // Chuyển đổi giá trị revenue từ chuỗi sang số
                            const total = context.chart.data.datasets[0].data.reduce((sum, currentValue) =>
                                sum + parseFloat(currentValue), 0);

                            // Tính phần trăm chính xác dựa trên tổng
                            const percentage = ((parseFloat(value) / total) * 100).toFixed(2) + '%';
                            return percentage; // Hiển thị phần trăm
                        },
                        color: '#fff', // Màu chữ hiển thị
                        font: {
                            weight: 'bold',
                            size: 14 // Cỡ chữ
                        },
                        anchor: 'center',
                        align: 'center',
                        offset: 0 // Đặt lại vị trí hiển thị
                    },
                    tooltip: {
                        callbacks: {
                            label: function(tooltipItem) {
                                return tooltipItem.label + ': ' + tooltipItem.raw.toLocaleString() + ' VNĐ';
                            }
                        }
                    }
                }
            },
            plugins: [ChartDataLabels] // Kích hoạt plugin
        });
    </script> --}}

    <script>
        // thống kê hóa đơn
        const labels = @json($labels);
        const pendingData = @json($pending);
        const completedData = @json($completed);
        const expiredData = @json($expired);

        // Cấu hình biểu đồ
        const data = {
            labels: labels,
            datasets: [{
                    label: 'Chưa xuất vé',
                    data: pendingData,
                    borderColor: 'rgba(54, 162, 235, 1)',
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    tension: 0.4, // Làm mịn đường
                },
                {
                    label: 'Đã xuất vé',
                    data: completedData,
                    borderColor: 'rgba(75, 192, 192, 1)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    tension: 0.4,
                },
                {
                    label: 'Đã hết hạn',
                    data: expiredData,
                    borderColor: 'rgba(255, 99, 132, 1)',
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    tension: 0.4,
                }
            ]
        };

        const config = {
            type: 'line',
            data: data,
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: 'Thống kê hóa đơn theo trạng thái'
                    }
                },
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Ngày'
                        }
                    },
                    y: {
                        title: {
                            display: true,
                            text: 'Số lượng hóa đơn'
                        },
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1, // Khoảng cách giữa các số trên trục Y
                            callback: function(value) {
                                return Number.isInteger(value) ? value : null; // Chỉ hiển thị số nguyên
                            }
                        }
                    }
                }
            }
        };

        // Render biểu đồ
        const invoiceStatusChart = new Chart(
            document.getElementById('invoiceStatusChart'), config
        );
    </script>
@endsection
