@extends('client.layouts.master')

@section('title')
    Chọn ghế
@endsection

@section('content')
    <div class="st_dtts_wrapper float_left">
        <div class="container container-choose-seat">
            <div class="row">
                <div class="mb-3 title-choose-seat">
                    <a href="/" class="cam">Trang chủ </a> <strong>></strong> <a href="#" class="cam">Đặt vé
                    </a> <strong>></strong> <a href="/movies/{{ $showtime->movie->slug }}"
                        class="cam">{{ $showtime->movie->name }}</a>
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
                                                            @for ($row = 0; $row < $matrixSeat['max_row']; $row++)
                                                                <tr>
                                                                    @for ($col = 0; $col < $matrixSeat['max_col']; $col++)
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
                                                                                    <td class="row-seat">
                                                                                        <span
                                                                                            data-seat-id="{{ $seat->id }}"
                                                                                            data-seat-price="{{ $seatPrice }}"
                                                                                            data-type="1"
                                                                                            class="solar--sofa-3-bold seat span-seat {{ $seatStatus }}"
                                                                                            id="seat-{{ $seat->id }}">
                                                                                            <span
                                                                                                class="seat-label">{{ $seat->name }}</span>
                                                                                        </span>
                                                                                    </td>
                                                                                @endif
                                                                                @if ($seat->type_seat_id == 2)
                                                                                    <td class="row-seat">
                                                                                        <span
                                                                                            data-seat-id="{{ $seat->id }}"
                                                                                            data-seat-price="{{ $seatPrice }}"
                                                                                            data-type="2"
                                                                                            class="mdi--love-seat text-muted seat span-seat {{ $seatStatus }}"
                                                                                            id="seat-{{ $seat->id }}">
                                                                                            <span
                                                                                                class="seat-label">{{ $seat->name }}</span>
                                                                                        </span>
                                                                                    </td>
                                                                                @endif
                                                                                @if ($seat->type_seat_id == 3)
                                                                                    <td class="row-seat" colspan="2">
                                                                                        <span
                                                                                            data-seat-id="{{ $seat->id }}"
                                                                                            data-seat-price="{{ $seatPrice }}"
                                                                                            data-type="3"
                                                                                            class="game-icons--sofa seat-double seat span-seat {{ $seatStatus }}"
                                                                                            id="seat-{{ $seat->id }}">
                                                                                            <span
                                                                                                class="seat-label">{{ $seat->name }}</span>
                                                                                        </span>
                                                                                    </td>
                                                                                @endif
                                                                            @endif
                                                                        @endforeach
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
                                                    <p id="timer" class="bold">{{ gmdate('i:s', $remainingSeconds) }}
                                                    </p>
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
                                        <li>
                                            <span><i class="fa fa-tags icons"></i> Thể loại</span>
                                            <span class="bold">{{ $showtime->movie->category }}</span>
                                        </li>
                                        <li>
                                            <span><i class="fa fa-clock-o icons"></i> Thời lượng</span>
                                            <span class="bold">{{ $showtime->movie->duration }} phút</span>
                                        </li>
                                        <hr style="border: 0; border-top: 2px dashed #7f7d7d; margin: 20px 0; ">
                                        <li>
                                            <span><i class="fa-solid fa-landmark"></i> Rạp chiếu</span>
                                            <span class="bold">{{ $showtime->room->cinema->name }}</span>
                                        </li>
                                        <li>
                                            <span><i class="fa-regular fa-calendar-days"></i> Ngày chiếu</span>
                                            <span
                                                class="bold">{{ \Carbon\Carbon::parse($showtime->date)->format('d/m/Y') }}</span>
                                        </li>
                                        <li>
                                            <span><i class="fa fa-clock-o icons"></i> Giờ chiếu</span>
                                            <span
                                                class="bold">{{ \Carbon\Carbon::parse($showtime->start_time)->format('H:i') }}</span>
                                        </li>
                                        <li>
                                            <span><i class="fa-solid fa-desktop"></i> Phòng chiếu</span>
                                            <span class="bold">{{ $showtime->room->name }}</span>
                                        </li>
                                        <li>
                                            <span><i class="fa-solid fa-cubes"></i> Ghế ngồi</span>
                                            <span id="selected-seats" class="bold"></span>
                                        </li>
                                    </ul>
                                </div>

                                <div class="total-price-choose-seat">
                                    <form action="{{ route('save-information', $showtime->id) }}" method="POST"
                                        id="checkout-form">
                                        @csrf
                                        <input type="hidden" name="showtimeId" value="{{ $showtime->id }}"
                                            id="showtime-id">
                                        <input type="hidden" name="seatId" id="hidden-seat-ids">
                                        <input type="hidden" name="selected_seats_name" id="hidden-selected-seats-name">
                                        <input type="hidden" name="total_price" id="hidden-total-price">
                                        <!-- Thêm id vào input hidden để cập nhật remainingSeconds bằng JS -->
                                        <input type="hidden" name="remainingSeconds" id="remaining-seconds"
                                            value="{{ $remainingSeconds }}">
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

@endsection

@section('styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('theme/client/css/choose-seat.checkout.css') }}" />
@endsection

@section('scripts')
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite('resources/js/choose-seat.js')
    <script>
        // Định nghĩa các biến toàn cục
        const selectedSeatsDisplay = document.getElementById('selected-seats');
        const hiddenSelectedSeats = document.getElementById('hidden-selected-seats-name');
        const hiddenSeatIds = document.getElementById('hidden-seat-ids');
        const totalPriceElement = document.getElementById('total-price');
        const hiddenTotalPrice = document.getElementById('hidden-total-price');
        const submitButton = document.getElementById('submit-button');
        const showtimeId = document.getElementById('showtime-id').value;

        let selectedSeats = [];
        let selectedSeatIds = [];
        let totalPrice = 0;

        // Đọc danh sách ghế từ biến PHP 
        const selectedSaveSeats = @json($selectedSeats);

        // Kiểm tra và thiết lập trạng thái ghế từ danh sách selectedSaveSeats
        if (Array.isArray(selectedSaveSeats) && selectedSaveSeats.length > 0) {
            selectedSaveSeats.forEach(seatId => setInitialSeatSelection(seatId));
        }

        // Thiết lập trạng thái ghế mà mình đang chọn
        function setInitialSeatSelection(seatId) {
            const seatElement = document.querySelector(`.seat[data-seat-id="${seatId}"]`);
            if (seatElement) {
                seatElement.classList.add('selected'); // Đánh dấu ghế là đã chọn
                seatElement.classList.remove('hold'); // Bỏ trạng thái available

                const seatLabel = seatElement.querySelector('.seat-label').textContent;
                selectedSeats.push(seatLabel);
                selectedSeatIds.push(seatId);
                totalPrice += parseInt(seatElement.getAttribute('data-seat-price'));
            }
        }

        // Cập nhật hiển thị ban đầu cho tổng tiền và danh sách ghế đã chọn
        updateDisplay();

        // Event delegation để xử lý sự kiện click cho tất cả các ghế
        document.querySelector('.seat-selection').addEventListener('click', async (event) => {
            const seat = event.target.closest('.seat');
            if (!seat) return;

            // Xử lý chọn ghế
            handleSeatSelection(seat);
        });

        // Xử lý chọn ghế
        async function handleSeatSelection(seat) {
            const seatId = seat.getAttribute('data-seat-id');
            const seatLabel = seat.querySelector('.seat-label').textContent;
            const seatPrice = parseInt(seat.getAttribute('data-seat-price'));

            // Kiểm tra trạng thái ghế (hold hoặc sold)
            if (seat.classList.contains('hold') || seat.classList.contains('sold')) {
                alert(seat.classList.contains('hold') ? 'Ghế này đã được giữ!' : 'Ghế này đã được bán!');
                return;
            }

            // Lưu thời gian bắt đầu
            const startTime = new Date();

            // Xử lý chọn hoặc bỏ chọn ghế
            if (seat.classList.contains('selected')) {
                releaseSeat(seat, seatLabel, seatId, seatPrice);
            } else {
                if (selectedSeats.length >= 8) {
                    alert('Bạn chỉ được chọn tối đa 8 ghế!');
                    return;
                }
                selectSeat(seat, seatLabel, seatId, seatPrice);
            }

            // Lưu thời gian kết thúc
            const endTime = new Date();
            const processingTime = endTime - startTime; // thời gian xử lý tính bằng mili giây

            console.log(`Thời gian xử lý: ${processingTime} ms`);

            // Cập nhật hiển thị danh sách ghế đã chọn và tổng tiền
            updateDisplay();
        }

        // Chọn ghế
        function selectSeat(seat, seatLabel, seatId, seatPrice) {
            seatId = parseInt(seatId); // Ép kiểu thành số nguyên

            if (!selectedSeatIds.includes(seatId)) { // Kiểm tra ghế đã có trong mảng chưa
                selectedSeats.push(seatLabel);
                selectedSeatIds.push(seatId);
                totalPrice += seatPrice;

                seat.classList.toggle('selected');
                seat.classList.toggle('available');

                // Cập nhật server
                updateSeatOnServer(seatId, 'hold');
                updateDisplay();
            }
        }

        // Bỏ chọn ghế
        function releaseSeat(seat, seatLabel, seatId, seatPrice) {
            seatId = parseInt(seatId); // Ép kiểu thành số nguyên

            selectedSeats = selectedSeats.filter(s => s !== seatLabel);
            selectedSeatIds = selectedSeatIds.filter(id => id !== seatId);
            totalPrice -= seatPrice;

            seat.classList.toggle('selected');
            seat.classList.toggle('available');

            // Cập nhật server
            updateSeatOnServer(seatId, 'release');
            updateDisplay();
        }

        // Cập nhật ghế trên server
        async function updateSeatOnServer(seatId, action) {
            try {
                await axios.post('/update-seat', {
                    seat_id: seatId,
                    showtime_id: showtimeId,
                    action: action
                });
            } catch (error) {
                console.error(`Lỗi ${action === 'hold' ? 'giữ' : 'hủy'} ghế:`, error);
                throw error; // Ném lỗi để có thể xử lý ở nơi gọi
            }
        }

        // Cập nhật hiển thị
        function updateDisplay() {
            selectedSeatsDisplay.textContent = selectedSeats.join(', ');
            hiddenSelectedSeats.value = selectedSeats.join(',');
            hiddenSeatIds.value = selectedSeatIds.join(',');
            totalPriceElement.textContent = totalPrice.toLocaleString() + ' Vnđ';
            hiddenTotalPrice.value = totalPrice;
        }


        // Hàm kiểm tra xem có ghế trống nằm giữa hai ghế được chọn không (cho ghế sole)
        function checkSoleSeats() {
            const rows = document.querySelectorAll('.table-seat tr');
            let soleSeatsMessage = '';
            let isSoleSeatIssue = false;

            rows.forEach(row => {
                const seatsInRow = Array.from(row.querySelectorAll('.seat'));
                let selectedIndexes = [];

                seatsInRow.forEach((seat, index) => {
                    const seatType = seat.getAttribute('data-type'); // Lấy loại ghế từ data-type
                    if (seat.classList.contains('selected') && seatType !==
                        '3') { // Bỏ qua ghế đôi hoặc ghế có type=3
                        selectedIndexes.push(index);
                    }
                });

                for (let i = 0; i < selectedIndexes.length - 1; i++) {
                    const gap = selectedIndexes[i + 1] - selectedIndexes[i];
                    // Kiểm tra khoảng cách giữa hai ghế chọn là 2 (tức là có một ghế trống ở giữa)
                    if (gap === 2) {
                        const emptySeatIndex = selectedIndexes[i] + 1;
                        const emptySeat = seatsInRow[emptySeatIndex];

                        // Bỏ qua nếu ghế ở giữa có trạng thái sold hoặc hold
                        if (!emptySeat.classList.contains('sold') && !emptySeat.classList.contains('hold')) {
                            isSoleSeatIssue = true;
                            soleSeatsMessage += emptySeat.querySelector('.seat-label').textContent + ' ';
                        }
                    }
                }
            });

            return {
                isSoleSeatIssue,
                soleSeatsMessage
            };
        }

        // Hàm kiểm tra xem ghế ngoài cùng có bị trống không khi ghế ngay cạnh được chọn
        function checkAdjacentEdgeSeats() {
            const rows = document.querySelectorAll('.table-seat tr');
            let edgeSeatsMessage = '';
            let isEdgeSeatIssue = false;

            rows.forEach(row => {
                const seatsInRow = row.querySelectorAll('.seat');
                if (seatsInRow.length >= 2) {
                    const firstSeat = seatsInRow[0];
                    const secondSeat = seatsInRow[1];
                    const lastSeat = seatsInRow[seatsInRow.length - 1];
                    const beforeLastSeat = seatsInRow[seatsInRow.length - 2];

                    // Bỏ qua nếu ghế đầu là ghế đôi, có type=3 hoặc có trạng thái sold/hold
                    if (!firstSeat.classList.contains('selected') &&
                        secondSeat.classList.contains('selected') &&
                        firstSeat.getAttribute('data-type') !== '3' &&
                        !firstSeat.classList.contains('sold') &&
                        !firstSeat.classList.contains('hold')) {
                        isEdgeSeatIssue = true;
                        edgeSeatsMessage += firstSeat.querySelector('.seat-label').textContent + ' ';
                    }

                    // Bỏ qua nếu ghế cuối là ghế đôi, có type=3 hoặc có trạng thái sold/hold
                    if (!lastSeat.classList.contains('selected') &&
                        beforeLastSeat.classList.contains('selected') &&
                        lastSeat.getAttribute('data-type') !== '3' &&
                        !lastSeat.classList.contains('sold') &&
                        !lastSeat.classList.contains('hold')) {
                        isEdgeSeatIssue = true;
                        edgeSeatsMessage += lastSeat.querySelector('.seat-label').textContent + ' ';
                    }
                }
            });

            return {
                isEdgeSeatIssue,
                edgeSeatsMessage
            };
        }

        // Kiểm tra cả hai điều kiện trước khi submit form
        submitButton.addEventListener('click', (event) => {
            const {
                isEdgeSeatIssue,
                edgeSeatsMessage
            } = checkAdjacentEdgeSeats();
            const {
                isSoleSeatIssue,
                soleSeatsMessage
            } = checkSoleSeats();

            if (selectedSeats.length === 0) {
                event.preventDefault();
                alert('Bạn chưa chọn ghế nào! Vui lòng chọn ghế trước khi tiếp tục.');
                return false;
            } else if (selectedSeats.length > 8) {
                event.preventDefault();
                alert('Bạn chỉ được chọn tối đa 8 ghế!');
            } else if (isEdgeSeatIssue) {
                event.preventDefault();
                alert(`Bạn không được để trống ghế: ${edgeSeatsMessage}`);
                return false;
            } else if (isSoleSeatIssue) {
                event.preventDefault();
                alert(`Bạn không được để trống ghế: ${soleSeatsMessage}`);
                return false;
            }
        });

        // Lấy giá trị thời gian còn lại từ server-side PHP và gán vào biến JavaScript
        let timeLeft = {{ $remainingSeconds }}; // Thời gian còn lại tính bằng giây
        const timerElement = document.getElementById('timer');
        const remainingSecondsInput = document.getElementById('remaining-seconds');
        const checkoutForm = document.getElementById('checkout-form');

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

        // Cập nhật remainingSeconds trước khi form được submit
        checkoutForm.addEventListener('submit', function() {
            // Gán giá trị thời gian còn lại (timeLeft) vào input hidden
            remainingSecondsInput.value = timeLeft;
        });
    </script>
@endsection
