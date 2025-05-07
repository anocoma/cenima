@extends('client.layouts.master')

@section('title')
    Chọn ghế
@endsection

@section('content')
    <div class="st_dtts_wrapper float_left">
        <div class="container container-choose-seat">
            <div class="row">
                <div class="mb-3 title-choose-seat">
                    <a href="#">Trang chủ ></a> <a href="#">Đặt vé ></a> <a
                        href="#">{{ $showtime->movie->name }}</a>
                </div>
                <div class="col-xl-8 col-lg-8 col-md-12 col-sm-12 col-12">
                    <div class="st_dtts_left_main_wrapper float_left">
                        <div class="row">
                            <div class="col-md-12 box-list-status-seat">
                                <div class="border my-3">
                                    <div class="list-seats"><span class="mdi--love-seat text-muted"></span>
                                        <span class="status-seat">Ghế trống</span>
                                    </div>
                                    <div class="list-seats"><span class="mdi--love-seat text-primary"></span>
                                        <span class="status-seat">Ghế đang chọn</span>
                                    </div>
                                    <div class="list-seats"><span class="mdi--love-seat text-blue"></span>
                                        <span class="status-seat">Ghế đang được giữ</span>
                                    </div>
                                    <div class="list-seats"><span class="mdi--love-seat text-danger"></span>
                                        <span class="status-seat">Ghế đã bán</span>
                                    </div>
                                </div>
                                <div class="">
                                    <div>
                                        <div class="container-screen">
                                            <div class="container-detail-seat">
                                                <div class="screen">Màn Hình Chiếu</div>
                                                <div class="seat-selection">
                                                    <table class="table-seat">
                                                        <tbody>
                                                            {{-- lưu chữ id ghế vào session vd: load lại trang thì tự động select lại ghế --}}
                                                            <input type="hidden" id="selected-seats-session"
                                                                value="{{ json_encode($selectedSeats) }}">

                                                            @for ($row = 0; $row < $matrixSeat['max_row']; $row++)
                                                                <tr>
                                                                    @for ($col = 0; $col < $matrixSeat['max_col']; $col++)
                                                                        <td class="row-seat">
                                                                            @foreach ($showtime->room->seats as $seat)
                                                                                @if ($seat->coordinates_x === $col + 1 && $seat->coordinates_y === chr(65 + $row))
                                                                                    @php
                                                                                        $seatData = $seat->showtimes
                                                                                            ->where('id', $showtime->id)
                                                                                            ->first()->pivot;
                                                                                        $seatStatus = $seatData->status;
                                                                                        $seatPrice = $seatData->price;
                                                                                    @endphp

                                                                                    @if ($seat->type_seat_id == 1)
                                                                                        <span
                                                                                            data-seat-id="{{ $seat->id }}"
                                                                                            data-seat-price="{{ $seatPrice }}"
                                                                                            class="solar--sofa-3-bold seat span-seat {{ $seatStatus }}"
                                                                                            id="seat-{{ $seat->id }}">
                                                                                            <span
                                                                                                class="seat-label">{{ $seat->name }}</span>
                                                                                        </span>
                                                                                    @endif
                                                                                    @if ($seat->type_seat_id == 2)
                                                                                        <span
                                                                                            data-seat-id="{{ $seat->id }}"
                                                                                            data-seat-price="{{ $seatPrice }}"
                                                                                            class="mdi--love-seat text-muted seat span-seat {{ $seatStatus }}"
                                                                                            id="seat-{{ $seat->id }}">
                                                                                            <span
                                                                                                class="seat-label">{{ $seat->name }}</span>
                                                                                        </span>
                                                                                    @endif
                                                                                    @if ($seat->type_seat_id == 3)
                                                                                        <span
                                                                                            data-seat-id="{{ $seat->id }}"
                                                                                            data-seat-price="{{ $seatPrice }}"
                                                                                            class="game-icons--sofa seat span-seat {{ $seatStatus }}"
                                                                                            id="seat-{{ $seat->id }}">
                                                                                            <span
                                                                                                class="seat-label">{{ $seat->name }}</span>
                                                                                        </span>
                                                                                    @endif
                                                                                @endif
                                                                            @endforeach
                                                                        </td>
                                                                    @endfor
                                                                </tr>
                                                            @endfor
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>

                                            <div class="legend">
                                                <div><span class="solar--sofa-3-bold text-muted"></span> Ghế Thường
                                                </div>
                                                <div><span class="mdi--love-seat text-muted"></span> Ghế Vip</div>
                                                <div><span class="game-icons--sofa text-muted"></span> Ghế Đôi</div>
                                                <div>
                                                    <p>Tổng tiền:</p>
                                                    <p id="total-price" class="bold">0 Vnđ</p>
                                                </div>
                                                <div>
                                                    <p>Thời gian còn lại:</p>
                                                    <p id="timer" class="bold">10:00</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="st_dtts_bs_wrapper float_left info-movie">
                                <div class="st_dtts_bs_heading float_left">
                                    <p>Thông tin phim</p>
                                </div>
                                <div class=" float_left">

                                    <ul>
                                        <li>
                                            <div>
                                                @php
                                                    $url = $showtime->movie->img_thumbnail;

                                                    if (!\Str::contains($url, 'http')) {
                                                        $url = Storage::url($url);
                                                    }
                                                @endphp
                                                <img width="150px" src="{{ $url }}" alt="">
                                            </div>
                                            <div>
                                                <h3>{{ $showtime->movie->name }}</h3>
                                                <br>
                                                <p>{{ $showtime->format }}</p>
                                            </div>

                                        </li>
                                        <li>Thể loại: <span class="bold">{{ $showtime->movie->category }}</span></li>
                                        <li> Thời lượng: <span class="bold">{{ $showtime->movie->duration }}
                                                phút</span>
                                        </li>
                                        <hr>
                                        <li> Rạp chiếu: <span class="bold">{{ $showtime->room->cinema->name }}</span>
                                        </li>
                                        <li> Ngày chiếu: <span
                                                class="bold">{{ \Carbon\Carbon::parse($showtime->movie->release_date)->format('d/m/Y') }}</span>
                                        </li>
                                        <li> Giờ chiếu: <span
                                                class="bold">{{ \Carbon\Carbon::parse($showtime->start_time)->format('H:i') }}</span>
                                        </li>
                                        <li> Phòng chiếu: <span class="bold">{{ $showtime->room->name }}</span></li>
                                        <li>Ghế ngồi: <span id="selected-seats" class="bold"></span></li>
                                    </ul>
                                </div>

                                <div class="total-price-choose-seat float_left">
                                    <div class="total-price-choose-seat float_left">
                                        <form action="{{ route('save-information', $showtime->id) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="showtimeId" value="{{ $showtime->id }}"
                                                id="showtime-id">
                                            <input type="hidden" name="seatId" id="hidden-seat-ids">
                                            <input type="hidden" name="selected_seats_name" id="hidden-selected-seats-name">
                                            <input type="hidden" name="total_price" id="hidden-total-price">
                                            <a href="{{ route('showtimes') }}">Quay lại</a>
                                            <button id="submit-button" type="submit">Tiếp tục</button>
                                        </form>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('scripts')
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite('resources/js/choose-seat.js')
@endsection
