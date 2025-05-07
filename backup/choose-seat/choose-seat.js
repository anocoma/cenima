import './bootstrap';
document.addEventListener('DOMContentLoaded', () => {
    const seats = document.querySelectorAll('.seat');
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

    // Lấy danh sách ghế đã được lưu từ session
    const selectedSeatsFromSession = JSON.parse(document.getElementById('selected-seats-session').value);

    // Chuyển đổi đối tượng thành mảng các giá trị (seatId)
    const seatIds = Object.values(selectedSeatsFromSession);

    // Kiểm tra nếu seatIds là mảng hợp lệ và không rỗng
    if (Array.isArray(seatIds) && seatIds.length > 0) {
        seatIds.forEach(seatId => {
            const seatElement = document.querySelector(`.seat[data-seat-id="${seatId}"]`);
            if (seatElement) {
                seatElement.classList.add('selected'); // Đánh dấu ghế là đã chọn
                seatElement.classList.remove('hold'); // Bỏ trạng thái available

                const seatLabel = seatElement.querySelector('.seat-label').textContent;
                selectedSeats.push(seatLabel);
                selectedSeatIds.push(seatId);
                totalPrice += parseInt(seatElement.getAttribute('data-seat-price'));
            }
        });
    } else {
        console.error('ahihi');
    }


    // Cập nhật lại hiển thị cho tổng tiền và danh sách ghế đã chọn
    selectedSeatsDisplay.textContent = selectedSeats.join(', ');
    hiddenSelectedSeats.value = selectedSeats.join(',');
    hiddenSeatIds.value = selectedSeatIds.join(','); 
    totalPriceElement.textContent = totalPrice.toLocaleString() + ' Vnđ';
    hiddenTotalPrice.value = totalPrice;

    seats.forEach(seat => {
        seat.addEventListener('click', async () => {
            const seatId = seat.getAttribute('data-seat-id');
            const seatLabel = seat.querySelector('.seat-label').textContent;
            const seatPrice = parseInt(seat.getAttribute('data-seat-price'));

            if (!seat.classList.contains('hold') && !seat.classList.contains('sold')) {
                if (seat.classList.contains('selected')) {
                    // Bỏ chọn ghế
                    selectedSeats = selectedSeats.filter(s => s !== seatLabel);
                    selectedSeatIds = selectedSeatIds.filter(id => id !== seatId);
                    totalPrice -= seatPrice;

                    seat.classList.remove('selected');
                    seat.classList.add('available');

                    try {
                        await axios.post('/release-seats', {
                            seat_ids: [seatId],
                            showtime_id: showtimeId
                        }).then(response => {
                            console.log(response.data.message);
                        }).catch(error => {
                            console.error(error.response.data.message);
                        });
                    } catch (error) {
                        console.error('Error releasing seat:', error);
                        seat.classList.remove('available');
                        seat.classList.add('selected');
                    }
                } else {
                    if (selectedSeats.length < 8) {
                        selectedSeats.push(seatLabel);
                        selectedSeatIds.push(seatId);
                        totalPrice += seatPrice;

                        seat.classList.add('selected');
                        seat.classList.remove('available');

                        try {
                            await axios.post('/hold-seats', {
                                seat_ids: [seatId],
                                showtime_id: showtimeId
                            }).then(response => {
                                console.log(response.data.message);
                            }).catch(error => {
                                console.error(error.response.data.message);
                            });
                        } catch (error) {
                            console.error('Error holding seat:', error);
                            seat.classList.remove('selected');
                            seat.classList.add('available');
                        }
                    } else {
                        alert('Bạn chỉ được chọn tối đa 8 ghế!');
                    }
                }

                // Cập nhật lại hiển thị cho tổng tiền và danh sách ghế đã chọn
                selectedSeatsDisplay.textContent = selectedSeats.join(', ');
                hiddenSelectedSeats.value = selectedSeats.join(',');
                hiddenSeatIds.value = selectedSeatIds.join(','); 
                totalPriceElement.textContent = totalPrice.toLocaleString() + ' Vnđ';
                hiddenTotalPrice.value = totalPrice;
            } else {
                if (seat.classList.contains('hold')) {
                    alert('Ghế này đã được giữ!');
                }
                if (seat.classList.contains('sold')) {
                    alert('Ghế này đã được bán!');
                }
            }
        });
    });

    window.Echo.channel(`showtime.${showtimeId}`)
        .listen('SeatHold', (e) => {
            const seatElement = document.getElementById(`seat-${e.seatId}`);
            if (seatElement && !seatElement.classList.contains('selected')) {
                seatElement.classList.add('hold');
                seatElement.classList.remove('available');
            }
        })
        .listen('SeatRelease', (e) => {
            const seatElement = document.getElementById(`seat-${e.seatId}`);
            if (seatElement) {
                if (seatElement.classList.contains('selected')) {
                    const seatLabel = seatElement.querySelector('.seat-label').textContent;
                    const seatId = seatElement.getAttribute('data-seat-id');
                    const seatPrice = parseInt(seatElement.getAttribute('data-seat-price'));

                    selectedSeats = selectedSeats.filter(s => s !== seatLabel);
                    selectedSeatIds = selectedSeatIds.filter(id => id !== seatId);
                    totalPrice -= seatPrice;

                    selectedSeatsDisplay.textContent = selectedSeats.join(', ');
                    hiddenSelectedSeats.value = selectedSeats.join(',');
                    hiddenSeatIds.value = selectedSeatIds.join(','); 
                    totalPriceElement.textContent = totalPrice.toLocaleString() + ' Vnđ';
                    hiddenTotalPrice.value = totalPrice;

                    seatElement.classList.remove('selected');
                }

                seatElement.classList.remove('hold');
                seatElement.classList.add('available');
            }
        })
        .listen('SeatSold', (e) => {
            const seatElement = document.getElementById(`seat-${e.seatId}`);
            if (seatElement && !seatElement.classList.contains('selected')) {
                seatElement.classList.add('sold');
                seatElement.classList.remove('hold');
            }
        });

    // Hàm kiểm tra xem có ghế trống nằm giữa hai ghế được chọn không (cho ghế sole)
    function checkSoleSeats() {
        const rows = document.querySelectorAll('.table-seat tr');
        let soleSeatsMessage = '';
        let isSoleSeatIssue = false;

        rows.forEach(row => {
            const seatsInRow = Array.from(row.querySelectorAll('.seat'));
            let selectedIndexes = [];

            seatsInRow.forEach((seat, index) => {
                if (seat.classList.contains('selected')) {
                    selectedIndexes.push(index);
                }
            });

            for (let i = 0; i < selectedIndexes.length - 1; i++) {
                const gap = selectedIndexes[i + 1] - selectedIndexes[i];
                if (gap === 2) {
                    isSoleSeatIssue = true;
                    const emptySeatIndex = selectedIndexes[i] + 1;
                    soleSeatsMessage += seatsInRow[emptySeatIndex].querySelector('.seat-label').textContent + ' ';
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

                if (!firstSeat.classList.contains('selected') && secondSeat.classList.contains('selected')) {
                    isEdgeSeatIssue = true;
                    edgeSeatsMessage += firstSeat.querySelector('.seat-label').textContent + ' ';
                }
                if (!lastSeat.classList.contains('selected') && beforeLastSeat.classList.contains('selected')) {
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
        const { isEdgeSeatIssue, edgeSeatsMessage } = checkAdjacentEdgeSeats();
        const { isSoleSeatIssue, soleSeatsMessage } = checkSoleSeats();

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
});
