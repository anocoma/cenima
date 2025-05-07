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
                                    {{-- <span class="mdi--love-seat"></span> --}}
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
                                                            @for ($row = 0; $row < $matrixSeat['max_row']; $row++)
                                                                <tr>
                                                                    {{-- <td class="box-item">
                                                                        {{ chr(65 + $row) }}
                                                                    </td> --}}
                                                                    @for ($col = 0; $col < $matrixSeat['max_col']; $col++)
                                                                        <td class="row-seat">
                                                                            @foreach ($showtime->room->seats as $seat)
                                                                                @if ($seat->coordinates_x === $col + 1 && $seat->coordinates_y === chr(65 + $row))
                                                                                    @php
                                                                                        // // Lấy status từ bảng seat_showtimes thông qua pivot
                                                                                        // $seatStatus = $seat->showtimes
                                                                                        //     ->where(
                                                                                        //         'id',
                                                                                        //         $showtime->id,
                                                                                        //     )
                                                                                        //     ->first()->pivot
                                                                                        //     ->status;
                                                                                        // Lấy status và price từ bảng seat_showtimes thông qua pivot
                                                                                        $seatData = $seat->showtimes
                                                                                            ->where(
                                                                                                'id',
                                                                                                $showtime->id,
                                                                                            )
                                                                                            ->first()->pivot;
                                                                                        $seatStatus =
                                                                                            $seatData->status;
                                                                                        $seatPrice =
                                                                                            $seatData->price;
                                                                                    @endphp

                                                                                    @if ($seat->type_seat_id == 1)
                                                                                        <span
                                                                                            data-seat-id="{{ $seat->id }}"
                                                                                            class="solar--sofa-3-bold seat span-seat {{ $seatStatus }}">
                                                                                            <span
                                                                                                class="seat-label">{{ $seat->name }}</span>
                                                                                        </span>
                                                                                        <p
                                                                                            style="font-size: 13px; font-weight: 600">
                                                                                            {{ $seatPrice }}</p>
                                                                                    @endif
                                                                                    @if ($seat->type_seat_id == 2)
                                                                                        <span
                                                                                            data-seat-id="{{ $seat->id }}"
                                                                                            class="mdi--love-seat text-muted seat span-seat {{ $seatStatus }}">
                                                                                            <span
                                                                                                class="seat-label">{{ $seat->name }}</span>
                                                                                        </span>
                                                                                        <p
                                                                                            style="font-size: 13px; font-weight: 600">
                                                                                            {{ $seatPrice }}</p>
                                                                                    @endif
                                                                                    @if ($seat->type_seat_id == 3)
                                                                                        <span
                                                                                            data-seat-id="{{ $seat->id }}"
                                                                                            class="game-icons--sofa seat span-seat {{ $seatStatus }}">
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
                                                {{-- <div><span class="seat available"></span> Ghế trống</div> --}}
                                                <div><span class="solar--sofa-3-bold text-muted"></span> Ghế Thường
                                                </div>
                                                <div><span class="mdi--love-seat text-muted"></span> Ghế Vip</div>
                                                <div><span class="game-icons--sofa text-muted"></span> Ghế Đôi</div>
                                                <div>
                                                    <p>Tổng tiền:</p>
                                                    <p id="total-price" class="bold">0 đ</p>
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
                                        {{-- <li class="bold"> Tổng tiền: <span class="bold">190.000đ</span></li> --}}
                                    </ul>
                                </div>

                                <div class="total-price-choose-seat float_left">
                                    <form action="{{ route('choose-seat-test', $showtime->id) }}" method="POST">
                                        @csrf
                                        {{-- <input type="hidden" name="showtime_id" value="{{ $showtime->id }}"> --}}
                                        <input type="hidden" name="showtimeId" value="{{ $showtime->id }}">
                                        <input type="hidden" name="seatId" id="hidden-seat-ids">
                                        <input type="hidden" name="selected_seats" id="hidden-selected-seats">
                                        <input type="hidden" name="total-price" id="total-price">
                                        <button type="submit">Tiếp tục</button>
                                    </form>
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
    <script>
        // document.addEventListener('DOMContentLoaded', () => {
        //     const seats = document.querySelectorAll('.seat'); // Lấy tất cả các ghế
        //     const selectedSeatsDisplay = document.getElementById('selected-seats'); // Vị trí hiển thị ghế đã chọn
        //     const hiddenSelectedSeats = document.getElementById(
        //         'hidden-selected-seats'); // Input ẩn cho ghế đã chọn
        //     const hiddenSeatIds = document.getElementById('hidden-seat-ids'); // Input ẩn cho ID ghế
        //     const submitButton = document.querySelector('button[type="submit"]'); // Nút "Tiếp tục"

        //     let selectedSeats = []; // Mảng lưu trữ tên các ghế đã chọn
        //     let selectedSeatIds = []; // Mảng lưu trữ ID các ghế đã chọn

        //     seats.forEach(seat => {
        //         seat.addEventListener('click', () => {
        //             const seatLabel = seat.querySelector('.seat-label').textContent; // Lấy tên ghế
        //             const seatId = seat.getAttribute(
        //                 'data-seat-id'); // Lấy ID ghế từ thuộc tính data

        //             // Kiểm tra ghế đã được đặt hoặc giữ chưa
        //             if (!seat.classList.contains('reserved') && !seat.classList.contains(
        //                     'pre-booked')) {
        //                 seat.classList.toggle('selected'); // Thêm hoặc xóa lớp 'selected'

        //                 if (seat.classList.contains('selected')) {
        //                     if (selectedSeats.length < 8) {
        //                         // Thêm tên ghế và ID vào mảng nếu số ghế đã chọn dưới 8
        //                         selectedSeats.push(seatLabel);
        //                         selectedSeatIds.push(seatId); // Thêm ID vào mảng ID ghế
        //                     } else {
        //                         // Nếu đã chọn 8 ghế, không cho chọn thêm và hiện cảnh báo
        //                         seat.classList.remove('selected');
        //                         alert('Bạn chỉ được chọn tối đa 8 ghế!');
        //                     }
        //                 } else {
        //                     // Xóa tên ghế và ID khỏi mảng
        //                     selectedSeats = selectedSeats.filter(s => s !== seatLabel);
        //                     selectedSeatIds = selectedSeatIds.filter(id => id !== seatId);
        //                 }

        //                 // Cập nhật danh sách ghế đã chọn hiển thị trên giao diện
        //                 selectedSeatsDisplay.textContent = selectedSeats.join(', ');
        //                 hiddenSelectedSeats.value = selectedSeats.join(
        //                     ', '); // Cập nhật input ẩn với ghế đã chọn
        //                 hiddenSeatIds.value = JSON.stringify(
        //                     selectedSeatIds); // Cập nhật input ẩn với ID ghế đã chọn
        //             }
        //         });
        //     });

        //     // Kiểm tra khi người dùng bấm nút "Tiếp tục"
        //     submitButton.addEventListener('click', (event) => {
        //         if (selectedSeats.length === 0) {
        //             event.preventDefault(); // Ngăn không cho form gửi đi
        //             alert(
        //                 'Bạn chưa chọn ghế nào! Vui lòng chọn ghế trước khi tiếp tục.'
        //                 ); // Hiển thị thông báo
        //             return false; // Ngăn chặn hành động tiếp theo
        //         } else if (selectedSeats.length > 8) {
        //             event.preventDefault(); // Ngăn không cho form gửi đi
        //             alert('Bạn chỉ được chọn tối đa 8 ghế!'); // Hiển thị thông báo
        //         }
        //     });
        // });

        document.addEventListener('DOMContentLoaded', () => {
            const seats = document.querySelectorAll('.seat'); // Lấy tất cả các ghế
            const selectedSeatsDisplay = document.getElementById('selected-seats'); // Vị trí hiển thị ghế đã chọn
            const hiddenSelectedSeats = document.getElementById(
            'hidden-selected-seats'); // Input ẩn cho ghế đã chọn
            const hiddenSeatIds = document.getElementById('hidden-seat-ids'); // Input ẩn cho ID ghế
            const totalPriceElement = document.getElementById('total-price'); // Phần tử hiển thị tổng tiền
            const submitButton = document.querySelector('button[type="submit"]'); // Nút "Tiếp tục"

            let selectedSeats = []; // Mảng lưu trữ tên các ghế đã chọn
            let selectedSeatIds = []; // Mảng lưu trữ ID các ghế đã chọn
            let totalPrice = 0; // Biến lưu trữ tổng tiền

            seats.forEach(seat => {
                seat.addEventListener('click', () => {
                    const seatLabel = seat.querySelector('.seat-label').textContent; // Lấy tên ghế
                    const seatId = seat.getAttribute(
                    'data-seat-id'); // Lấy ID ghế từ thuộc tính data
                    const seatPrice = parseInt(seat.nextElementSibling.textContent.replace(
                        /[^0-9]/g, '')); // Lấy giá ghế

                    // Kiểm tra ghế đã được đặt hoặc giữ chưa
                    if (!seat.classList.contains('reserved') && !seat.classList.contains(
                            'pre-booked')) {
                        seat.classList.toggle('selected'); // Thêm hoặc xóa lớp 'selected'

                        if (seat.classList.contains('selected')) {
                            if (selectedSeats.length < 8) {
                                // Thêm tên ghế và ID vào mảng nếu số ghế đã chọn dưới 8
                                selectedSeats.push(seatLabel);
                                selectedSeatIds.push(seatId); // Thêm ID vào mảng ID ghế

                                totalPrice += seatPrice; // Cộng giá ghế vào tổng tiền
                            } else {
                                // Nếu đã chọn 8 ghế, không cho chọn thêm và hiện cảnh báo
                                seat.classList.remove('selected');
                                alert('Bạn chỉ được chọn tối đa 8 ghế!');
                            }
                        } else {
                            // Xóa tên ghế và ID khỏi mảng
                            selectedSeats = selectedSeats.filter(s => s !== seatLabel);
                            selectedSeatIds = selectedSeatIds.filter(id => id !== seatId);

                            totalPrice -= seatPrice; // Trừ giá ghế khỏi tổng tiền
                        }

                        // Cập nhật danh sách ghế đã chọn hiển thị trên giao diện
                        selectedSeatsDisplay.textContent = selectedSeats.join(', ');
                        hiddenSelectedSeats.value = selectedSeats.join(
                        ', '); // Cập nhật input ẩn với ghế đã chọn
                        hiddenSeatIds.value = JSON.stringify(
                        selectedSeatIds); // Cập nhật input ẩn với ID ghế đã chọn

                        // Cập nhật tổng tiền hiển thị
                        totalPriceElement.textContent = totalPrice.toLocaleString() +
                        ' đ'; // Cập nhật tổng tiền
                    }
                });
            });

            // Kiểm tra khi người dùng bấm nút "Tiếp tục"
            submitButton.addEventListener('click', (event) => {
                if (selectedSeats.length === 0) {
                    event.preventDefault(); // Ngăn không cho form gửi đi
                    alert(
                    'Bạn chưa chọn ghế nào! Vui lòng chọn ghế trước khi tiếp tục.'); // Hiển thị thông báo
                    return false; // Ngăn chặn hành động tiếp theo
                } else if (selectedSeats.length > 8) {
                    event.preventDefault(); // Ngăn không cho form gửi đi
                    alert('Bạn chỉ được chọn tối đa 8 ghế!'); // Hiển thị thông báo
                }
            });
        });


        // Thời gian đếm ngược (10 phút = 600 giây)
        let timeLeft = 600; // 600 giây tương đương 10 phút
        const timerElement = document.getElementById('timer');

        // Hàm đếm ngược thời gian
        const countdown = setInterval(() => {
            // Tính số phút và giây còn lại
            const minutes = Math.floor(timeLeft / 60);
            const seconds = timeLeft % 60;

            // Hiển thị thời gian còn lại ở định dạng mm:ss
            timerElement.textContent = `${minutes}:${seconds < 10 ? '0' + seconds : seconds}`;

            // Giảm thời gian còn lại
            timeLeft--;

            // Khi thời gian kết thúc (hết 0 giây)
            if (timeLeft < 0) {
                clearInterval(countdown); // Dừng đếm ngược

                // Hiển thị thông báo và quay về trang chủ
                alert('Hết thời gian! Bạn sẽ được chuyển về trang chủ.');
                window.location.href = '/'; // Điều hướng về trang chủ ("/")
            }
        }, 1000); // Cập nhật mỗi giây
    </script>
@endsection
