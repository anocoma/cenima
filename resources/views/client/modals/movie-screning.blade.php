<div id="modalMovieScrening" class="modalMovieScrening">
    <div class="modalMovieScrening-content">

        <!-- Modal Header -->
        <div class="modalMovieScrening-header">
            <span class="modalMovieScrening-title" id="modalMovieTitle">LỊCH CHIẾU</span>
            <span class="closeModalMovieScrening">&times;</span>
        </div>
        <div id='cinema-title'>
            @php
                $cinema = App\Models\Cinema::findOrFail(session('cinema_id'));
            @endphp
            <h2 class="cinema-title">Rạp Poly {{ $cinema->name }}</h2>
        </div>
        <div class="modalMovieScrening-body">

            <div class="listMovieScrening-date" id="date-showtimes">

            </div>
        </div>
    </div>
</div>



