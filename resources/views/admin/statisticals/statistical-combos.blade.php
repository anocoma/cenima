@extends('admin.layouts.master')

@section('title')
    Thống kê combo
@endsection

@section('content')
    <div class="row">
        <div class="col">
            <div class="h-100">

                <form action="{{ route('admin.statistical-combos') }}" method="GET" class="mb-3">
                    @include('admin.layouts.components.statistical-filter')
                </form>

                <div class="row">
                    <div class="col-xl-12">
                        <div class="card">
                            <div class="card-header align-items-center d-flex">
                                <h4 class="card-title mb-0 flex-grow-1">Thống kê Combo
                                    @if (Auth::user()->cinema_id != '')
                                        - {{ Auth::user()->cinema->name }}
                                    @endif
                                </h4>
                            </div><!-- end card header -->

                            <canvas id="comboChart" height="490"></canvas>
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

    <script>
        const labels = @json($comboStatistics->pluck('name'));
        const summaries = @json($comboStatistics->pluck('summary'));
    
        const data = {
            labels: labels,
            datasets: [{
                label: 'Combo Statistics (Số lượng & Doanh thu)',
                data: summaries.map(s => parseFloat(s.split(' ')[0])), // Hiển thị bằng số lượng
                backgroundColor: '#405189',
                borderColor: '#405189',
                borderWidth: 1
            }]
        };
    
        const config = {
            type: 'bar',
            data: data,
            options: {
                responsive: true,
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(tooltipItem) {
                                const index = tooltipItem.dataIndex;
                                return `${labels[index]}: ${summaries[index]}`;
                            }
                        }
                    },
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Số lượng'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Combo'
                        }
                    }
                }
            }
        };
    
        new Chart(document.getElementById('comboChart'), config);
    </script>
@endsection
