@extends('client.layouts.master')

@section('title')
    Lịch chiếu
@endsection

@section('content')
    <div class="st_slider_index_sidebar_main_wrapper st_slider_index_sidebar_main_wrapper_md">
        <div class="container">
            <!-- Nội dung chi tiết lịch chiếu -->
            <div class="">
                <!-- Tabs hiển thị các ngày trong tuần -->
                <ul class="nav nav-tabs " id="date-tabs" style="margin-bottom: 30px">
                    <li>
                        <a href="#"><span class="font-38 font-s-35">26</span>/09 - T5</a>
                    </li>

                    <li>
                        <a href="#"><span class="font-38 font-s-35">27</span>/09 - T6</a>
                    </li>

                    <li>
                        <a href="#"><span class="font-38 font-s-35">28</span>/09 - T7</a>
                    </li>

                    <li>
                        <a href="#"><span class="font-38 font-s-35">29</span>/09 - CN</a>
                    </li>

                    <li>
                        <a href="#"><span class="font-38 font-s-35">30</span>/09 - T2</a>
                    </li>

                    <li>
                        <a href="#"><span class="font-38 font-s-35">01</span>/10 - T5</a>
                    </li>
                    <li>
                        <a href="#"><span class="font-38 font-s-35">02</span>/10 - T6</a>
                    </li>
                </ul>
                <div class="row">
                    <div class="col-xl-8 col-lg-12 col-md-12 col-sm-12 col-8">
                        <div class="col-md-4 image-movie-detail">
                            <img src="https://files.betacorp.vn/media%2fimages%2f2024%2f09%2f19%2f482wx722h%2D162630%2D190924%2D83.jpg"
                                class="movie-poster">
                        </div>
                        <div class="movie-detail-content">
                            <h1 class="movie-title">Cám</h1>
                            <ul class="movie-info">
                                <li><strong>Thể loại:</strong> Kinh dị</li>
                                <li><strong>Thời lượng:</strong> 122 phút</li>
                            </ul>
                            <!-- Lịch chiếu phim -->
                            <div class="showtime-section">
                                <h4 class="showtime-title">2D phụ đề</h4>
                                <div class="showtime-list">
                                    <button class="showtime-btn">09:30</button>
                                    <button class="showtime-btn">11:45</button>
                                    <button class="showtime-btn">12:45</button>
                                    <button class="showtime-btn">14:00</button>
                                    <button class="showtime-btn">16:15</button>
                                    <button class="showtime-btn">18:45</button>
                                    <button class="showtime-btn">20:00</button>
                                    <button class="showtime-btn">22:15</button>
                                    <button class="showtime-btn">23:10</button>
                                </div>
                            </div>
                            <div class="showtime-section">
                                <h2 class="showtime-title">2D phụ đề</h2>
                                <div class="showtime-list">
                                    <button class="showtime-btn">09:30</button>
                                    <button class="showtime-btn">11:45</button>
                                    <button class="showtime-btn">12:45</button>
                                    <button class="showtime-btn">14:00</button>
                                    <button class="showtime-btn">16:15</button>
                                    <button class="showtime-btn">18:45</button>
                                    <button class="showtime-btn">20:00</button>
                                    <button class="showtime-btn">22:15</button>
                                    <button class="showtime-btn">23:10</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <hr style="margin: 30px;">
                <div class="row">
                    <div class="col-xl-8 col-lg-12 col-md-12 col-sm-12 col-8">
                        <div class="col-md-4 image-movie-detail">
                            <img src="https://files.betacorp.vn/media%2fimages%2f2024%2f08%2f27%2f400x633%2D13%2D093512%2D270824%2D67.jpg"
                                class="movie-poster">
                        </div>
                        <div class="movie-detail-content">
                            <h1 class="movie-title">Cám</h1>
                            <ul class="movie-info">
                                <li><strong>Thể loại:</strong> Kinh dị</li>
                                <li><strong>Thời lượng:</strong> 122 phút</li>
                            </ul>
                            <!-- Lịch chiếu phim -->
                            <div class="showtime-section">
                                <h4 class="showtime-title">2D phụ đề</h4>
                                <div class="showtime-list">
                                    <button class="showtime-btn">09:30</button>
                                    <button class="showtime-btn">11:45</button>
                                    <button class="showtime-btn">12:45</button>
                                    <button class="showtime-btn">14:00</button>
                                    <button class="showtime-btn">16:15</button>
                                    <button class="showtime-btn">18:45</button>
                                    <button class="showtime-btn">20:00</button>
                                    <button class="showtime-btn">22:15</button>
                                    <button class="showtime-btn">23:10</button>
                                </div>
                            </div>
                            <div class="showtime-section">
                                <h2 class="showtime-title">2D phụ đề</h2>
                                <div class="showtime-list">
                                    <button class="showtime-btn">09:30</button>
                                    <button class="showtime-btn">11:45</button>
                                    <button class="showtime-btn">12:45</button>
                                    <button class="showtime-btn">14:00</button>
                                    <button class="showtime-btn">16:15</button>
                                    <button class="showtime-btn">18:45</button>
                                    <button class="showtime-btn">20:00</button>
                                    <button class="showtime-btn">22:15</button>
                                    <button class="showtime-btn">23:10</button>
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
    <style>
        /* Style cho các tab ngày */
        .nav-tabs li a {
            padding: 20px 38px;
            border: none;
            font-size: 18px;
            cursor: pointer;
            position: relative;
            color: black;
            text-decoration: none;
            font-weight: bold;
        }

        .nav-tabs li a.selected {
            color: red;
            border-bottom: 2px solid red;
        }

        /* .nav-tabs li a.selected::after {
                content: '';
                position: absolute;
                left: 0;
                height: 2px;
                width: 100%;
            } */

        /* Style cho phần chi tiết phim */
        .image-movie-detail {
            text-align: center;
            width: 360px;
        }

        .movie-poster {
            width: 100%;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .movie-title {
            font-size: 30px;
            font-weight: bold;
            margin-top: 10px;
        }

        .movie-description {
            margin-top: 15px;
            font-size: 16px;
            color: #333;
        }

        .movie-info {
            list-style: none;
            padding: 0;
            margin-top: 15px;
        }

        .movie-info li {
            font-size: 14px;
            margin-bottom: 5px;
        }

        /* Style cho phần lịch chiếu */
        .showtime-section {
            margin-top: 30px;
        }

        .showtime-title {
            font-size: 19px;
            font-weight: bold;
            margin-bottom: 15px;
        }

        .showtime-list {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .showtime-btn {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            background-color: #f1f1f1;
            position: relative;
            font-weight: 600;
        }

        .showtime-btn:hover {
            color: red;
        }

        /* Khi button được chọn */
        .showtime-btn.selected {
            color: red;
            /* border-bottom: 2px solid red; */
        }

        /* Thêm hiệu ứng gạch chân */
        /* .showtime-btn.selected::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: 0;
            height: 2px;
            width: 100%;
            background-color: red;
        } */
    </style>
@endsection

@section('scripts')
    <script>
        // Xử lý cho các nút lịch chiếu (showtime)
        document.querySelectorAll('.showtime-btn').forEach(button => {
            button.addEventListener('click', function() {
                document.querySelectorAll('.showtime-btn').forEach(btn => btn.classList.remove('selected'));
                this.classList.add('selected');
            });
        });

        // Xử lý cho các tab ngày
        document.querySelectorAll('#date-tabs li a').forEach(tab => {
            tab.addEventListener('click', function(event) {
                event.preventDefault(); // Ngăn việc chuyển hướng
                document.querySelectorAll('#date-tabs li a').forEach(link => link.classList.remove(
                    'selected'));
                this.classList.add('selected');
            });
        });
    </script>
@endsection
