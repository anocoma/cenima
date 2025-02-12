@extends('client.layouts.master')

@section('title')
    Tài khoản của tôi
@endsection

@section('style-libs')
    <!--datatable css-->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" />
    <!--datatable responsive css-->
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" />

    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css">
    <style>
        .container_content {
            margin: 0 auto;
            background-color: white;
            padding: 0 !important;
        }

        .tab-content_info {
            padding: 25px 0;
        }
    </style>
@endsection

@section('content')
    <div class='info_account' style="background: #f3f3f9 ;padding-top: 50px; padding-bottom: 100px;">
        <div class="container container_content" style=" ">
            <div class="my-account-tabs">
                <a href="#my-account" role="tab" data-toggle="tab" class="tab-link">
                    <div class="my-account-tab {{ $page == 'my-account' ? 'my-account-active' : '' }}" role="presentation">
                        THÔNG TIN TÀI KHOẢN
                    </div>
                </a>
                <a href="#membership" role="tab" data-toggle="tab" class="tab-link">
                    <div class="my-account-tab {{ $page == 'membership' ? 'my-account-active' : '' }}">THẺ THÀNH VIÊN</div>
                </a>
                <a href="#cinema-journey" role="tab" data-toggle="tab" class="tab-link">
                    <div class="my-account-tab {{ $page == 'cinema-journey' ? 'my-account-active' : '' }}"
                        role="presentation">LỊCH SỬ GIAO DỊCH
                    </div>
                </a>
                <a href="#my-voucher" role="tab" data-toggle="tab" class="tab-link">
                    <div class="my-account-tab {{ $page == 'my-voucher' ? 'my-account-active' : '' }}">VOUCHER CỦA TÔI</div>
                </a>
            </div>


            <div class="col-md-12">
                <div class="tab-content tab-content_info">
                    {{-- Thông tin tài khoản --}}
                    <div id="my-account" class="tab-pane  {{ $page == 'my-account' ? 'in active' : 'fade' }} item-content"
                        role="tabpanel"> {{-- active --}}
                        <form action="{{ route('my-account.update') }}" method="post" enctype="multipart/form-data"
                            id="updateAccountForm">
                            @csrf
                            @method('PUT')

                            <div class="my-account-upload-container">
                                <div class="my-account-image-upload-container" id="img_thumbnail" name="img_thumbnail">
                                    @php
                                        $url = $user->img_thumbnail;

                                        if (!\Str::contains($url, 'http')) {
                                            $url = Storage::url($url);
                                        }

                                    @endphp
                                    @if (!empty($user->img_thumbnail))
                                        <img src="{{ $url }}">
                                    @else
                                        <img src="{{ asset('theme/client/images/user-dummy-img.jpg') }}">
                                    @endif

                                </div>
                                <div class="my-account-buttons">
                                    <input type="file" id="file-upload" name="img_thumbnail" accept="image/*"
                                        style="display: none;" />
                                    <label for="img_thumbnail" class="my-account-upload-btn" id="uploadBtn">Tải ảnh
                                        lên</label>
                                </div>

                            </div>

                            <div class="my-account-form-row">
                                <div class="my-account-form-group">
                                    <div class="my-account-mb-3">
                                        <label for="name"><span style="color: red;">*</span>&nbsp;Họ tên</label>
                                        <input type="text" class="my-account-form-control" placeholder="Họ và tên"
                                            name="name" id="name" value="{{ old('name', $user->name) }}">
                                        @error('name')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="my-account-mb-3">
                                        <label for="phone"><span style="color: red;">*</span>&nbsp;Số điện thoại</label>
                                        <i class="fa fa-phone-square phone-icon"></i>
                                        <input type="text" id="phone" class="my-account-form-control" name="phone"
                                            placeholder="Nhập số điện thoại của bạn"
                                            value="{{ old('phone', $user->phone) }}">
                                        @error('phone')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="my-account-mb-3">
                                        @if ($user->birthday == null)
                                            <label for="birthday"><span style="color: red;">*</span>&nbsp;Ngày sinh</label>
                                            <i class="fa fa-calendar birthday-icon"></i>
                                            <input type="date" id="birthday" value="{{ old('birthday') }}"
                                                class="my-account-form-control" name="birthday" placeholder="Ngày sinh" />
                                            @error('birthday')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        @else
                                            <label>Ngày sinh</label>
                                            <input disabled value="{{ \Carbon\Carbon::parse($user->birthday)->format('d/m/Y') }}" class="my-account-form-control"/>
                                        @endif

                                    </div>
                                    <div class="my-account-mb-3">
                                        <a href="#" id="changePasswordBtn" style="color: #ff7307;">Đổi mật
                                            khẩu?</a>
                                    </div>
                                </div>

                                <div class="my-account-form-group">
                                    <div class="my-account-mb-3">
                                        <label for="email">Email</label>
                                        <i class="fa fa-envelope email-icon"></i>
                                        <input  id="email" disabled class="my-account-form-control"
                                           value="{{ old('email', $user->email) }}">
                                    </div>
                                    <div class="my-account-mb-3">
                                        <label for="gender">Giới tính</label>
                                        <i class="fa fa-male sex-icon"></i>
                                        <div class="my-account-input-icon">
                                            <select name="gender" id="" class="my-account-form-control">
                                                @foreach ($genders as $gender)
                                                    <option value="{{ $gender }}" @selected($user->gender == $gender)>
                                                        {{ $gender }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="my-account-mb-3">
                                        <label for="address">Địa chỉ</label>
                                        <input type="text" class="my-account-form-control"
                                            placeholder="Số nhà, đường, ngõ xóm" name="address" id="address"
                                            value="{{ old('address', $user->address) }}">
                                    </div>
                                </div>
                            </div>

                            <div class="my-account-text-center my-account-my-3">
                                <button type="submit" class="my-account-btn">Cập nhật</button>
                            </div>
                        </form>
                    </div>
                    <div id="membership"
                        class="tab-pane  {{ $page == 'membership' ? 'in active' : 'fade' }} item-content"
                        role="tabpanel">
                        {{-- fade --}}
                        <div class="row">
                            <div class="col-md-12">
                                <div class='row-header'>
                                    Tổng quát
                                </div>
                                <div class="row">
                                    <div class="col-md-9">

                                        <div class='text-center rank_membership'>
                                            <p><span class="span_card">Cấp độ thẻ: </span><span
                                                    class="bold">{{ $user->membership->rank->name }}</span></p>
                                        </div>
                                        {{-- <div class="progress-wrapper">
                                            <div class="progress-info">
                                                <span>Số tiền đã chi tiêu</span>
                                                <span
                                                    class="amount">{{ number_format($user->membership->total_spent, 0, ',', '.') }}
                                                    <strong>VND</strong></span>
                                            </div>
                                            @php
                                                $maxAmount = 5000000; // Giới hạn tối đa
                                                $progress = ($user->membership->total_spent / $maxAmount) * 100;
                                            @endphp
                                            <div class="progress-bar-container">
                                                <div class="progress-bar-fill {{ $progress >= 100 ? 'full' : '' }}"
                                                    style="width: {{ $progress }}%;"></div>
                                            </div>
                                            <div class="milestone-container">
                                                <div class="milestone" style="left: 0;">
                                                    <span>Normal</span>
                                                    <span>0</span>
                                                </div>
                                                <div class="milestone" style="left: 40%;">
                                                    <span>VIP</span>
                                                    <span>2.000.000</span>
                                                </div>
                                                <div class="milestone" style="right: 0;">
                                                    <span>Platinum</span>
                                                    <span>5.000.000</span>
                                                </div>
                                            </div>
                                        </div> --}}

                                        <div class="progress-wrapper">
                                            <div class="progress-info">
                                                <span>Số tiền đã chi tiêu</span>
                                                <span class="amount">

                                                        <strong>{{ number_format($user->membership->total_spent, 0, ',', '.') }}    VND</strong>
                                                    </span>
                                            </div>

                                            @php
                                                $maxAmount = $ranks->max('total_spent');
                                                // Tính toán phần trăm tiến độ dựa trên maxAmount
                                                $progress = min(
                                                    ($user->membership->total_spent / $maxAmount) * 100,
                                                    100,
                                                );
                                            @endphp

                                            <div class="progress-bar-container">
                                                <div class="progress-bar-fill {{ $progress >= 100 ? 'full' : '' }}"
                                                    style="width: {{ $progress }}%;"></div>
                                            </div>

                                            <div class="milestone-container">
                                                @foreach ($ranks as $index => $rank)
                                                    <div class="milestone"
                                                        style="{{ $index === count($ranks) - 1 ? 'right: 0;' : 'left: ' . ($rank['total_spent'] / $maxAmount) * 100 . '%;' }}">
                                                        <span>{{ $rank['name'] }}</span>
                                                        <span>{{ number_format($rank['total_spent'], 0, ',', '.') }}
                                                            VND</span>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>


                                    </div>
                                    <div class="col-md-3 d-flex justify-content-center">
                                        <div style="margin: 0 auto; text-align: center;">
                                            {!! $barcode = DNS1D::getBarcodeHTML($user->membership->code, 'C128', 2.4, 100) !!}
                                            <p style="font-size: 16px; margin: 3px auto">{{ $user->membership->code }}</p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="points-info">
                                            <div><span class="span_label">Điểm đã tích lũy:</span> <span
                                                    class='bold'>{{ number_format($user->membership->pointHistories->where('type', App\Models\PointHistory::POINTS_ACCUMULATED)->sum('points'), 0, ',', '.') }}
                                                    điểm</span>
                                            </div>
                                            <div><span class="span_label">Điểm đã sử dụng:</span> <span
                                                    class='bold'>{{ number_format($user->membership->pointHistories->where('type', App\Models\PointHistory::POINTS_SPENT)->sum('points'), 0, ',', '.') }}
                                                    điểm</span>
                                            </div>
                                            <div><span class="span_label">Điểm hiện có:</span> <span
                                                    class='bold'>{{ number_format($user->membership->points, 0, ',', '.') }}
                                                    điểm</span></div>

                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="card-body">
                                    <div class='row-header'>
                                        Lịch sử điểm
                                    </div>
                                    <table id="pointHistory" class='table table-bordered dt-responsive nowrap'
                                        width="100%">
                                        <thead class='xanh-fpt'>
                                        <tr>
                                            <th>Thời gian</th>
                                            <th>Số điểm</th>
                                            <th>Nội dung sử dụng</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach ($user->membership->pointHistories()->latest('id')->get() as $pointHistory)
                                            <tr>
                                                <td>{{ $pointHistory->created_at->format('d/m/Y H:i') }}</td>
                                                <td>
                                                    {{ $pointHistory->type == App\Models\PointHistory::POINTS_ACCUMULATED
                                                        ? '+ ' . number_format($pointHistory->points, 0, ',', '.')
                                                        : '- ' . number_format($pointHistory->points, 0, ',', '.') }}
                                                </td>
                                                <td>{{ $pointHistory->type }}</td>

                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="my-voucher"
                        class="tab-pane  {{ $page == 'my-voucher' ? 'in active' : 'fade' }} item-content"
                        role="tabpanel">
                        {{-- fade --}}
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card-body">
                                    <div class='row-header'>
                                        Danh sách voucher
                                    </div>
                                    <table id="myVoucher" class='table table-bordered dt-responsive nowrap'
                                        width="100%">
                                        <thead class='xanh-fpt'>
                                            <tr>
                                                <th>Voucher</th>
                                                <th>Tiêu đề</th>
                                                <th>Giảm giá</th>

                                                <th>Thời gian</th>
                                                <th>Còn lại</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($vouchers as $voucher)
                                                <tr>
                                                    <td>{{ $voucher->code }}</td>
                                                    <td>{{ $voucher->title }}</td>
                                                    <td>{{ number_format($voucher->discount, 0, ',', '.') }} đ</td>

                                                    <td>
                                                        {{ \Carbon\Carbon::parse($voucher->start_date_time)->format('H:i, d/m/Y') }}
                                                        <strong>đến</strong>
                                                        {{ \Carbon\Carbon::parse($voucher->end_date_time)->format('H:i, d/m/Y') }}
                                                    </td>
                                                    <td>{{ $voucher->remaining_uses }} lần</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>


                    {{-- Hành trình điện ảnh --}}
                    <div id="cinema-journey"
                        class="tab-pane  {{ $page == 'cinema-journey' ? 'in active' : 'fade' }} item-content"
                        role="tabpanel">
                        {{-- fade --}}
                        <div class="row">
                            <div class="col-md-12">
                                <table class='table table-bordered dt-responsive nowrap' id="transactionHistory"
                                    width="100%">
                                    <thead class='xanh-fpt text-center'>
                                        <tr>
                                            <th>Mã đặt vé</th>
                                            <th>Hình ảnh</th>
                                            <th>Thông tin vé</th>
                                            <th>Thao tác</th>

                                    </thead>
                                    <tbody>
                                        @foreach ($tickets as $ticket)
                                            <tr>

                                                <td>{{ $ticket->code }}
                                                    <div>
                                                        @include('client.modals.ticket-detail', [
                                                            'ticket' => $ticket,
                                                        ])
                                                    </div>
                                                </td>
                                                <td>
                                                    @php
                                                        // Lấy thông tin movie từ ticketSeat đầu tiên trong nhóm
                                                        $url = $ticket->movie->img_thumbnail;

                                                        if (!\Str::contains($url, 'http')) {
                                                            $url = Storage::url($url);
                                                        }
                                                    @endphp

                                                    <img width="100% " src="{{ $url }}" alt="movie_img" />


                                                </td>
                                                <td>
                                                    <h3 class="movie-name-history">
                                                        <a
                                                            href="{{ route('movies.movie-detail', $ticket->movie->slug) }}">{{ $ticket->movie->name }}</a>
                                                    </h3>
                                                    <b>Ngày chiếu:</b>
                                                    {{ \Carbon\Carbon::parse($ticket->showtime->date)->format('d/m/Y') }}
                                                    <br>
                                                    <b>Giờ chiếu: </b>
                                                    {{ \Carbon\Carbon::parse($ticket->showtime->start_time)->format('H:i') }}
                                                    ~
                                                    {{ \Carbon\Carbon::parse($ticket->showtime->end_time)->format('H:i') }}
                                                    <br>
                                                    <b>Rạp chiếu:</b> {{ $ticket->cinema->name }} -
                                                    {{ $ticket->room->name }}
                                                    <br>
                                                    <b>Ghế ngồi:</b>
                                                    {{ implode(', ', $ticket->ticketSeats->pluck('seat.name')->toArray()) }}
                                                    <br>
                                                    <b>Trạng thái:</b>
                                                    <span
                                                        class="badge
                                                            @switch($ticket->status)
                                                                @case(App\Models\Ticket::NOT_ISSUED)
                                                                    badge-not-issued
                                                                    @break
                                                                @case(App\Models\Ticket::ISSUED)
                                                                    badge-issued
                                                                    @break
                                                                @case(App\Models\Ticket::EXPIRED)
                                                                    badge-expired
                                                                    @break
                                                            @endswitch
                                                        ">
                                                        {{ $ticket->status }}
                                                    </span>

                                                    <br>
                                                    <b>Tổng tiền thanh toán:</b>
                                                    {{ number_format($ticket->total_price, 0, ',', '.') }}
                                                    đ

                                                </td>
                                                <td>


                                                    <div class="action-icons d-flex flex-column">
                                                        <!-- Nút Chi Tiết -->
                                                        <button
                                                            class="btn btn-custom-primary  btn-sm mb-2 d-flex align-items-center"
                                                            title="Chi tiết"
                                                            onclick="showTicketDetail('{{ $ticket->code }}')">
                                                            <i class="fas fa-info-circle me-2"></i> Chi tiết
                                                        </button>
                                                        @if ($ticket->status == App\Models\Ticket::ISSUED)
                                                            <a
                                                                href="{{ route('movies.movie-detail', $ticket->movie->slug) }}">
                                                                <button
                                                                    class="btn btn-custom-warning btn-sm d-flex align-items-center"
                                                                    title="Đánh giá phim">
                                                                    <i class="fas fa-star me-2"></i> Đánh giá
                                                                </button>
                                                            </a>
                                                        @endif
                                                    </div>


                                                </td>


                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <div class="my-account-overlay" id="overlay">
        <div class="my-account-modal" id="changePasswordForm">
            <form id="changePasswordForm">
                <div class="my-account-mb-3">
                    <label for="old_password"><span style="color: red;">*</span>&nbsp;Mật khẩu hiện tại</label>
                    <input type="password" class="my-account-form-control" id="old_password" name="old_password"
                        placeholder="Nhập mật khẩu hiện tại">
                    <span id="old_password_error" class="text-danger"></span>
                </div>
                <div class="my-account-mb-3">
                    <label for="password"><span style="color: red;">*</span>&nbsp;Mật khẩu mới</label>
                    <input type="password" class="my-account-form-control" id="password" name="password"
                        placeholder="Nhập mật khẩu mới">
                    <span id="password_error" class="text-danger"></span>
                </div>
                <div class="my-account-mb-3">
                    <label for="password_confirmation"><span style="color: red;">*</span>&nbsp;Nhập lại mật khẩu
                        mới</label>
                    <input type="password" class="my-account-form-control" id="password_confirmation"
                        name="password_confirmation" placeholder="Nhập lại mật khẩu mới">
                    <span id="password_confirmation_error" class="text-danger"></span>
                </div>
                <div class="my-account-text-center">
                    <button type="submit" class="my-account-btn">Xác nhận</button>
                    <button type="button" class="my-account-btn" id="closeChangePassword">Hủy</button>
                </div>
            </form>

        </div>
    </div>
@endsection

@section('script-libs')
    </script>
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
    <script>
        new DataTable("#transactionHistory", {
            order: [],
            columnDefs: [{
                targets: 1,
                width: "120px"
            }],
            language: {
                search: "Tìm kiếm:",
                paginate: {
                    next: "Tiếp theo",
                    previous: "Trước"
                },
                lengthMenu: "Hiển thị _MENU_ mục",
                info: "Hiển thị từ _START_ đến _END_ trong tổng số _TOTAL_ mục",
        emptyTable: "Không có dữ liệu để hiển thị",
        zeroRecords: "Không tìm thấy kết quả phù hợp"
            }
        });
    </script>
    <script>
        new DataTable("#pointHistory", {
            order: [],
            language: {
                search: "Tìm kiếm:",
                paginate: {
                    next: "Tiếp theo",
                    previous: "Trước"
                },
                lengthMenu: "Hiển thị _MENU_ mục",
                info: "Hiển thị từ _START_ đến _END_ trong tổng số _TOTAL_ mục",
        emptyTable: "Không có dữ liệu để hiển thị",
        zeroRecords: "Không tìm thấy kết quả phù hợp"
            }

        });
    </script>
    <script>
        new DataTable("#myVoucher", {
            order: [],
            language: {
                search: "Tìm kiếm:",
                paginate: {
                    next: "Tiếp theo",
                    previous: "Trước"
                },
                lengthMenu: "Hiển thị _MENU_ mục",
                info: "Hiển thị từ _START_ đến _END_ trong tổng số _TOTAL_ mục",
        emptyTable: "Không có dữ liệu để hiển thị",
        zeroRecords: "Không tìm thấy kết quả phù hợp"
            }
        });
    </script>

    <script>
        $(document).ready(function() {
            // Xóa thông báo lỗi khi người dùng nhập vào các input trong form đổi mật khẩu
            $('#old_password, #password, #password_confirmation').on('input', function() {
                $(this).next('.text-danger').text(''); // Xóa nội dung lỗi ngay sau trường input
            });

            // AJAX cho cập nhật tài khoản
            $('#updateAccountForm').on('submit', function(e) {
                e.preventDefault();

                let formData = new FormData(this);

                // Xóa tất cả thông báo lỗi trước đó
                $('.text-danger').remove();

                $.ajax({
                    url: $(this).attr('action'),
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        if (response.success) {
                            location.reload(); // Tải lại trang nếu cập nhật thành công
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            for (let field in errors) {
                                let inputField = $(`[name="${field}"]`);
                                inputField.next('.text-danger').remove(); // Xóa lỗi cũ nếu có
                                inputField.after(
                                    `<span class="text-danger">${errors[field][0]}</span>`);
                            }
                        } else {
                            alert("Lỗi: " + xhr.responseJSON.message);
                        }
                    }
                });
            });

            // AJAX cho đổi mật khẩu
            $('#changePasswordForm').on('submit', function(e) {
                e.preventDefault();

                let formData = {
                    old_password: $('#old_password').val(),
                    password: $('#password').val(),
                    password_confirmation: $('#password_confirmation').val(),
                    _token: '{{ csrf_token() }}',
                };

                // Xóa tất cả thông báo lỗi trước đó
                $('#old_password_error, #password_error, #password_confirmation_error').text('');

                $.ajax({
                    url: '{{ route('my-account.changePasswordAjax') }}',
                    type: 'PUT',
                    data: formData,
                    success: function(response) {
                        if (response.success) {
                            location.reload(); // Tải lại trang nếu đổi mật khẩu thành công
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;

                            // Hiển thị thông báo lỗi cụ thể cho từng input
                            if (errors.old_password) {
                                $('#old_password').next('.text-danger').remove();
                                $('#old_password').after(
                                    `<span class="text-danger">${errors.old_password[0]}</span>`
                                );
                            }
                            if (errors.password) {
                                $('#password').next('.text-danger').remove();
                                $('#password').after(
                                    `<span class="text-danger">${errors.password[0]}</span>`
                                );
                            }
                            if (errors.password_confirmation) {
                                $('#password_confirmation').next('.text-danger').remove();
                                $('#password_confirmation').after(
                                    `<span class="text-danger">${errors.password_confirmation[0]}</span>`
                                );
                            }
                        } else if (xhr.status === 400) {
                            $('#old_password').next('.text-danger').remove();
                            $('#old_password').after(
                                `<span class="text-danger">${xhr.responseJSON.error}</span>`
                            );
                        }
                    },
                });
            });
        });
    </script>

    <script>
        // const divTabLinks = document.querySelectorAll('.tab-link div');
        // const itemContents = document.querySelectorAll('.item-content');


        // function activateTab(hash) {
        //     // Tắt tất cả các tab và nội dung
        //     divTabLinks.forEach(link => link.classList.remove('my-account-active'));
        //     itemContents.forEach(content => content.classList.remove('in', 'active'));
        //     console.log(itemContents);

        //     // Kích hoạt tab và nội dung tương ứng với hash
        //     const activeDiv = document.querySelector(`a[href="${hash}"] div`);
        //     const activeContent = document.querySelector(hash);

        //     if (activeDiv && activeContent) {
        //         activeDiv.classList.add('my-account-active'); // Thêm lớp 'active' và 'my-account' vào tab
        //         activeContent.classList.add('in', 'active');

        //     }
        // }

        // document.addEventListener('DOMContentLoaded', function() {
        //     const hash = window.location.hash || '#my-account'; // Mặc định tab "THÔNG TIN TÀI KHOẢN"
        //     activateTab(hash);
        // });
        // window.addEventListener('hashchange', function() {

        //     const hash = window.location.hash || '#my-account';
        //     activateTab(hash);
        // });
    </script>
@endsection
