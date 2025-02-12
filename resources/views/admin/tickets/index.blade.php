@extends('admin.layouts.master')

@section('title')
    Danh sách hóa đơn
@endsection

@section('style-libs')
    <!--datatable css-->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" />
    <!--datatable responsive css-->
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" />

    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css">
@endsection



@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Danh sách hóa đơn @if (Auth::user()->cinema_id != '')
                        - {{ Auth::user()->cinema->name }}
                    @endif
                </h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Hóa đơn</a></li>
                        <li class="breadcrumb-item active">Danh sách</li>
                    </ol>
                </div>

            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                {{-- fillter --}}
                <div class="card-body border border-dashed border-end-0 border-start-0">
                    <form method="GET" action="{{ route('admin.tickets.index') }}">
                        <div class="row">
                            <div class="col-md-12">

                                <div class="row">
                                    @if (Auth::user()->hasRole('System Admin'))
                                        <div class="col-md-2">
                                            <label class="mb-0">Chi nhánh</label>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="mb-0">Rạp</label>
                                        </div>
                                    @endif
                                    <div class="col-md-2">
                                        <label class="mb-0">Ngày</label>
                                    </div>

                                    <div class="col-md-3">

                                        <label class="mb-0">Phim</label>
                                    </div>

                                    <div class="col-md-2">
                                        <label class="mb-0">Trạng thái</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">

                                <div class="row">
                                    @if (Auth::user()->hasRole('System Admin'))
                                        <div class="col-md-2">
                                            <select name="branch_id" id="branch" class="form-select">
                                                @foreach ($branches as $id => $name)
                                                    <option value="{{ $id }}"
                                                        {{ $id == $branchId ? 'selected' : '' }}>
                                                        {{ $name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <select name="cinema_id" id="cinema" class="form-select">
                                                @foreach ($cinemas as $id => $name)
                                                    <option value="{{ $id }}"
                                                        {{ $id == $cinemaId ? 'selected' : '' }}>
                                                        {{ $name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    @endif

                                    <div class="col-md-2">
                                        <input type="date" name="date" class="form-control"
                                            value="{{ $date }}">
                                    </div>

                                    <div class="col-md-3">

                                        <select name="movie_id" class="form-select">
                                            <option value="">Tất cả các phim</option>
                                            @foreach ($movies as $id => $name)
                                                <option value="{{ $id }}"
                                                    {{ $id == $movieId ? 'selected' : '' }}>
                                                    {{ $name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-2">
                                        <select name="status" class="form-select">
                                            <option value="">Tất cả</option>
                                            <option value="0" {{ $status === '0' ? 'selected' : '' }}>Chưa xuất vé
                                            </option>
                                            <option value="1" {{ $status === '1' ? 'selected' : '' }}>Đã xuất vé
                                            </option>
                                            <option value="2" {{ $status === '2' ? 'selected' : '' }}>Đã hết
                                                hạn</option>
                                        </select>
                                    </div>
                                    <div class="col-md-1">
                                        <button class="btn btn-success" type="submit">
                                            <i class="ri-equalizer-fill me-1 align-bottom"></i>Lọc
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>


                    </form>
                </div>

                @if (session()->has('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif
                @if (session()->has('error'))
                    <div class="alert alert-success">
                        {{ session('error') }}
                    </div>
                @endif

                <div class="card-body">
                    <table id="example" class="table table-bordered dt-responsive nowrap align-middle"
                        style="width:100%;">
                        <thead class='table-light'>
                            <tr>
                                <th>Mã vé</th>
                                <th>Thông tin người dùng</th>
                                <th class="text-center">Hình ảnh</th>
                                <th>Thông tin vé</th>
                                {{-- <th>Trạng thái</th> --}}
                                <th>Chức năng</th>
                            </tr>
                        </thead>
                        <tbody id="ticket-table-body">
                            @foreach ($tickets as $code => $groupTickets)
                                @php
                                    $ticket = $groupTickets->first();
                                    $url = $ticket->movie->img_thumbnail;
                                    if (!\Str::contains($url, 'http')) {
                                        $url = Storage::url($url);
                                    }
                                    $showtime = $ticket->showtime;
                                    $showtimeStart = $showtime
                                        ? \Carbon\Carbon::parse($showtime->start_time)->format('H:i')
                                        : 'Không có';
                                    $showtimeEnd = $showtime
                                        ? \Carbon\Carbon::parse($showtime->end_time)->format('H:i')
                                        : 'Không có';
                                @endphp
                                <tr>
                                    <td class="text-center">{{ $code }}</td>
                                    <td>
                                        <ul class="nav nav-sm flex-column">
                                            <li class="nav-item mb-1"><span class="fw-semibold">Người dùng:</span>
                                                {{ $ticket->user->name }}</li>
                                            <li class="nav-item mb-1"><span class="fw-semibold">Chức vụ:</span>
                                                <span
                                                    class="badge {{ $ticket->user->type === 'admin' ? 'bg-primary-subtle text-primary' : ' bg-secondary-subtle text-secondary' }}">{{ $ticket->user->type }}</span>
                                            </li>
                                            <li class="nav-item mb-1"><span class="fw-semibold">Email:</span>
                                                {{ $ticket->user->email }}</li>
                                            {{-- <li class="nav-item mb-1"><span class="fw-semibold">Số điện thoại:</span>
                                            {{ $ticket->user->phone }}</li> --}}
                                            <li class="nav-item mb-1"><span class="fw-semibold">Phương thức thanh
                                                    toán:</span> {{ $ticket->payment_name }}</li>
                                        </ul>
                                    </td>
                                    <td class="text-center">
                                        @if (!empty($ticket->movie->img_thumbnail))
                                            <img src="{{ $url }}" alt="Movie Thumbnail" class="rounded-2"
                                                width="100px">
                                        @else
                                            No image !
                                        @endif
                                    </td>
                                    <td>
                                        <ul class="nav nav-sm flex-column">
                                            <li class="nav-item mb-1"><span class="fw-semibold">Phim:</span>
                                                {{ $ticket->movie->name }}</li>
                                            <li class="nav-item mb-1"><span class="fw-semibold">Nơi chiếu:</span>
                                                {{ $ticket->cinema->branch->name }} - {{ $ticket->cinema->name }} -
                                                {{ $ticket->room->name }}
                                            </li>
                                            <li class="nav-item mb-1"><span class="fw-semibold">Ghế:</span>
                                                {{ $ticket->ticketSeats->pluck('seat.name')->implode(', ') }}
                                            </li>
                                            <li class="nav-item mb-1"><span class="fw-semibold">Tổng tiền:</span>
                                                {{ number_format($ticket->total_price) }} VNĐ
                                            </li>
                                            <li class="nav-item mb-1"><span class="fw-semibold">Trạng thái:</span>
                                                {{-- @if (now()->greaterThan($ticket->expiry))
                                                <span class="badge bg-danger">Đã hết hạn</span>
                                            @else --}}
                                                @switch($ticket->status)
                                                    @case('Chưa xuất vé')
                                                        <span class="badge bg-warning">Chưa xuất vé</span>
                                                    @break

                                                    @case('Đã hết hạn')
                                                        <span class="badge bg-danger">Đã hết hạn</span>
                                                    @break

                                                    @case('Đã xuất vé')
                                                        <span class="badge bg-success">Đã xuất vé</span>
                                                    @break
                                                @endswitch
                                                {{-- @endif --}}
                                            </li>
                                            <li class="nav-item mb-1"><span class="fw-semibold">Lịch chiếu:</span>
                                                {{ $showtimeStart }}
                                                ~ {{ $showtimeEnd }}
                                            </li>

                                            <li class="nav-item mb-1"><span class="fw-semibold">Ngày chiếu:</span>
                                                {{ \Carbon\Carbon::parse($ticket->showtime->date)->format('d/m/Y') }}
                                            </li>

                                            <li class="nav-item mb-1"><span class="fw-semibold">Thời hạn sử dụng:</span>
                                                {{ $ticket->expiry->format('H:i, d/m/Y') }}
                                            </li>
                                        </ul>
                                    </td>
                                    {{-- <td>
                                     <select class="form-select" data-original-status="{{ $ticket->status }}"
                                             data-ticket-id="{{ $ticket->id }}" onchange="changeStatus(this)"
                                         {{ $ticket->expiry->isPast() || $ticket->status == 'Đã xuất vé' ? 'disabled' : '' }}>
                                         <option value="Chưa xuất vé" {{ $ticket->status == 'Chưa xuất vé' ? 'selected' : '' }}>Chờ xác
                                             nhận
                                         </option>
                                         <option value="Đã xuất vé" {{ $ticket->status == 'Đã xuất vé' ? 'selected' : '' }}>Hoàn tất</option>
                                         @if ($ticket->expiry->isPast() && $ticket->status != 'Đã xuất vé')
                                             <option value="Đã hết hạn" selected disabled>Đã hết hạn</option>
                                         @endif
                                     </select>
                                 </td> --}}
                                    <td class="text-center">
                                        @can('Xem chi tiết hóa đơn')
                                            <a href="{{ route('admin.tickets.show', $ticket) }}">
                                                <button title="Chi tiết" class="btn btn-success btn-sm" type="button"><i
                                                        class="fas fa-eye"></i>
                                                </button>
                                            </a>
                                        @endcan
                                        {{-- @if ($ticket->status == 'Đã xuất vé')
                                        <a href="{{ route('admin.tickets.print', $ticket) }}">
                                            <button title="print" class="btn btn-success btn-sm" type="button"><i
                                                    class="ri-download-2-fill align-middle me-1"></i> In vé
                                            </button>
                                        </a>
                                        <a href="{{ route('admin.tickets.printCombo', $ticket) }}">
                                            <button title="print" class="btn btn-success btn-sm" type="button"><i
                                                    class="ri-download-2-fill align-middle me-1"></i> In combo
                                            </button>
                                        </a>
                                    @endif --}}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>

                    </table>
                </div>


            </div>
        </div><!--end col-->
    </div><!--end row-->
@endsection


@section('script-libs')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <!--datatable js-->
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/quagga/0.12.1/quagga.min.js"
            integrity="sha512-bCsBoYoW6zE0aja5xcIyoCDPfT27+cGr7AOCqelttLVRGay6EKGQbR6wm6SUcUGOMGXJpj+jrIpMS6i80+kZPw=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script> --}}
    <script>
        new DataTable("#example", {
            order: []
        });
    </script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        //cập nhật trạng thái
        /*function changeStatus(e) {
            if (confirm("Bạn có chắc chắn muốn thay đổi trạng thái vé không?")) {
                var ticketId = e.getAttribute('data-ticket-id');
                var newStatus = e.value;

                fetch(`/admin/tickets/${ticketId}/update-status`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        status: newStatus
                    })
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Trạng thái đã được cập nhật thành công!');
                            window.location.reload();
                        } else {
                            // Hiển thị thông báo lỗi từ server và load laji trang
                            alert(data.message || 'Lỗi khi cập nhật trạng thái.');
                            window.location.reload();

                            // Khôi phục trạng thái cũ nếu có lỗi
                            e.value = e.getAttribute("data-original-status");
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Đã xảy ra lỗi trong quá trình xử lý.');
                        e.value = e.getAttribute("data-original-status");
                    });
            } else {
                // Khôi phục trạng thái cũ nếu người dùng chọn "Cancel" trong confirm
                e.value = e.getAttribute("data-original-status");
            }
        }

        /*Hiển thị rạp*/
        $(document).ready(function() {
            // Lấy giá trị branchId và cinemaId từ Laravel
            var selectedBranchId = "{{ old('branch_id', '') }}";
            var selectedCinemaId = "{{ old('cinema_id', '') }}";

            // Xử lý sự kiện thay đổi chi nhánh
            $('#branch').on('change', function() {
                var branchId = $(this).val();
                var cinemaSelect = $('#cinema');
                cinemaSelect.empty();


                if (branchId) {
                    $.ajax({
                        url: "{{ env('APP_URL') }}/api/cinemas/" + branchId,
                        method: 'GET',
                        success: function(data) {
                            $.each(data, function(index, cinema) {
                                cinemaSelect.append('<option value="' + cinema.id +
                                    '" >' + cinema.name + '</option>');
                            });

                            // Chọn lại cinema nếu có selectedCinemaId
                            if (selectedCinemaId) {
                                cinemaSelect.val(selectedCinemaId);
                                selectedCinemaId = false;
                            }
                        }
                    });
                }
            });

            // Nếu có selectedBranchId thì tự động kích hoạt thay đổi chi nhánh để load danh sách cinema
            if (selectedBranchId) {
                $('#branch').val(selectedBranchId).trigger('change');

            }
        });

        /*modal quét qr*/
        /*document.addEventListener("DOMContentLoaded", function () {
            const scanModal = document.getElementById('scanModal');
            const scanAnotherBtn = document.getElementById("scanAnotherBtn");
            const errorMessage = document.getElementById("error-message");
            const barcodeResult = document.getElementById("barcode-result");

            // Khởi tạo sự kiện mở modal
            scanModal.addEventListener('shown.bs.modal', startScanner);
            scanModal.addEventListener('hidden.bs.modal', function () {
                stopScanner();
                clearBarcodeResult();
            });

            // Hàm khởi động Quagga
            function startScanner() {
                Quagga.init({
                    inputStream: {
                        type: "LiveStream",
                        target: document.querySelector("#camera"),
                        constraints: {
                            width: 640,
                            height: 380,
                            facingMode: "user" // Thay đổi thành "environment" neeus sử dụng camera sau
                        }
                    },
                    decoder: {
                        readers: ["code_128_reader"]
                    }
                }, function (err) {
                    if (err) {
                        console.log("Lỗi: ", err);
                        errorMessage.innerText = "Không thể truy cập camera, vui lòng kiểm tra quyền truy cập!";
                        return;
                    }
                    Quagga.start();
                });

                Quagga.onDetected(detectedCode);
            }

            // Hàm dừng Quagga
            function stopScanner() {
                Quagga.stop();
                Quagga.offDetected(detectedCode);
            }

            // Hàm xử lý mã quét được
            function detectedCode(result) {
                const code = result.codeResult.code;
                barcodeResult.innerText = code; // Hiển thị mã vạch quét được
                stopScanner(); // Dừng scanner sau khi đọc được mã

                // Gửi mã code qua AJAX
                fetch('{{ route('admin.tickets.processScan') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({code: code})
                })
                    .then(response => response.json())
                    .then(data => {
                        alert(data.message);
                        if (data.success && data.redirect_url) {
                            window.location.href = data.redirect_url;
                        }
                    })
                    .catch(error => {
                        console.error("Lỗi:", error);
                        errorMessage.innerText = 'Lỗi kết nối, vui lòng thử lại sau!';
                    });
            }

            // Hàm xóa mã vạch và thông báo lỗi
            function clearBarcodeResult() {
                barcodeResult.innerText = ""; // Xóa mã vạch quét được khi mở lại modal
                errorMessage.innerText = ""; // Xóa thông báo lỗi nếu có
            }

            // Xử lý khi bấm nút "Quét lại mã khác"
            scanAnotherBtn.addEventListener("click", function () {
                barcodeResult.innerText = ""; // Xóa mã quét được trước đó
                errorMessage.innerText = ""; // Xóa thông báo lỗi
                startScanner(); // Bắt đầu quét lại
            });
        });*/
    </script>
@endsection
