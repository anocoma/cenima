@extends('admin.layouts.master')

@section('title')
    Thông tin vé
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Thông tin hóa đơn</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.tickets.index') }}">Danh sách</a></li>
                        <li class="breadcrumb-item active">Chi tiết</li>
                    </ol>
                </div>

            </div>
        </div>
    </div>
    {{-- đây là giao diện vé khi in --}}
    <div id="invoice">
        <div class="invoice-container">
            <div class="invoice-content">
                <h2 class="invoice-title">Hóa đơn chi tiết</h2>

                <div class="invoice-details">
                    <strong>Chi nhánh công ty Poly Cinemas vietnam tại {{ $oneTicket->cinema->branch->name }}</strong><br>
                    Địa chỉ: 69 {{ $oneTicket->cinema->name }} - {{ $oneTicket->cinema->branch->name }}<br>
                    MST: 012147901412
                    <hr>
                    <strong>Poly Cinemas {{ $oneTicket->cinema->name }} -
                        {{ $oneTicket->cinema->branch->name }}</strong><br>
                    Thời gian đặt vé: {{ \Carbon\Carbon::parse($oneTicket->created_at)->format('H:i d/m/Y') }}
                    <hr>
                    @php
                        $ticketSeat = $oneTicket->ticketSeats()->first();
                        $rating = App\Models\Movie::getRatingByName($oneTicket->movie->rating);
                    @endphp
                    <strong>{{ $oneTicket->movie->name }} ({{ $oneTicket->showtime->format }}) </strong><br>
                    ({{ $rating['name'] }}) {{ $rating['description'] }}<br>
                    <strong>Phòng:</strong> {{ $ticket->room->name }} <br>
                    <strong>Ghế:</strong> {{ $ticket->ticketSeats->pluck('seat.name')->implode(', ') }} <br>
                    <span>
                        <strong>Suất chiếu:</strong>
                        {{ \Carbon\Carbon::parse($oneTicket->showtime->start_time)->format('H:i') }} ~
                        {{ \Carbon\Carbon::parse($oneTicket->showtime->start_time)->format('H:i') }}
                    </span><br>

                    <span>
                        <strong>Ngày chiếu:</strong>
                        {{ \Carbon\Carbon::parse($oneTicket->showtime->date)->format('d/m/Y') }}
                    </span><br>

                    <hr>
                    @if ($ticket->ticketCombos->isNotEmpty())
                        <div class="ticket-info border-bottom-dashed border-top-dashed mt-2">
                            @foreach ($oneTicket->ticketCombos as $ticketCombo)
                                <p><b>{{ $ticketCombo->combo->name }} x {{ $ticketCombo->quantity }}
                                        ({{ number_format($ticketCombo->combo->price_sale) }}
                                        vnđ)
                                    </b></p>

                                <ul>
                                    @foreach ($ticketCombo->combo->food as $food)
                                        <li>{{ $food->name }} x {{ $food->pivot->quantity }}</li>
                                    @endforeach
                                </ul>
                            @endforeach
                        </div>
                        <hr>
                    @endif
                </div>

                <div class="invoice-summary">
                    <div><span>Giá vé:</span><span>{{ number_format($totalPriceSeat, 0, ',', '.') }} vnd</span></div>
                    @if ($ticket->ticketCombos->isNotEmpty())
                        <div><span>Giá combo:</span><span>{{ number_format($totalComboPrice, 0, ',', '.') }} vnd</span>
                        </div>
                    @endif
                    <div><span>Giảm giá:</span><span>{{ number_format($ticket->voucher_discount, 0, ',', '.') }} vnd</span>
                    </div>
                    <div><span>Điểm Poly:</span><span>{{ number_format($ticket->point_discount, 0, ',', '.') }} vnd</span>
                    </div>
                    <div><strong>Thành
                            tiền:</strong><strong>{{ number_format($ticket->total_price, 0, ',', '.') }}vnd</strong></div>
                </div>

                <div class="barcode">
                    {!! $barcode !!}
                </div>
                <div class="invoice-code">{{ $oneTicket->code }}</div>
            </div>
        </div>

        {{-- hoa don combo --}}
        @if ($ticket->ticketCombos->isNotEmpty())
            <div class="invoice-container">
                <div class="invoice-content">
                    <h2 class="invoice-title">Hóa đơn combo</h2>

                    <div class="invoice-details">
                        <strong>Chi nhánh công ty Poly Cinemas vietnam tại
                            {{ $oneTicket->cinema->branch->name }}</strong><br>
                        Địa chỉ: 69 {{ $oneTicket->cinema->name }} - {{ $oneTicket->cinema->branch->name }}<br>
                        mst: 012147901412
                        <hr>
                        <strong>Poly Cinemas {{ $oneTicket->cinema->name }} -
                            {{ $oneTicket->cinema->branch->name }}</strong><br>
                        Thời gian đặt vé: {{ \Carbon\Carbon::parse($oneTicket->created_at)->format('H:i d/m/Y') }}
                        <hr>
                        <div class="ticket-info border-bottom-dashed mt-2">
                            @foreach ($oneTicket->ticketCombos as $ticketCombo)
                                @php
                                    $combo = $ticketCombo->combo;
                                    $price = $combo->price_sale > 0 ? $combo->price_sale : $combo->price;
                                    $totalPrice = $price * $ticketCombo->quantity;
                                @endphp

                                <p><b>{{ $combo->name }} x {{ $ticketCombo->quantity }}
                                        ({{ number_format($combo->price_sale) }}
                                        VND)
                                    </b></p>

                                <ul>
                                    @foreach ($combo->food as $food)
                                        <li>{{ $food->name }} x {{ $food->pivot->quantity }}</li>
                                    @endforeach
                                </ul>
                            @endforeach
                        </div>
                        <hr>
                    </div>

                    <div class="invoice-summary">
                        <div><strong>Thành tiền:</strong><strong>{{ number_format($totalComboPrice, 0, ',', '.') }}
                                VND</strong></div>
                    </div>

                    <div class="barcode">
                        {!! $barcode !!}
                    </div>
                    <div class="invoice-code">{{ $oneTicket->code }}</div>
                </div>
            </div>
        @endif

        {{-- hoa don ve --}}
        @foreach ($oneTicket->ticketSeats as $seat)
            <div class="invoice-container">
                <div class="invoice-content">
                    <h2 class="invoice-title">Vé xem phim</h2>


                    <div class="invoice-details">
                        <strong>Chi nhánh công ty Poly Cinemas vietnam tại
                            {{ $oneTicket->cinema->branch->name }}</strong><br>
                        Địa chỉ: 69 {{ $oneTicket->cinema->name }} - {{ $oneTicket->cinema->branch->name }}<br>
                        mst: 012147901412
                        <hr>
                        <strong>Poly Cinemas {{ $oneTicket->cinema->name }} -
                            {{ $oneTicket->cinema->branch->name }}</strong><br>
                        Thời gian đặt vé: {{ \Carbon\Carbon::parse($oneTicket->created_at)->format('H:i d/m/Y') }}
                        <hr>
                        @php
                            $ticketSeat = $oneTicket->ticketSeats()->first();
                            $rating = App\Models\Movie::getRatingByName($oneTicket->movie->rating);
                        @endphp
                        <strong>{{ $oneTicket->movie->name }} ({{ $oneTicket->showtime->format }})</strong><br>
                        ({{ $rating['name'] }})
                        {{ $rating['description'] }}<br>
                        <strong>Phòng:</strong> {{ $ticket->room->name }}<br>
                        <strong>Ghế:</strong> {{ $seat->seat->name }} <br>
                        <span>
                            <strong>Suất chiếu:</strong>
                            {{ \Carbon\Carbon::parse($oneTicket->showtime->start_time)->format('H:i') }} ~
                            {{ \Carbon\Carbon::parse($oneTicket->showtime->start_time)->format('H:i') }}
                        </span><br>

                        <span>
                            <strong>Ngày chiếu:</strong>
                            {{ \Carbon\Carbon::parse($oneTicket->showtime->date)->format('d/m/Y') }}
                        </span>
                        <hr>
                    </div>

                    <div class="invoice-summary">
                        <div><strong>Giá vé:</strong><span>{{ number_format($seat->price, 0, ',', '.') }} VND</span></div>
                    </div>

                    <div class="barcode">
                        {!! $barcode !!}
                    </div>
                    <div class="invoice-code">{{ $oneTicket->code }}</div>

                </div>
            </div>
        @endforeach
    </div>

    <div class="row">
        <div class="col-xl-9">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <div class="card-title flex-grow-1 mb-0">
                            <a href="{{ route('admin.tickets.index') }}" class="btn btn-primary btn-sm">
                                <i class=" ri-reply-fill align-middle me-1"></i>Danh sách</a>
                        </div>

                        <div class="flex-shrink-0">
                            <!-- Static Backdrop -->
                            @if (now()->lt($ticket->expiry))
                                <button type="button" class="btn btn-success btn-sm"
                                    {{ $ticket->status == App\Models\Ticket::ISSUED ? 'onclick=printInvoice()' : ' ' }}
                                    data-bs-toggle="modal" data-bs-target="#confirmTicket">
                                    <i class="ri-download-2-fill align-middle me-1"></i> In vé
                                </button>
                            @endif
                            <!-- confirmTicket Modal -->
                            @if ($ticket->status == App\Models\Ticket::NOT_ISSUED)
                                <div class="modal fade" id="confirmTicket" data-bs-backdrop="static"
                                    data-bs-keyboard="false" tabindex="-1" role="dialog"
                                    aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-body text-center p-5">
                                                <div class="mt-4">
                                                    <h4 class="mb-3">Xác nhận xuất vé</h4>
                                                    <p class="text-muted mb-4"> Vui lòng xác nhận và thay đổi trạng thái
                                                        thành đã xuất vé.
                                                    </p>
                                                    <div class="hstack gap-2 justify-content-center">
                                                        <a id="confirmPrintBtn" class="btn btn-success"
                                                            data-bs-dismiss="modal">Xác
                                                            nhận</a>
                                                        <a class="btn btn-link link-success fw-medium"
                                                            data-bs-dismiss="modal"><i
                                                                class="ri-close-line me-1 align-middle"></i> Hủy</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive table-card">
                        <table class="table table-nowrap align-middle table-borderless mb-0">
                            <thead class="table-light text-muted">
                                <tr>
                                    <th scope="col">Phim</th>
                                    <th scope="col">Suất chiếu</th>
                                    <th scope="col">Ghế ngồi</th>
                                    <th scope="col" class="text-end">Tổng tiền ghế</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        @php
                                            $img = $oneTicket->first();
                                            $url = $img->movie->img_thumbnail;
                                            if (!\Str::contains($url, 'http')) {
                                                $url = Storage::url($url);
                                            }
                                        @endphp
                                        <div style="display: flex; justify-content: center">
                                            @if (!empty($img->movie->img_thumbnail))
                                                <img src="{{ $url }}" alt="Movie Thumbnail" class="rounded-2"
                                                    width="100px">
                                            @else
                                                No image!
                                            @endif
                                        </div>
                                        <div style="display: flex; justify-content: center">
                                            <p class="mt-2 mb-0 fs-5 link-primary"><b>{{ $oneTicket->movie->name }}</b></p>
                                        </div>
                                    </td>
                                    <td>
                                        <p><span class="fw-semibold">Độ tuổi:</span> {{ $oneTicket->movie->rating }}</p>
                                        <p>
                                            <span class="fw-semibold">Thời lượng: </span>
                                            {{ $oneTicket->movie->duration }} phút
                                        </p>
                                        <p><span class="fw-semibold">Định dạng:</span> {{ $oneTicket->showtime->format }}
                                        </p>
                                        <p>
                                            <span class="fw-semibold">Thể loại: </span>
                                            {{ $oneTicket->movie->category }}
                                        </p>
                                        <p>
                                            <span class="fw-semibold">Địa điểm: </span>
                                            {{ $oneTicket->cinema->branch->name }} -
                                            {{ $oneTicket->cinema->name }} -
                                            {{ $oneTicket->room->name }}
                                        </p>
                                        <p>
                                            <span class="fw-semibold">Lịch chiếu: </span>
                                            {{ \Carbon\Carbon::parse($oneTicket->showtime->start_time)->format('H:i') }} ~
                                            {{ \Carbon\Carbon::parse($oneTicket->showtime->end_time)->format('H:i') }}
                                            ( {{ \Carbon\Carbon::parse($oneTicket->showtime->date)->format('d/m/Y') }} )
                                        </p>
                                    </td>
                                    <td>
                                        <p>
                                            <span
                                                class="link-primary fw-medium">{{ $ticket->ticketSeats->pluck('seat.name')->implode(', ') }}
                                            </span>
                                        </p>
                                    </td>
                                    <td class="fw-medium text-end">
                                        @php
                                            $totalPriceSeats = 0;
                                            foreach ($ticket->ticketSeats as $ticketSeat) {
                                                $totalPriceSeats += $ticketSeat->price;
                                            }
                                        @endphp
                                        <p>
                                            {{ number_format($totalPriceSeats, 0, ',', '.') }} vnđ
                                        </p>
                                    </td>
                                </tr>
                            </tbody>

                            @if ($ticket->ticketCombos->isNotEmpty())
                                <thead class="table-light text-muted">
                                    <tr>
                                        <th scope="col">Combo</th>
                                        <th scope="col">Chi tiết</th>
                                        <th scope="col">Số lượng x giá</th>
                                        <th scope="col" class="text-end">Giá combo</th>
                                </thead>
                                <tbody>
                                    @foreach ($ticket->ticketCombos as $ticketCombo)
                                        <tr>
                                            <td>
                                                @php

                                                    $url = $ticketCombo->combo->img_thumbnail;
                                                    if (!\Str::contains($url, 'http')) {
                                                        $url = Storage::url($url);
                                                    }
                                                @endphp
                                                <div style="display: flex; justify-content: center">
                                                    @if (!empty($ticketCombo->combo->img_thumbnail))
                                                        <img src="{{ $url }}" alt="Movie Thumbnail"
                                                            class="rounded-2" width="70px">
                                                    @else
                                                        No image!
                                                    @endif
                                                </div>
                                                <div style="display: flex; justify-content: center">
                                                    <p class="mt-2 mb-0 fs-6 link-primary">
                                                        <b>{{ $ticketCombo->combo->name }}</b>
                                                    </p>
                                                </div>
                                            </td>
                                            <td>
                                                @foreach ($ticketCombo->combo->food as $itemFood)
                                                    <li>
                                                        {{ $itemFood->name }} x
                                                        ({{ $itemFood->pivot->quantity }})
                                                    </li>
                                                @endforeach
                                            </td>
                                            <td>
                                                <span class="link-primary fw-medium">
                                                    {{ $ticketCombo->quantity }} x
                                                    {{ number_format($ticketCombo->combo->price_sale, 0, ',', '.') }} vnđ
                                                </span>
                                            </td>
                                            <td class="fw-medium text-end">
                                                {{ number_format($ticketCombo->price, 0, ',', '.') }} vnđ
                                            </td>
                                        </tr>
                                    @endforeach

                                </tbody>
                            @endif

                            <tbody class="border-top border-top-dashed">
                                <tr>
                                    <td colspan="3"></td>
                                    <td colspan="1" class="fw-medium p-0">
                                        <table class="table table-borderless mb-0">
                                            <tbody>
                                                <tr>
                                                    <td>Giảm giá :</td>
                                                    <td class="text-end">
                                                        {{ number_format($ticket->voucher_discount, 0, ',', '.') }} vnđ
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Điểm Poly :</td>
                                                    <td class="text-end">
                                                        {{ number_format($ticket->point_discount, 0, ',', '.') }} vnđ</td>
                                                </tr>
                                                <tr class="border-top border-top-dashed">
                                                    <th scope="row">Tổng tiền :</th>
                                                    <th class="text-end">
                                                        {{ number_format($ticket->total_price, 0, ',', '.') }}
                                                        vnđ</th>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!--end card-->

        </div>
        <!--end col-->
        <div class="col-xl-3">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex">
                        <h5 class="card-title flex-grow-1 mb-0">Trạng thái vé</h5>
                        <div class="flex-shrink-0">
                            <span href="javascript:void(0);">
                                @if (now()->greaterThan($ticket->expiry))
                                    <span class="badge fs-11 bg-danger-subtle text-danger">
                                        Đã hết hạn <br>
                                        <span>{{ \Carbon\Carbon::parse($ticket->expiry)->locale('vi')->translatedFormat('H:i - j/n/Y') }}</span>
                                    </span>
                                @elseif ($ticket->status == 'Chưa xuất vé')
                                    <span class="badge fs-12 bg-warning-subtle text-warning p-2">
                                        Chưa xuất vé
                                    </span>
                                @elseif($ticket->status == 'Đã xuất vé')
                                    <span class="badge fs-11 bg-success-subtle text-success">
                                        Đã xuất vé <br>
                                        <span>({{ \Carbon\Carbon::parse($ticket->updated_at)->locale('vi')->translatedFormat('H:i - j/n/Y') }})</span>
                                    </span>
                                @endif

                            </span>
                        </div>
                    </div>
                </div>
                <div class="card-body">

                    <div class="text-center">
                        <div class="d-flex justify-content-center">{!! $barcode !!}</div>
                        <p class="text-muted mb-0 mt-2"><b>{{ $ticket->code }}</b></p>
                    </div>
                </div>
            </div>
            <!--end card-->

            <div class="card">
                <div class="card-header">
                    <div class="d-flex">
                        <h5 class="card-title flex-grow-1 mb-0">Thông tin người đặt</h5>
                        <div class="flex-shrink-0">
                            <a href="{{ route('admin.users.show', $ticket->user->id) }}" class="link-secondary">Xem chi
                                tiết</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0 vstack gap-3">
                        <li>
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    @php
                                        $user = $ticket->user;
                                        $url = $user->img_thumbnail;
                                        if (!\Str::contains($url, 'http')) {
                                            $url = Storage::url($url);
                                        }
                                    @endphp

                                    @if (!empty($user->img_thumbnail))
                                        <img src="{{ $url }}" alt="Movie Thumbnail" width="50px"
                                            class="avatar-sm rounded">
                                    @else
                                        <img class="avatar-sm rounded"
                                            src="{{ env('APP_URL') . '/theme/admin/' }}assets/images/users/user-dummy-img.jpg"
                                            alt="Header Avatar">
                                    @endif
                                    {{-- <img src="assets/images/users/avatar-3.jpg" alt="" class="avatar-sm rounded"> --}}
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="fs-14 mb-1">{{ $ticket->user->name }}</h6>
                                    <p class="text-muted mb-0">{{ $ticket->user->type }}</p>
                                </div>
                            </div>
                        </li>
                        <li><i class="ri-mail-line me-2 align-middle text-muted fs-16"></i>{{ $ticket->user->email }}
                        </li>
                        <li><i class="ri-phone-line me-2 align-middle text-muted fs-16"></i>{{ $ticket->user->phone }}
                        </li>
                    </ul>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0"><i class="ri-secure-payment-line align-bottom me-1 text-muted"></i>Thông
                        tin thanh toán</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2">
                        <div class="flex-shrink-0">
                            <p class="text-muted mb-0">Thanh toán vào lúc:</p>
                        </div>
                        <div class="flex-grow-1 ms-2">
                            <h6 class="mb-0">
                                {{ \Carbon\Carbon::parse($ticket->created_at)->locale('vi')->translatedFormat('H:i - j/n/Y') }}
                            </h6>
                        </div>

                    </div>
                    <div class="d-flex align-items-center mb-2">
                        <div class="flex-shrink-0">
                            <p class="text-muted mb-0">Phương thức thanh toán:</p>
                        </div>
                        <div class="flex-grow-1 ms-2">
                            <h6 class="mb-0">{{ $ticket->payment_name }}</h6>
                        </div>
                    </div>
                    <div class="d-flex align-items-center mb-2">
                        <div class="flex-shrink-0">
                            <p class="text-muted mb-0">Tên khách hàng:</p>
                        </div>
                        <div class="flex-grow-1 ms-2">
                            <h6 class="mb-0">{{ $ticket->user->name }}</h6>
                        </div>
                    </div>
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <p class="text-muted mb-0">Tổng tiền:</p>
                        </div>
                        <div class="flex-grow-1 ms-2">
                            <h6 class="mb-0">{{ number_format($ticket->total_price, 0, ',', '.') }} VNĐ</h6>
                        </div>
                    </div>
                </div>
            </div>
            <!--end card-->
        </div>
        <!--end col-->
    </div><!--end card-->
@endsection

@section('style-libs')
    <script src="https://printjs-4de6.kxcdn.com/print.min.js"></script>
    <link rel="stylesheet" href="https://printjs-4de6.kxcdn.com/print.min.css">
    <link rel="stylesheet" href="{{ asset('theme/admin/assets/css/order.css') }}">
@endsection

@section('script-libs')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function printInvoice() {
            printJS({
                printable: 'invoice', // ID hoặc phần tử bạn muốn in
                type: 'html',
                css: '{{ asset('theme/admin/assets/css/order.css') }}'
            });
        }
    </script>
    <script>
        document.getElementById('confirmPrintBtn').addEventListener('click', function() {
            fetch('{{ route('admin.tickets.confirm', $ticket) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                })
                .then(response => response.json())
                .then(data => {
                    if (data.message) {
                        window.location.reload();
                    }

                })
                .catch(error => {
                    console.error('Lỗi:', error);
                    alert('Có lỗi xảy ra khi xử lý yêu cầu');
                });
        });
    </script>
    <script>
        @if (session('confirm'))
            printInvoice();
        @endif
    </script>
@endsection
