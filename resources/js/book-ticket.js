import './bootstrap';
Echo.channel('showtime.' + showtimeId)
    .listen('ChangeSeat', (event) => {
        console.log(event);
        const seatElement = document.getElementById(`box-${event.seat.seat_id}`);
        seatElement.classList.remove('seat-hold', 'seat-selected');
        if (event.seat.status === 'hold') {
            seatElement.classList.add('seat-hold');
        }
    });
