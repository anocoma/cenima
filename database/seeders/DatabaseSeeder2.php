<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Branch;
use App\Models\Combo;
use App\Models\Voucher;
use App\Models\ComboFood;
use App\Models\Food;
use App\Models\Membership;
use App\Models\Movie;
use App\Models\SeatTemplate;
use App\Models\Slideshow;
use App\Models\TypeRoom;
use App\Models\User;
use App\Models\Post;
use App\Models\Contact;
use App\Models\Rank;
use App\Models\Room;
use App\Models\Showtime;
use App\Models\Ticket;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\SiteSetting;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $now = now();

        // 3 bản ghi slideshow
        Slideshow::insert([
            [
                'img_thumbnail' => json_encode([
                    'https://www.webstrot.com/html/moviepro/html/images/header/01.jpg',
                    'https://www.webstrot.com/html/moviepro/html/images/header/02.jpg',
                    'https://www.webstrot.com/html/moviepro/html/images/header/03.jpg'
                ]),
                'is_active' => 1,
            ]
        ]);



        //20 bản ghi movie và 40 bản ghi movie_version
        $url_youtubes = [
            'VmJ4oB3Xguo',
            'XuX2HKeMkVw',
            'SGg9DxLFCtc',
            'm6MF1MqsDhc',
            'dNwuFYhwTAk',
            '4oxoPMxBO6s',
            'b1Yqng0uSWM',
            'IK-eb2AbKQ',
            'Tx5JuN-5n8U',
            'kMjlJkmt5nk',
            'gTo9JwsmjT4',
            '4rgYUipGJNo'
        ];
        $booleans = [
            true,
            false,
            false,
            false,
            false,
            false,
            false,
            false,
        ];

        $ratings = ['P',  'T13', 'T16', 'T18', 'K'];
        $categories = [
            "Hành động, kịch tính",
            "Phiêu lưu, khám phá",
            "Kinh dị",
            "Khoa học viễn tưởng",
            "Tình cảm",
            "Hài hước",
            "Kịch, Hồi Hộp",
            "Hoạt hình",
            "Tâm lý",
            "Âm nhạc, phiêu lưu",
        ];
        $movieNames =  [
            "Moana 2: Hành Trình Của Moana",
            "Thợ Săn Thủ Lĩnh",
            "Nhím Sonic III",
            "Spring Garden: Ai Oán Trong Vườn Xuân",
            "Tee Yod: Quỷ Ăn Tạng II",
            "Vùng Đất Bị Nguyền Rủa",
            "Gladiator: Võ Sĩ Giác Đấu II",
            "Elli và Bí Ẩn Chiếc Tàu Ma",
            "Sắc Màu Của Hạnh Phúc",
            "OZI: Phi Vụ Rừng Xanh",
            "Tee Yod: Quỷ Ăn Tạng",
            "Venom: Kèo Cuối",
            "Ngày Xưa Có Một Chuyện Tình",
            "Cười Xuyên Biên Giới",
            "Thiên Đường Quả Báo",
            "Cu Li Không Bao Giờ Khóc",
            "RED ONE: Mật mã đỏ",
            "Vây Hãm Tại Đài Bắc",
            'Học Viện Anh Hùng',
            "Linh Miêu",
            'Công Tử Bạc Liêu',
            "CAPTAIN AMERICA: BRAVE NEW WORLD",
            "Địa Đạo: Mặt Trời Trong Bóng Tối",
            "Thám Tử Kiên: Kỳ Án Không Đầu",
            'Mufasa: Vua Sư Tử'
        ];


        for ($i = 0; $i < 25; $i++) {
            $releaseDate = fake()->dateTimeBetween(now()->subMonths(5), now()->addMonths(2));

            $endDate = fake()->dateTimeBetween($releaseDate, now()->addMonths(5));
            $rating = $ratings[array_rand($ratings)];
            $x = ($i % 21) + 1;

            $img = "images/movies/" . $x . ".png";
            $movie = DB::table('movies')->insertGetId([
                'name' => $movieNames[$i],
                'slug' => Str::slug($movieNames[$i]),
                'category' =>  $categories[array_rand($categories)],
                'img_thumbnail' => asset($img),
                'description' => Str::limit(fake()->paragraph, 250),
                'director' => fake()->name,
                'cast' => fake()->name(),
                'rating' => $rating,
                'duration' => fake()->numberBetween(60, 180),
                'release_date' => $releaseDate,
                'end_date' => $endDate,
                'trailer_url' => $url_youtubes[array_rand($url_youtubes)],
                'is_active' => true,
                'is_hot' => fake()->boolean(30),
                'is_special' => false,
                'is_publish' => true,
                'surcharge' => [0, 10000, 20000][array_rand([0, 10000, 20000])],

            ]);
            DB::table('movie_versions')->insert([
                'movie_id' => $movie,
                'name' => 'Phụ Đề'
            ]);
            if (rand(1, 100) <= 50) { // 50% tỷ lệ
                DB::table('movie_versions')->insert([
                    'movie_id' => $movie,
                    'name' => 'Lồng Tiếng'
                ]);
            }
            if (rand(1, 100) <= 5) { // 5% tỷ lệ
                DB::table('movie_versions')->insert([
                    'movie_id' => $movie,
                    'name' => 'Thuyết Minh'
                ]);
            }
        }

        //4 bản ghi chi nhánh
        $branches = [
            'Hà nội',
            'Hồ Chí Minh',
            'Đà Nẵng',
            'Hải Phòng'
        ];
        foreach ($branches as $branch) {
            Branch::create([
                'name' => $branch,
                'slug' => Str::slug($branch)
            ]);
        }

        //8 bản ghi rạp tương ứng với mỗi chi nhánh 2 rạp
        $cinemas = [
            'Hà Đông',
            'Mỹ Đình',
            'Sài Gòn',
            'Gò Vấp',
            'Hải Châu',
            'Cẩm  Lệ',
            'Đồ Sơn',
            'Lương Khê'
        ];
        $branchId = 1;
        $counter = 0;
        foreach ($cinemas as $cinema) {
            DB::table('cinemas')->insert([
                'branch_id' => $branchId,
                'name' => $cinema,
                'surcharge' => [10000, 20000][array_rand([10000, 20000])],
                'slug' => Str::slug($cinema),
                'address' => $cinema . ', ' . fake()->address(),
            ]);
            $counter++;

            if ($counter % 2 == 0) {
                $branchId++;
            }
        }

        //3 bản ghi loại phòng
        $typeRooms = [
            ['name' => '2D', 'surcharge' => 0],
            ['name' => '3D', 'surcharge' => 30000],
            ['name' => 'IMAX', 'surcharge' => 50000]
        ];
        DB::table('type_rooms')->insert($typeRooms);
        $typeSeats = [
            ['name' => 'Ghế Thường', 'price' => 50000],
            ['name' => 'Ghế Vip', 'price' => 75000],
            ['name' => 'Ghế Đôi', 'price' => 120000],
        ];
        DB::table('type_seats')->insert($typeSeats);



        // Tạo template ghế
        SeatTemplate::create([
            'name' => 'Template Standard',
            'description' => 'Mẫu sơ đồ ghế tiêu chuẩn.',
            'matrix_id' => 1, // ID matrix ví dụ
            'seat_structure' => $this->generateSeatStructure1(), // Cấu trúc ghế
            'is_publish' => 1, // Đã publish
            'is_active' => 1, // Đã kích hoạt
        ]);
        SeatTemplate::create([
            'name' => 'Template Large',
            'description' => 'Mẫu sơ đồ ghế lớn.',
            'matrix_id' => 3, // ID matrix ví dụ
            'seat_structure' => $this->generateSeatStructure2(), // Cấu trúc ghế
            'is_publish' => 1, // Đã publish
            'is_active' => 1, // Đã kích hoạt
        ]);
        function randomSeatTemplateId()
        {
            // Tạo một số ngẫu nhiên từ 1 đến 100
            $randomNumber = rand(1, 100);

            // Xác suất 80% cho '1' và 20% cho '2'
            return ($randomNumber <= 80) ? 1 : 2;
        }


        // =========Đây là tạo phòng chiếu =================
        for ($i = 0; $i < count($cinemas); $i++) {
            $branch_id = DB::table('cinemas')->where('id', $i + 1)->value('branch_id');
            // Tạo phòng cho mỗi rạp
            for ($r = 0; $r < 2; $r++) {
                $roomId = DB::table('rooms')->insertGetId([
                    'branch_id' => $branch_id,
                    'cinema_id' => $i + 1,
                    'type_room_id' => fake()->numberBetween(1, 3), // Loại phòng ngẫu nhiên
                    'name' => 'P20' . $r + 1, // Tên phòng
                    'seat_template_id' => randomSeatTemplateId(), //loai mau so do ghe
                    'is_active' => 1,
                    'is_publish' => 1,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);

                $seatTemplateId = DB::table('rooms')->where('id', $roomId)->value('seat_template_id');
                $seatTemplate = SeatTemplate::find($seatTemplateId);
                $seatStructure = json_decode($seatTemplate->seat_structure,true);

                $dataSeats = [];

                //Tạo ghế trong phòng chiếu
                foreach ($seatStructure as $seat) {
                    $name = $seat['coordinates_y'] . $seat['coordinates_x'];

                    // Nếu là ghế đôi thì thêm tên ghế thứ hai
                    if ($seat['type_seat_id'] == 3) {
                        $name .= ' ' . $seat['coordinates_y'] . ($seat['coordinates_x'] + 1);
                    }

                    $dataSeats[] = [
                        'coordinates_x' => $seat['coordinates_x'],
                        'coordinates_y' => $seat['coordinates_y'],
                        'name' => $name,
                        'type_seat_id' => $seat['type_seat_id'],
                        'room_id' => $roomId,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }

                // Chèn tất cả ghế vào bảng seats
                DB::table('seats')->insert($dataSeats);
            }
        }

        //=========Đây là tạo lịch chiếu =================

        $roomCount = [1, 2, 3, 4];
        foreach ($roomCount as $room_id) {
            for ($i = 0; $i < 10; $i++) { // Tạo 10 lịch chiếu cho mỗi phòng
                // Giả lập start_time
                $start_time = fake()->dateTimeBetween('now', '+1 week');

                // Lấy movie_version_id ngẫu nhiên và truy vấn lấy duration của phim, movie_id
                $movie_version_id = fake()->numberBetween(1, 40);
                $movie = DB::table('movies')
                    ->join('movie_versions', 'movies.id', '=', 'movie_versions.movie_id')
                    ->where('movie_versions.id', $movie_version_id)
                    ->select('movies.id as movie_id', 'movies.duration')
                    ->first();

                // Lấy cinema_id từ room
                $cinema = DB::table('rooms')
                    ->where('id', $room_id)
                    ->select('cinema_id')
                    ->first();

                // Lấy type_room dựa trên room_id
                $type_room = DB::table('type_rooms')
                    ->join('rooms', 'type_rooms.id', '=', 'rooms.type_room_id')
                    ->where('rooms.id', $room_id)
                    ->select('type_rooms.name')
                    ->first();

                // Lấy thông tin movie_version
                $movie_version = DB::table('movie_versions')
                    ->where('id', $movie_version_id)
                    ->select('name')
                    ->first();

                // Tạo format kết hợp giữa type_room và movie_version
                $format = $type_room->name . ' ' . $movie_version->name;

                if ($movie && $cinema) {
                    $duration = $movie->duration; // Thời lượng phim (phút)
                    $end_time = (clone $start_time)->modify("+{$duration} minutes")->modify('+15 minutes'); // Cộng thêm thời lượng phim và 15 phút vệ sinh

                    // Kiểm tra trùng thời gian với các suất chiếu khác trong cùng phòng
                    $existingShowtime = DB::table('showtimes')
                        ->where('room_id', $room_id)
                        ->where(function ($query) use ($start_time, $end_time) {
                            // Kiểm tra xem start_time hoặc end_time có nằm trong khoảng thời gian của suất chiếu nào không
                            $query->whereBetween('start_time', [$start_time->format('Y-m-d H:i'), $end_time->format('Y-m-d H:i')])
                                ->orWhereBetween('end_time', [$start_time->format('Y-m-d H:i'), $end_time->format('Y-m-d H:i')])
                                ->orWhere(function ($query) use ($start_time, $end_time) {
                                    // Kiểm tra nếu suất chiếu khác bao trùm toàn bộ khoảng thời gian
                                    $query->where('start_time', '<=', $start_time->format('Y-m-d H:i'))
                                        ->where('end_time', '>=', $end_time->format('Y-m-d H:i'));
                                });
                        })
                        ->exists();

                    if (!$existingShowtime) {
                        // Không có suất chiếu trùng, thêm mới suất chiếu
                        DB::table('showtimes')->insert([
                            'cinema_id' => $cinema->cinema_id,  // Lưu cinema_id
                            'room_id' => $room_id,
                            'slug' => Showtime::generateCustomRandomString(),
                            'format' => $format, // Format kết hợp type_room và movie_version
                            'movie_version_id' => $movie_version_id,
                            'movie_id' => $movie->movie_id,
                            'date' => $start_time->format('Y-m-d'),
                            'start_time' => $start_time->format('Y-m-d H:i'),
                            'end_time' => $end_time->format('Y-m-d H:i'),
                            'is_active' => 1,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    } else {
                        // Nếu có trùng thời gian, bỏ qua và tiếp tục vòng lặp
                        continue;
                    }
                }
            }
        }


        //3 bản ghi loại ghế


        // Lấy số lượng rạp và phòng đã có
        $roomCount = DB::table('rooms')->count();

        // Tạo dữ liệu cho bảng seats
        // for ($room_id = 1; $room_id <= $roomCount; $room_id++) {
        //     for ($y = 'A'; $y <= 'J'; $y++) { // Tạo 10 cột ghế (trục y)
        //         for ($x = 1; $x <= 10; $x++) { // Tạo 10 hàng ghế (trục x)
        //             // for ($y = 'A'; $y <= 'J'; $y++) { // Tạo 10 cột ghế (trục y)

        //             // Xác định loại ghế dựa trên hàng (y)
        //             if (in_array($y, ['A', 'B', 'C', 'D', 'E'])) {
        //                 $type_seat_id = 1; // Ghế thường
        //             } else {
        //                 $type_seat_id = 2; // Ghế VIP
        //             }

        //             DB::table('seats')->insert([
        //                 'room_id' => $room_id,
        //                 'type_seat_id' => $type_seat_id,
        //                 'coordinates_x' => $x,
        //                 'coordinates_y' => $y,
        //                 'name' => $y . $x,
        //                 'is_active' => 1,
        //                 'created_at' => now(),
        //                 'updated_at' => now(),
        //             ]);
        //         }
        //     }
        // }

        // Lấy số lượng ghế và suất chiếu
        // $seatCount = DB::table('seats')->count();
        // $showtimeCount = DB::table('showtimes')->count();

        // for ($showtime_id = 1; $showtime_id <= $showtimeCount; $showtime_id++) {
        //     for ($seat_id = 1; $seat_id <= $seatCount; $seat_id++) {

        //         // Lấy thông tin ghế (type_seat_id và giá)
        //         $seat = DB::table('seats')
        //             ->join('type_seats', 'seats.type_seat_id', '=', 'type_seats.id')
        //             ->where('seats.id', $seat_id)
        //             ->select('type_seats.price as seat_price')
        //             ->first();

        //         // Lấy thông tin phòng (type_room_id và giá)
        //         $room = DB::table('rooms')
        //             ->join('type_rooms', 'rooms.type_room_id', '=', 'type_rooms.id')
        //             ->where('rooms.id', $room_id)
        //             ->select('type_rooms.surcharge as room_surcharge')
        //             ->first();

        //         // Lấy thông tin phim từ suất chiếu (movie_id và giá)
        //         $showtime = DB::table('showtimes')
        //             ->join('movies', 'showtimes.movie_id', '=', 'movies.id')
        //             ->where('showtimes.id', $showtime_id)
        //             ->select('movies.price as movie_price')
        //             ->first();

        //         // Lấy giá rạp
        //         $cinema = DB::table('showtimes')
        //             ->join('cinemas', 'showtimes.cinema_id', '=', 'cinemas.id')
        //             ->where('showtimes.id', $showtime_id)
        //             ->select('cinemas.price as cinema_price')
        //             ->first();

        //         // Tính tổng giá
        //         $totalPrice = $seat->seat_price + $room->room_surcharge + $showtime->movie_price + $cinema->cinema_price;

        //         // Thêm vào bảng seat_showtimes
        //         DB::table('seat_showtimes')->insert([
        //             'seat_id' => $seat_id,
        //             'showtime_id' => $showtime_id,
        //             'status' => 'available',
        //             'price' => $totalPrice,  // Giá tổng được tính ở trên
        //             'created_at' => now(),
        //             'updated_at' => now(),
        //         ]);
        //     }
        // }
        // $seatCount = DB::table('seats')->count();
        // $showtimeCount = DB::table('showtimes')->count();

        // for ($showtime_id = 1; $showtime_id <= $showtimeCount; $showtime_id++) {
        //     for ($seat_id = 1; $seat_id <= $seatCount; $seat_id++) {

        //         // Lấy thông tin ghế (type_seat_id và giá)
        //         $seat = DB::table('seats')
        //             ->join('type_seats', 'seats.type_seat_id', '=', 'type_seats.id')
        //             ->where('seats.id', $seat_id)
        //             ->select('type_seats.price as seat_price', 'seats.room_id') // Lấy thêm room_id
        //             ->first();

        //         if (!$seat) {
        //             Log::warning("Seat not found for seat_id: $seat_id");
        //             continue;  // Nếu không tìm thấy ghế, bỏ qua
        //         }

        //         // Sử dụng $seat->room_id để lấy thông tin phòng
        //         $room = DB::table('rooms')
        //             ->join('type_rooms', 'rooms.type_room_id', '=', 'type_rooms.id')
        //             ->where('rooms.id', $seat->room_id) // Sử dụng room_id từ ghế
        //             ->select('type_rooms.surcharge as room_surcharge')
        //             ->first();

        //         // Lấy thông tin phim từ suất chiếu (movie_id và giá)
        //         $showtime = DB::table('showtimes')
        //             ->join('movies', 'showtimes.movie_id', '=', 'movies.id')
        //             ->where('showtimes.id', $showtime_id)
        //             ->select('movies.surcharge as movie_surcharge')
        //             ->first();

        //         // Lấy giá rạp
        //         $cinema = DB::table('showtimes')
        //             ->join('cinemas', 'showtimes.cinema_id', '=', 'cinemas.id')
        //             ->where('showtimes.id', $showtime_id)
        //             ->select('cinemas.surcharge as cinema_surcharge')
        //             ->first();

        //         // Kiểm tra nếu bất kỳ giá trị nào là null
        //         if ($seat && $room && $showtime && $cinema) {
        //             // Tính tổng giá
        //             $totalPrice = $seat->seat_price + $room->room_surcharge + $showtime->movie_surcharge + $cinema->cinema_surcharge;

        //             // Thêm vào bảng seat_showtimes
        //             DB::table('seat_showtimes')->insert([
        //                 'seat_id' => $seat_id,
        //                 'showtime_id' => $showtime_id,
        //                 'status' => 'available',
        //                 'price' => $totalPrice,  // Giá tổng được tính ở trên
        //                 'created_at' => now(),
        //                 'updated_at' => now(),
        //             ]);
        //         } else {
        //             // Xử lý trường hợp không tìm thấy giá trị
        //             Log::warning("Missing data for seat_id: $seat_id, showtime_id: $showtime_id");
        //         }
        //     }
        // }

        $showtimes = DB::table('showtimes')
            ->join('movies', 'showtimes.movie_id', '=', 'movies.id')
            ->join('cinemas', 'showtimes.cinema_id', '=', 'cinemas.id')
            ->join('rooms', 'showtimes.room_id', '=', 'rooms.id')
            ->join('type_rooms', 'rooms.type_room_id', '=', 'type_rooms.id')
            ->select(
                'showtimes.id as showtime_id',
                'rooms.id as room_id',
                'movies.surcharge as movie_surcharge',
                'cinemas.surcharge as cinema_surcharge',
                'type_rooms.surcharge as room_surcharge'
            )
            ->get();

        // Lấy tất cả ghế và nhóm theo room_id để dễ truy xuất
        $seats = DB::table('seats')
            ->join('type_seats', 'seats.type_seat_id', '=', 'type_seats.id')
            ->select(
                'seats.id as seat_id',
                'seats.room_id',
                'type_seats.price as seat_price'
            )
            ->get()
            ->groupBy('room_id'); // Nhóm ghế theo room_id

        // Duyệt qua từng suất chiếu và thêm ghế của phòng tương ứng
        foreach ($showtimes as $showtime) {
            $roomSeats = $seats->get($showtime->room_id); // Lấy ghế thuộc phòng

            if (!$roomSeats) {
                Log::warning("No seats found for room_id: {$showtime->room_id}");
                continue; // Bỏ qua nếu không có ghế cho phòng này
            }
            foreach ($roomSeats as $seat) {
                // Tính tổng giá cho từng ghế
                $totalPrice = $seat->seat_price
                    + $showtime->room_surcharge
                    + $showtime->movie_surcharge
                    + $showtime->cinema_surcharge;

                // Thêm vào bảng seat_showtimes
                DB::table('seat_showtimes')->insert([
                    'seat_id' => $seat->seat_id,
                    'showtime_id' => $showtime->showtime_id,
                    'status' => 'available',
                    'price' => $totalPrice,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }














        //tạo 5 bản ghỉ user type admin
        $users = [
            [
                'name' => 'System Admin',
                'img_thumbnail' => '',
                'phone' => '0332295555',
                'email_verified_at' => '2024-11-01 19:58:51',
                'email' => 'admin@fpt.edu.vn',
                'password' => Hash::make('admin@fpt.edu.vn'),
                'address' => 'Bích Hòa, Thanh Oai, Hà Nội',
                'gender' => 'Nam',
                'birthday' => '2004-02-07',
                'type' => 'admin',
                'cinema_id' => null,
            ],
            [
                'name' => 'Trương Công Lực',
                'img_thumbnail' => '',
                'phone' => '0332293871',
                'email_verified_at' => '2024-11-01 19:58:51',
                'email' => 'luctcph37171@fpt.edu.vn',
                'password' => Hash::make('luctcph37171@fpt.edu.vn'),
                'address' => 'Bích Hòa, Thanh Oai, Hà Nội',
                'gender' => 'Nữ',
                'birthday' => '2004-02-07',
                'type' => 'member',
                'cinema_id' => null,
            ],
            [
                'name' => 'Hà Đắc Hiếu',
                'img_thumbnail' => '',
                'phone' => '0975098710',
                'email_verified_at' => '2024-11-01 19:58:51',
                'email' => 'hieuhdph36384@fpt.edu.vn',
                'password' => Hash::make('hieuhdph36384@fpt.edu.vn'),
                'address' => 'Núi Trầm, Chương Mỹ, Hà Nội.',
                'gender' => 'Nam',
                'birthday' => '2004-08-08',
                'type' => 'member',
                'cinema_id' => null,
            ],
            [
                'name' => 'Đặng Phú An',
                'img_thumbnail' => '',
                'phone' => '0378633611',
                'email_verified_at' => '2024-11-01 19:58:51',
                'email' => 'andpph31859@fpt.edu.vn',
                'password' => Hash::make('andpph31859@fpt.edu.vn'),
                'address' => 'Văn Chấn, Yên Bái.',
                'gender' => 'Nam',
                'birthday' => '2004-06-06',
                'type' => 'member',
                'cinema_id' => null,
            ],
            [
                'name' => 'An Dang Phu',
                'img_thumbnail' => '',
                'phone' => '0378633611',
                'email_verified_at' => '2024-11-01 19:58:51',
                'email' => 'anpx123@gmail.com',
                'password' => Hash::make('anpx123@gmail.com'),
                'address' => 'Văn Chấn, Yên Bái.',
                'gender' => 'Nam',
                'birthday' => '2004-10-01',
                'type' => 'member',
                'cinema_id' => 1,
            ],
            [
                'name' => 'Nguyễn Viết Sơn',
                'img_thumbnail' => '',
                'phone' => '0973657594',
                'email_verified_at' => '2024-11-01 19:58:51',
                'email' => 'sonnvph33874@fpt.edu.vn',
                'password' => Hash::make('sonnvph33874@fpt.edu.vn'),
                'address' => 'Núi Trầm, Chương Mỹ, Hà Nội.',
                'gender' => 'Nam',
                'birthday' => '2004-11-11',
                'type' => 'member',
                'cinema_id' => null,
            ],
            [
                'name' => 'Bùi Đỗ Đạt',
                'img_thumbnail' => '',
                'phone' => '0965263725',
                'email_verified_at' => '2024-11-01 19:58:51',
                'email' => 'datbdph38211@fpt.edu.vn',
                'password' => Hash::make('datbdph38211@fpt.edu.vn'),
                'address' => 'Bích Hòa, Thanh Oai, Hà Nội',
                'gender' => 'Nam',
                'birthday' => '2004-10-14',
                'type' => 'member',
                'cinema_id' => null,
            ],
            [
                'name' => 'Nhân viên Rạp',
                'img_thumbnail' => '',
                'phone' => '0965266625',
                'email_verified_at' => '2024-11-01 19:58:51',
                'email' => 'nhanvienrapHaDong@fpt.edu.vn',
                'password' => Hash::make('nhanvienrapHaDong@fpt.edu.vn'),
                'address' => 'Bích Hòa, Thanh Oai, Hà Nội',
                'gender' => 'Nam',
                'birthday' => '2004-10-14',
                'type' => 'admin',
                'cinema_id' => 1,
            ],
            [
                'name' => 'Nhân viên Rạp',
                'img_thumbnail' => '',
                'phone' => '0965265555',
                'email_verified_at' => '2024-11-01 19:58:51',
                'email' => 'nhanvienrapMyDinh@fpt.edu.vn',
                'password' => Hash::make('nhanvienrapMyDinh@fpt.edu.vn'),
                'address' => 'Bích Hòa, Thanh Oai, Hà Nội',
                'gender' => 'Nam',
                'birthday' => '2004-10-14',
                'type' => 'admin',
                'cinema_id' => 2,
            ],
            [
                'name' => 'Quản lý cơ sở Hà Đông',
                'img_thumbnail' => '',
                'phone' => '0999965555',
                'email_verified_at' => '2024-11-01 19:58:51',
                'email' => 'quanlycosoHaDong@fpt.edu.vn',
                'password' => Hash::make('quanlycosoHaDong@fpt.edu.vn'),
                'address' => 'Bích Hòa, Chương Mỹ, Hà Nội',
                'gender' => 'Nam',
                'birthday' => '2004-10-14',
                'type' => 'admin',
                'cinema_id' => 1,
            ],
            [
                'name' => 'Quản lý cơ sở Mỹ Đình',
                'img_thumbnail' => '',
                'phone' => '0999999995',
                'email_verified_at' => '2024-11-01 19:58:51',
                'email' => 'quanlycosoMyDinh@fpt.edu.vn',
                'password' => Hash::make('quanlycosoMyDinh@fpt.edu.vn'),
                'address' => 'Bích Hòa, Chương Mỹ, Hà Nội',
                'gender' => 'Nam',
                'birthday' => '2004-10-14',
                'type' => 'admin',
                'cinema_id' => 2,
            ],
        ];

        // Chèn tất cả người dùng vào cơ sở dữ liệu
        User::insert($users);
        $dataRanks = [
            ['name' => 'Member',       'total_spent' => 0,         'ticket_percentage' => 5,     'combo_percentage' => 3,  'is_default' => 1],
            ['name' => 'Gold',         'total_spent' => 1000000,   'ticket_percentage' => 7,     'combo_percentage' => 5,  'is_default' => 0],
            ['name' => 'Platinum',     'total_spent' => 3000000,   'ticket_percentage' => 10,    'combo_percentage' => 7,  'is_default' => 0],
            ['name' => 'Diamond',      'total_spent' => 5000000,   'ticket_percentage' => 15,    'combo_percentage' => 10, 'is_default' => 0],
        ];
        foreach ($dataRanks as $rank) {
            Rank::create($rank);
        }
        // Tạo một bản ghi thành viên cho mỗi người dùng
        foreach ($users as $userData) {
            $user = User::where('email', $userData['email'])->first();
            if ($user) {
                Membership::create([
                    'user_id' => $user->id,
                    'rank_id' => 1,
                    'code' => Membership::codeMembership(),
                ]);
            }
        }



        //3 bảng ghi food
        Food::insert(
            [
                ['name' => 'Nước có gaz (22oz)', 'img_thumbnail' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRzWcnDbyPmBMtua26Cr1cv-970sm56vJkZUw&s', 'price' => 20000, 'type' => 'Nước Uống'],
                ['name' => 'Bắp (69oz)', 'img_thumbnail' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTXVyPxPb8ZuGNwrTDt6Em_2PlVUl-0ibkgeA&s', 'price' => 30000, 'type' => 'Đồ Ăn'],
                ['name' => 'Ly Vảy cá kèm nước', 'img_thumbnail' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSxIj_cKCMmduRPAnphuGPCQFiHQIU3IG4kcg&s', 'price' => 40000, 'type' => 'Khác'],
            ]
        );

        //4 bảng ghi Combos
        Combo::insert([
            ['name' => 'Combo Snack', 'img_thumbnail' => 'https://files.betacorp.vn/media/combopackage/2024/03/31/combo-online-03-163047-310324-49.png', 'description' => 'Combo gồm nước và bắp'],
            ['name' => 'Combo Drink', 'img_thumbnail' => 'https://files.betacorp.vn/media/combopackage/2024/06/05/combo-online-26-101802-050624-36.png', 'description' => 'Combo nước uống đặc biệt'],
            ['name' => 'Combo Mixed', 'img_thumbnail' => 'https://files.betacorp.vn/media/combopackage/2024/03/31/combo-online-04-163144-310324-32.png', 'description' => 'Combo đồ ăn và nước uống'],
            ['name' => 'Combo Premium', 'img_thumbnail' => 'https://files.betacorp.vn/media/combopackage/2024/08/23/combo-see-me-duoi-ca-01-120352-230824-11.png', 'description' => 'Combo cao cấp'],
        ]);

        $combos = Combo::all();
        $foods = Food::all();
        foreach ($combos as $combo) {
            $totalPrice = 0;
            foreach ($foods->random(rand(1, 3)) as $food) {
                $quantity = rand(1, 4);
                $itemPrice = $food->price * $quantity;
                $totalPrice += $itemPrice;

                ComboFood::create([
                    'combo_id' => $combo->id,
                    'food_id' => $food->id,
                    'quantity' => $quantity,
                ]);
            }

            DB::table('combos')
                ->where('id', $combo->id)
                ->update(['price' => $totalPrice, 'price_sale' => $totalPrice - 20000]);
        }
        // $userIds = range(1, 6);
        $dataVouchers = [
            ['title'=>'Chúc mừng giáng sinh Merry Christmas', 'code'=> 'GIANGSINHANLANH', 'description'=> 'Nhân dịp giáng sinh Polycinemas tặng quý khách hàng mã vouchers giảm giá 30.000 VNĐ khi đặt vé tại rạp.','discount'=>30000],
            ['title'=>'Chúc mừng năm mới 2024', 'code'=> 'HPNY2025', 'description'=> 'Đầu xuân năm mới Polycinemas chúc quý khách hàng một năm an khang thịnh vượng !','discount'=>10000]
        ];
        foreach ($dataVouchers as $vc) {
            Voucher::create([
                'code' => $vc['code'],
                'title' => $vc['title'],
                'description' => $vc['description'],
                'start_date_time' => Carbon::now()->subDays(rand(0, 30)),
                'end_date_time' => Carbon::now()->addDays(rand(30, 60)),
                'discount' => $vc['discount'],
                'quantity' => fake()->numberBetween(1, 100),
                'limit' => fake()->numberBetween(1, 5),
                'is_active' => 1,
                'is_publish' => 1,
                'type' => 1,
            ]);
            // foreach ($userIds as $userId) {
            //     DB::table('user_vouchers')->insert([
            //         'user_id' => $userId,
            //         'voucher_id' => $voucherId,
            //         'usage_count' => 0,
            //     ]);
            // }
        }


        // Tạo 10 bài viết
        $posts = [
            // 1
            [
                'title' => 'ĐẶT VÉ BETA CINEMAS, MOMO LIỀN! 🚀',
                'slug' => 'dat-ve-beta-cinemas-momo-lien',
                'img_post' => 'https://files.betacorp.vn//media/images/2024/09/01/545x415-131203-010924-53.jpg',
                'description' => 'Đặt vé tại Beta Cinemas qua MoMo nhanh chóng, tiện lợi và nhận ngay ưu đãi hấp dẫn. Trải nghiệm phim yêu thích dễ dàng chỉ trong vài bước!',
                'content' => '
                    <p><strong>MUA VÉ U22 THANH TOÁN BẰNG MOMO TẠI BETA CINEMAS!</strong></p>
                    <p><strong>Giá vé ưu đãi nay còn có thêm hình thức thanh toán quốc dân MoMo! Ngại gì mà không ra rạp ngay từ hôm nay để trải nghiệm sự tiện lợi này!
                    Danh sách phim lễ đã đầy đủ, lựa phim và ra rạp Beta Cinemas thôi các bạn ơi!</strong></p>
                    <h2><strong>🔥 Ưu đãi với khách hàng có thẻ học sinh sinh viên, trẻ em cao dưới 1,3m và người trên 55 tuổi</strong></h2>
                    <p>🎊 50k cho phim 2D, 70k cho phim 3D: Giải Phóng</p>
                    <p>🎊 45k cho phim 2D, 65k cho phim 3D: Mỹ Đình, Thanh Xuân, Đan Phượng, Tân Uyên, Empire Bình Dương (Thủ Dầu Một)</p>
                    <p>🎊 40K cho phim 2D, 60k cho phim 3D: Bắc Giang, Biên Hòa, Nha Trang, Thanh Hóa, Thái Nguyên</p>
                    <p>🎊 45k (thứ 2-5) & 50k (thứ 6-7-CN) cho phim 2D, 65k (thứ 2-5-) & 70k (thứ 6-7-CN) cho phim 3D: Lào Cai</p>
                    <p>🎊 45k (từ thứ 2-5) & 55k (thứ 6-7-CN) cho phim 2D, 65k (từ thứ 2-5) & 75k (thứ 6-7-CN) cho phim 3D: Quang Trung</p>
                    <p>🎊 40k (thứ 2-4-5-6) & 50k (thứ 7-CN) cho phim 2D, 60k (thứ 2-4-5-6) & 70k (thứ 7-CN) cho phim 3D: Long Khánh</p>
                    <h2><strong>Điều kiện áp dụng:</strong></h2>
                    <p>Xuất trình thẻ HSSV (nếu có) hoặc CMND/CCCD, bằng lái xe dưới 22 tuổi.</p>
                    <p>Mặc đồng phục của trường</p>
                    <p>Chiều cao dưới 1m3</p>
                    <h2><strong>Lưu ý:</strong></h2>
                    <p><strong>Chỉ áp dụng cho khách hàng thành viên của Beta Cinemas.</strong></p>
                    <p>Thẻ học sinh, sinh viên phải còn thời hạn áp dụng.</p>
                    <p>1 thẻ học sinh, sinh viên có thể áp dụng được cho cả nhóm khách hàng đi cùng đối với phim không giới hạn độ tuổi
                    (các phim từ T13 trở lên cần kiểm tra thẻ của từng người).</p>
                    <p>Ưu đãi áp dụng với người lớn tuổi (trên 55t) và phải xuất trình CMND trước khi mua vé.</p>
                    <p>Không áp dụng đồng thời với các chương trình khuyến mãi khác.</p>
                    <p>Chỉ áp dụng cho mua vé tại quầy.</p>
                    <p>Không áp dụng cho mua vé Online.</p>
                    <p>Không áp dụng nếu trùng vào ngày lễ, Tết.</p>
                    <p>Không áp dụng cho các suất chiếu đặc biệt hoặc phim ra mắt sớm.</p>
                    <h3><strong>ĐẶT VÉ BETA CINEMAS, MOMO LIỀN! 🚀</strong></h3>
                    <p><strong>Một chạm có vé:</strong><a href="https://bit.ly/MoMo-Movies"> https://bit.ly/MoMo-Movies </a></p>
                ',
            ],
            // 2
            [
                'title' => 'DÀNH TẶNG 10K CHO CÁC BETA-ER',
                'slug' => 'danh-tang-10k-cho-cac-beta-er',
                'img_post' => 'https://files.betacorp.vn/media/images/2024/12/04/sppxbeta-545x415-155204-041224-61.png',
                'description' => 'Ưu đãi cực hot: Beta Cinemas dành tặng 10K cho tất cả Beta-er! Đừng bỏ lỡ cơ hội nhận ngay quà tặng giá trị này khi đặt vé hôm nay.',
                'content' => '
                    <p><strong>Ưu đãi cực khủng tháng 12.2024 dành cho khách hàng của Beta Cinemas</strong></p>
                    <p>Giảm 10K cho hóa đơn từ 100k! Áp dụng cho khách hàng nhận được mã giảm giá trong Kho Voucher trên Ví ShopeePay</p>
                    <p>- Thời gian áp dụng: 01/12/2024 - 31/12/2024.</p>
                    <p>- Áp dụng khi thực hiện giao dịch tại Website/App Beta Cinemas và thanh toán bằng Ví ShopeePay.</p>
                    <p>- Mỗi khách hàng được hưởng ưu đãi tối đa 1 lần.</p>
                    <p><strong>Đặt vé ngay tại:</strong> <a>https://betacinemas.vn/lich-chieu.htm</a></p>
                ',
            ],
            // 3
            [
                'title' => 'BETA VÉ RẺ, MOMO MUA LIỀN!',
                'slug' => 'beta-ve-re-momo-mua-lien',
                'img_post' => 'https://files.betacorp.vn/media/images/2024/04/16/339090620-769688404468201-6997705945754521027-n-113050-160424-59.jpg',
                'description' => 'Vé xem phim tại Beta Cinemas siêu rẻ, chỉ cần thanh toán qua MoMo! Nhanh tay đặt ngay để không bỏ lỡ những bộ phim bom tấn.',
                'content' => '
                    <p><strong>Beta Cinemas đẹp thôi chưa đủ, mà giá lại còn “vừa túi tiền”. Từ nay, các mọt phim tha hồ thưởng thức những thước phim
                    điện ảnh đỉnh cao tại tất cả cụm rạp Beta trên toàn quốc với giá cực ưu đãi khi đặt vé trên MoMo.</strong></p>
                    <p><strong>- Thứ 2 đến Thứ 6 hàng tuần: 58.000đ/vé 2D</strong></p>
                    <p><strong>- Thứ 7 và Chủ Nhật hàng tuần: 62.000đ/vé 2D</strong></p>
                    <p><strong>Thời gian áp dụng: Từ nay đến khi hết ngân sách khuyến mãi.</strong></p>
                    <p><strong>Điều khoản, điều kiện áp dụng:</strong></p>
                    <p><strong>Chỉ áp dụng khi đặt vé xem phim và thanh toán trực tiếp trên MoMo, không áp dụng chương trình khuyến mãi
                    khi khách hàng đặt và thanh toán tại quầy hoặc qua các hình thức khác.</strong></p>
                    <p><strong>Áp dụng tại tất cả các rạp Beta trên toàn quốc, trừ Beta Phú Mỹ và Beta Hồ Tràm.</strong></p>
                    <p><strong>Chương trình áp dụng cho các suất chiếu 2D trong suốt thời gian diễn ra chương trình.</strong></p>
                    <p><strong>Chương trình cho tất cả các loại ghế: ghế đơn thường, ghế VIP và ghế đôi.</strong></p>
                    <p><strong>Chương trình không bao gồm suất chiếu sớm, lễ tết và các suất chiếu tại các phòng chiếu đặc biệt.</strong></p>
                    <p><strong>Chỉ áp dụng trên giá vé, không áp dụng cho các combo hoặc dịch vụ khác đi kèm.</strong></p>
                    <p><strong>Không áp dụng đồng thời các chương trình khuyến mãi khác của rạp.</strong></p>
                    <p><strong>Chương trình có thể kết thúc sớm hơn dự kiến nếu hết ngân sách. </strong></p>
                    <p><strong>Khiếu nại không được giải quyết nếu khách hàng chấp nhận thanh toán giá vé cuối cùng hiển thị trên màn hình giao dịch.</strong></p>
                    <p><strong>Mua vé Beta 58.000Đ - 62.000Đ với các bước:</strong></p>
                    <p><strong>Bước 1: Truy cập App MoMo, trên giao diện chính chọn “Mua vé xem phim”.</strong></p>
                    <p><strong>Bước 2: Nhấn vào thanh “Tìm kiếm” và gõ chọn rạp “Beta” gần khu vực.</strong></p>
                    <p><strong>Bước 3: Sau khi đã chọn rạp, màn hình chuyển qua các phim và suất chiếu.</strong></p>
                    <p><strong>Ở đây khách hàng sẽ chọn ngày, chọn phim và suất chiếu.</strong></p>
                    <p><strong>Bước 4: Sau khi chọn phim và suất chiếu phù hợp, màn hình chuyển qua giao diện chọn ghế.</strong></p>
                    <p><strong>Bước 5: Chọn thêm Combo bắp nước. Sau đó nhấn “Tiếp tục”.</strong></p>
                    <p><strong>Bước 6: Tổng giao dịch hiển thị trên màn hình. Nhấn “Tiếp tục” để tiến hành thanh toán.</strong></p>
                    <p><strong>Bước 7: Nhấn “Xác nhận” để thanh toán giao dịch.</strong></p>
                    <p><strong>Sau khi thanh toán “Giao dịch thành công”, khách hàng kiểm tra vé đã mua trong “Mua vé xem phim” => “Tôi” => “Vé đã mua”.</strong></p>
                    <p><strong>Hình ảnh Thông tin vé Khách hàng đã mua.</strong></p>
                    <p><strong>👉 ĐẶT VÉ NGAY TẠI:</strong> <a href="https://momo.vn/cinema/rap/beta-cinemas">https://momo.vn/cinema/rap/beta-cinemas</a></p>
                ',
            ],
            // 4
            [
                'title' => 'THÀNH VIÊN BETA - ĐỒNG GIÁ 45K/50K',
                'slug' => 'thanh-vien-beta-dong-gia-45k-50k',
                'img_post' => 'https://files.betacorp.vn//cms/images/2024/04/03/545x415-member-130929-030424-88.jpg',
                'description' => 'Trở thành thành viên Beta Cinemas để tận hưởng ưu đãi đồng giá vé 45K hoặc 50K. Xem phim tiết kiệm mà vẫn đầy đủ trải nghiệm!',
                'content' => '
                    <p>Nhanh tay đặt vé cùng bạn bè đón những suất chiếu sớm giá rẻ nào các bạn !!!</p>
                    <p><strong>Nội Dung Chương Trình: </strong></p>
                    <p>💗 Chỉ 45K/ vé 2D tại Beta Thanh Xuân, Mỹ Đình, Biên Hòa, Thái Nguyên, Nha Trang.</p>
                    <p>💗 Chỉ 50K/ vé 2D tại Beta Giải Phóng.</p>
                    <p><strong>Điều Kiện Áp Dụng: </strong></p>
                    <p><strong>Chỉ áp dụng cho khách hàng thành viên của Beta Cinemas.</strong></p>
                    <p>Chỉ áp dụng từ thứ 2 đến thứ 6 hàng tuần</p>
                    <p>Áp dụng cho cả mua vé tại quầy và mua Online</p>
                    <p>Không áp dụng đồng thời với các chương trình khuyến mại khác</p>
                    <p>Không áp dụng nếu trùng vào ngày lễ, Tết.</p>
                    <p>Không áp dụng cho các suất chiếu đặc biệt hoặc phim ra mắt sớm</p>
                    <p>Phụ thu 5k cho ghế VIP/đôi</p>
                    <p>Xem lịch chiếu và đăt vé tại: <a href="http://onelink.to/zmb6dp">http://onelink.to/zmb6dp</a></p>
                ',
            ],
            // 5
            [
                'title' => 'THỨ BA VUI VẺ',
                'slug' => 'thu-ba-vui-ve',
                'img_post' => 'https://files.betacorp.vn//cms/images/2024/04/03/545x415-t3vv-130807-030424-88.jpg',
                'description' => 'Đừng bỏ lỡ ngày thứ Ba vui vẻ tại Beta Cinemas với giá vé ưu đãi cực rẻ! Lên lịch ngay để cùng bạn bè thưởng thức phim yêu thích.',
                'content' => '
                    <p>
                    <strong>😍😍 Mọi người đừng quên ngày " Thứ 3 vui vẻ" của Beta Cinemas với mức giá chỉ 40 - 45 - 50k với tất cả các phim nhé! 😍😍</strong></p>
                    <p><strong>💸 Đồng giá giá vé phim :</strong></p>
                    <p>💗 Chỉ 40K/vé 2D - 60K/vé 3D tại Beta Bắc Giang, Thanh Hóa, Biên Hòa, Nha Trang, Thái Nguyên.</p>
                    <p>💗 Chỉ 45K/vé 2D - 65K/vé 3D tại Beta Mỹ Đình, Thanh Xuân, Đan Phượng, Tân Uyên, Empire Bình Dương (Thủ Dầu Một), Lào Cai.</p>
                    <p>💗 Chỉ 45K/vé 2D - 65K/vé 3D (học sinh sinh viên, trẻ em, người cao tuổi) & 50K/vé 2D - 70K/vé 3D (người lớn) tại Quang Trung.</p>
                    <p>💗 Chỉ 40K/vé 2D - 60K/vé 3D (học sinh sinh viên, trẻ em, người cao tuổi) & 45K/vé 2D - 65K/vé 3D (người lớn) tại Long Khánh.</p>
                    <p>💗 Chỉ 50K/vé 2D - 70K/vé 3D tại Giải Phóng, Ung Văn Khiêm, Trần Quang Khải.</p>
                    <p>💗 Chỉ 50K/vé (học sinh sinh viên, trẻ em, người cao tuổi, nhân viên) & 60K/vé (người lớn) tại Hồ Tràm, TRMall Phú Quốc. </p>
                    <p><strong>💢 Điều kiện áp dụng:</strong></p>
                    <p><strong>- Chỉ áp dụng cho khách hàng thành viên của Beta Cinemas.</strong></p>
                    <p>- Áp dụng cho cả mua vé tại quầy và mua Online</p>
                    <p>- Không áp dụng đồng thời với các chương trình khuyến mại khác</p>
                    <p>- Không áp dụng nếu trùng vào ngày lễ, Tết.</p>
                    <p>- Không áp dụng cho các suất chiếu đặc biệt hoặc phim ra mắt sớm</p>
                    <p>- KHÔNG PHỤ THU GHẾ VIP/ĐÔI</p>
                    <p>- Phụ thu 10k với khách hàng không có tài khoản thành viên Beta Member (đăng ký nhanh trong 1 nốt nhạc tại
                    <a href="http://onelink.to/zmb6dp">http://onelink.to/zmb6dp<a/>)</p>
                ',
            ],
            // 6
            [
                'title' => 'SALE KHÔNG NGỪNG - MỪNG "MAD SALE DAY"',
                'slug' => 'sale-khong-ngung-mad-sale-day',
                'img_post' => 'https://files.betacorp.vn//cms/images/2024/04/03/545x415-mad-sale-day-131034-030424-25.jpg',
                'description' => 'Bùng nổ ưu đãi cùng ngày "Mad Sale Day" tại Beta Cinemas! Giảm giá không ngừng cho tất cả vé xem phim và combo.',
                'content' => '
                    <p><strong>SALE KHÔNG NGỪNG - MỪNG "MAD SALE DAY"</strong></p>
                    <p><strong>Không thể bỏ lỡ Mad Sale Day - Thứ 2 đầu tiên của tháng - Ngày hội khuyến mãi hấp dẫn nhất tại Beta Cinemas:</strong></p>
                    <p><strong>💢 ĐỪNG BỎ LỠ - MAD SALE DAY VỚI CÁC ƯU ĐÃI SAU💢</strong></p>
                    <p>🎁 Đồng giá 40K/vé đối với 2D  ➕ tặng 1 bắp: Áp dụng tại các cụm rạp Beta Thái Nguyên, Thanh Hóa, Bắc Giang, Nha Trang, Biên Hòa.</p>
                    <p>🎁 Đồng giá 45K/vé đối với 2D  ➕ tặng 1 bắp: Áp dụng tại Beta Thanh Xuân,
                    Mỹ Đình, Đan Phượng, Long Khánh, Tân Uyên, Empire Bình Dương (Thủ Dầu Một), Phú Mỹ, Lào Cai.</p>
                    <p>🎁 Đồng giá 45k/vé (học sinh, sinh viên, trẻ em, người cao tuổi), 50k/vé
                    (người lớn) đối với 2D  ➕ tặng 1 bắp: Áp dụng tại Beta Quang Trung.</p>
                    <p>🎁 Đồng giá 50K/vé đối với 2D  ➕ tặng 1 bắp: Áp dụng tại Beta Giải Phóng, Ung Văn Khiêm, Trần Quang Khải.</p>
                    <p>🎁 Đồng giá 50k/vé (học sinh, sinh viên, trẻ em, người cao tuổi), 60k/vé (người lớn)
                    đối với 2D  ➕ tặng 1 bắp: Áp dụng tại Beta Hồ Tràm, TRMall Phú Quốc.</p>
                    <p>⚠️ LƯU Ý:</p>
                    <p>🔹 Áp dụng cho tất cả khách hàng.</p>
                    <p>🔹 Áp dụng khi mua vé trực tiếp tại quầy và mua online.</p>
                    <p>🔹 Không giới hạn suất chiếu và ghế ngồi.</p>
                    <p>🔹 Áp dụng tại toàn bộ các rạp Beta Cinemas.</p>
                    <p>🔹 Không áp dụng đồng thời với các chương trình khuyến mại khác.</p>
                    <p>🔹 Không áp dụng nếu trùng vào ngày lễ, Tết và các ngày nghỉ bù theo lịch của Nhà nước.</p>
                    <p>🔹 Không phụ thu phim bom tấn, ghế VIP, ghế đôi.</p>
                    <p>🔹 Giá vé giảm Mad Sale Day không áp dụng với các phim có suất chiếu sớm, hoặc giá vé đặc biệt từ nhà phát hành.
                    Với các phim này, vé phim sẽ chỉ được tặng Bắp MIỄN PHÍ</p>
                    <p><strong>"BOM TẤN" ĐÃ NỔ, CÒN BẠN ĐÃ "NỔ VÍ" HAY CHƯA?</strong></p>
                    <p>-----------------------------</p>
                    <p><strong>🤩𝔹𝔼𝕋𝔸 ℂ𝕀ℕ𝔼𝕄𝔸𝕊 - ℝẠℙ ℕ𝔾𝕆ℕ 𝔾𝕀Á ℕ𝔾Ọ𝕋🤩</strong></p>
                    <p><strong>🎈LỊCH CHIẾU VÀ HỆ THỐNG RẠP:</strong> <a href="https://www.betacineplex.vn/">https://www.betacineplex.vn/</a></p>
                    <p><strong>🎈TRUY CẬP APP</strong> <a href="http://onelink.to/zmb6dp">http://onelink.to/zmb6dp</a></p>
                ',
            ],
            // 7
            [
                'title' => 'GIÁ VÉ ƯU ĐÃI CHO HỌC SINH, SINH VIÊN',
                'slug' => 'gia-ve-uu-dai-hoc-sinh-sinh-vien',
                'img_post' => 'https://files.betacorp.vn//cms/images/2024/04/03/545x415-hssv-131204-030424-20.jpg',
                'description' => 'Học sinh, sinh viên nhận ngay ưu đãi giá vé cực thấp tại Beta Cinemas! Chỉ cần mang theo thẻ sinh viên để nhận khuyến mãi hấp dẫn.',
                'content' => '
                    <p>
                    <strong>Rạp sinh viên – giá vé cũng rất sinh viên</strong></p>
                    <p>Ưu đãi với khách hàng có thẻ học sinh sinh viên, trẻ em cao dưới 1,3m và người trên 55 tuổi</p>
                    <p>🎊 40K cho phim 2D, 60k cho phim 3D: Bắc Giang, Biên Hòa, Nha Trang, Thanh Hóa, Thái Nguyên</p>
                    <p>🎊 40k (thứ 2-4-5-6) & 50k (thứ 7-CN) cho phim 2D, 60k (thứ 2-4-5-6) & 70k (thứ 7-CN) cho phim 3D: Long Khánh</p>
                    <p>🎊 45k (thứ 2-5) & 50k (thứ 6-7-CN) cho phim 2D, 65k (thứ 2-5-) & 70k (thứ 6-7-CN) cho phim 3D: Lào Cai</p>
                    <p>🎊 45k (từ thứ 2-5) & 55k (thứ 6-7-CN) cho phim 2D, 65k (từ thứ 2-5) & 75k (thứ 6-7-CN) cho phim 3D: Quang Trung</p>
                    <p>🎊 45k cho phim 2D, 65k cho phim 3D: Mỹ Đình, Thanh Xuân, Đan Phượng, Tân Uyên, Empire Bình Dương (Thủ Dầu Một)</p>
                    <p>🎊 50k cho phim 2D, 70k cho phim 3D: Giải Phóng</p>
                    <p>🎊 50k (từ thứ 2-5) & 55k (thứ 6-7-CN) cho phim 2D, 70k (từ thứ 2-5) & 75k (thứ 6-7-CN) cho phim 3D: Trần Quang Khải, Ung Văn Khiêm</p>
                    <p><strong>Điều kiện áp dụng:</strong></p>
                    <p>Xuất trình thẻ HSSV (nếu có) hoặc CMND/CCCD, bằng lái xe dưới 22 tuổi.</p>
                    <p>Mặc đồng phục của trường</p>
                    <p>Chiều cao dưới 1m3</p>
                    <p><strong>Lưu ý:</strong></p>
                    <p><strong>Chỉ áp dụng cho khách hàng thành viên của Beta Cinemas.</strong></p>
                    <p>Thẻ học sinh, sinh viên phải còn thời hạn áp dụng.</p>
                    <p>1 thẻ học sinh, sinh viên có thể áp dụng được cho cả nhóm khách hàng đi cùng đối với phim không giới hạn độ tuổi
                    (các phim từ T13 trở lên cần kiểm tra thẻ của từng người).</p>
                    <p>Ưu đãi áp dụng với người lớn tuổi (trên 55t) và phải xuất trình CMND trước khi mua vé.</p>
                    <p>Không áp dụng đồng thời với các chương trình khuyến mãi khác.</p>
                    <p>Chỉ áp dụng cho mua vé tại quầy.</p>
                    <p>Không áp dụng cho mua vé Online.</p>
                    <p>Không áp dụng nếu trùng vào ngày lễ, Tết.</p>
                    <p>Không áp dụng cho các suất chiếu đặc biệt hoặc phim ra mắt sớm.</p>
                ',
            ],
            // 8
            [
                'title' => 'THÀNH LẬP LIÊN DOANH BETA MEDIA VÀ AEON ENTERTAINMENT',
                'slug' => 'lien-doanh-beta-media-aeon-entertainment',
                'img_post' => 'https://files.betacorp.vn//media/images/2024/09/05/z5799808128187-c7065a264ae65ee9119069e5f37ee079-144458-050924-43.jpg',
                'description' => 'Beta Media chính thức hợp tác cùng AEON Entertainment, mở ra kỷ nguyên mới cho ngành giải trí
                                tại Việt Nam với nhiều dự án rạp chiếu phim hấp dẫn.',
                'content' => '
                    <p><strong>Ngày 31.07.2024, Beta Media (Việt Nam) và Aeon Entertainment (Nhật Bản) chính thức bố công ty liên doanh tại Việt Nam.
                    Liên doanh này tập trung vào việc phát triển, quản lý, vận hành chuỗi rạp chiếu phim cao cấp; đầu tư sản xuất phim điện ảnh
                    và phát hành các bộ phim Việt Nam, Nhật Bản và quốc tế tại thị trường Việt Nam.</strong></p>
                    <p><strong>Sự kiện công bố liên doanh này là dấu mốc quan trọng trong sự phát triển chung của nền điện ảnh Việt Nam,
                    cũng như sự hợp tác kinh tế giữa hai quốc gia Nhật Bản – Việt Nam. Buổi lễ công bố đã thu hút sự tham dự của các cơ quan báo chí, các đơn vị,
                    cá nhân hoạt động trong lĩnh vực phim ảnh, giải trí. Đặc biệt, sự kiện vinh dự đón tiếp ông Nobuyuki Fujiwara – Chủ tịch Aeon Entertainment
                    và ông Bùi Quang Minh – Chủ tịch Beta Group.</strong></p>
                    <p><strong>Dự kiến, vài chục tỉ Yên (vài nghìn tỉ đồng) sẽ được đầu tư để xây dựng hơn 50 cụm rạp chiếu phim đẳng cấp với thương hiệu
                    Aeon Beta Cinema cho tới năm 2035, mang phong cách hiện đại hài hoà với các giá trị truyền thống của Việt Nam và Nhật Bản.
                    Các rạp chiếu phim Aeon Beta Cinema sẽ được triển khai trên khắp các tỉnh thành của Việt Nam, mang đến cho khán giả những trải nghiệm
                    xem phim tuyệt vời nhất. Dự kiến, rạp chiếu phim Aeon Beta Cinema đầu tiên sẽ khai trương vào năm 2025.</strong></p>
                    <p><strong>Liên doanh này không chỉ dừng lại ở việc phát triển hệ thống rạp chiếu phim mà còn đặt mục tiêu tham gia mạnh mẽ vào lĩnh vực sản xuất,
                    phát hành phim. Theo thoả thuận, các dự án sản xuất và phát hành phim điện ảnh sẽ được triển khai với thương hiệu Aeon Beta, hứa hẹn mang đến
                    cho khán giả những bộ phim chất lượng, giàu giá trị nghệ thuật và giải trí.</strong></p>
                    <p><strong>Được thành lập từ năm 2014, Beta Media là một công ty của hệ sinh thái Beta Group. Là công ty vận hành, phát triển
                    chuỗi rạp chiếu phim nhắm vào phân khúc tầm trung tại Việt Nam, Beta Media đã phát triển 20 cụm rạp chiếu Beta Cinemas khắp các tỉnh thành
                    trên cả nước. Trong khi Beta Cinemas vẫn giữ chiến lược phục vụ khách hàng trung cấp (mass market), sự ra đời của Aeon Beta sẽ mang đến
                    những trải nghiệm đẳng cấp cho các khách hàng thuộc phân khúc cao cấp hơn. </strong></p>
                    <p><strong>Về phía đối tác chiến lược, Aeon Entertainment là một công ty con thuộc Tập đoàn Aeon Nhật Bản. Được thành lập từ năm 1991,
                    Aeon Entertainment hiện có 96 rạp chiếu phim, là chuỗi rạp lớn nhất ở đất nước mặt trời mọc (tính đến tháng 7/2024). Đặc biệt, với triết lý mở rộng
                    ranh giới của sự phấn khích cho giới mộ điệu điện ảnh và lấp đầy cuộc sống con người bằng niềm vui và sự phấn khích, đơn vị này luôn đi đầu
                    trong việc khởi chiếu những bộ phim điện ảnh mới nhất trên thế giới. Việc thành lập liên doanh ở Việt Nam cho thấy tiềm lực và nỗ lực phát triển
                    trên thị trường quốc tế của Aeon Entertainment.</strong></p>
                    <p><strong>Theo chia sẻ của ông Nobuyuki Fujiwara – Chủ tịch Aeon Entertainment, Beta Media là đối tác hoàn hảo, bởi doanh nghiệp này
                    có sự hiểu biết sâu rộng về thị trường Việt Nam, kiến thức marketing vượt trội và khả năng kết nối mạng lưới địa phương mạnh mẽ. “Điện ảnh
                    có khả năng kết nối con người và tâm hồn lại với nhau. Chúng tôi tin vào sức mạnh đó và sẽ tiếp tục thách thức bản thân để mang đến sự bất ngờ
                    và phấn khích cho khách hàng tại Việt Nam”, ông Nobuyuki Fujiwara khẳng định.</strong></p>
                    <p><strong>Cùng quan điểm với đối tác, ông Bùi Quang Minh, Chủ tịch Beta Group, nhấn mạnh: “Liên doanh này là kết quả tốt đẹp của sự chia sẻ
                    tầm nhìn, khát vọng, cũng như giá trị cốt lõi để cùng nhau mang lại những trải nghiệm mới mẻ và giá trị bền vững cho cộng đồng. Sự kết hợp giữa
                    Aeon Entertainment, với tiềm lực mạnh mẽ và bề dày kinh nghiệm trong ngành công nghiệp điện ảnh, cùng Beta Media, với sự hiểu biết sâu sắc về
                    thị trường Việt Nam và năng lực đổi mới sáng tạo, sẽ tạo ra những cơ hội phát triển đột phá cho cả hai bên”. </strong></p>
                    <p><strong>Với sự ra đời của Liên doanh Aeon Beta, thị trường rạp chiếu phim, sản xuất và phát hành phim sẽ có thêm một thương hiệu quy mô và
                    đẳng cấp, góp phần nâng tầm trải nghiệm cho những người yêu thích điện ảnh. Đồng thời, Liên doanh này cam kết xây dựng và phát triển văn hoá,
                    quan hệ Việt-Nhật, đóng góp vào sự phát triển bền vững cho cộng đồng và xã hội.</strong></p>
                    <p><strong>Cùng xem lại Lễ ký kết liên doanh Aeon Beta tại đây: RECAP LIÊN DOANH BETA MEDIA VÀ AEON ENTERTAINMENT</strong></p>
                ',
            ],
            // 9
            [
                'title' => 'SHARK MINH BETA KÝ KẾT NHƯỢNG QUYỀN “RẠP CHIẾU PHIM TRIỆU LIKE”, NÂNG TỔNG SỐ LÊN 19 CỤM RẠP BETA CINEMAS',
                'slug' => 'shark-minh-beta-nhuong-quyen-19-rap',
                'img_post' => 'https://files.betacorp.vn//cms/images/2024/04/03/nghh6516-1-134044-030424-58.png',
                'description' => 'Shark Minh Beta ký kết nhượng quyền “Rạp chiếu phim triệu like”, đưa tổng số rạp Beta Cinemas lên con số 19.
                                Xem phim dễ dàng tại nhiều địa điểm hơn bao giờ hết!',
                'content' => '
                    <p><strong>Vừa qua, ngày 21 tháng 12 năm 2023 tại tòa nhà Trung tâm văn hoá đa năng IMC, tọa lạc tại 62 Trần Quang Khải,
                     Quận 1, TP Hồ Chí Minh. Chủ tịch Beta Group - Bùi Quang Minh ký kết hợp tác nhượng quyền rạp phim Beta Cinemas lần thứ 19 tại Sài Gòn.</strong></p>
                    <p>Lễ ký kết với sự tham gia của đại diện Beta Media ông Bùi Quang Minh (Nhà Điều Hành kiêm Nhà Sáng Lập Beta Group),
                    cùng với Công Ty Cổ Phần APJ Entertainment đại diện bên nhượng quyền và các khách mời đặc biệt là lãnh đạo cấp cao của các công ty đối tác,
                    nhà đầu tư, đơn vị báo đài, các phòng ban quan trọng của cả 2 công ty.</p>
                    <p>Xã hội - Shark Minh Beta ký kết nhượng quyền “Rạp chiếu phim triệu like”, nâng tổng số lên 19 cụm rạp Beta Cinemas</p>
                    <p>Lễ ký kết đem đến cơ hội đầu tư “uy tín - an toàn” với mô hình nhượng quyền rạp phim đa dạng về các phân khúc. Đây cũng là thương hiệu
                    rạp chiếu phim nhượng quyền đầu tiên tại Việt Nam, hiện đang có 19 cụm rạp trải dài khắp cả nước và vẫn đang tiếp tục mở rộng.</p>
                    <p>Beta Cinemas đang giới thiệu 3 gói nhượng quyền: Beta Lite (Thiết kế trẻ trung, chất lượng tiêu chuẩn), Beta Standard (Thiết kế hiện đại,
                    chất lượng quốc tế), Beta Premium (Thiết kế sang trọng, chất lượng đẳng cấp). Rạp phim được trang bị cơ sở vật chất, thiết bị hiện đại theo
                    tiêu chuẩn Hollywood 100% nhập khẩu từ nước ngoài.</p>
                    <p>Xã hội - Shark Minh Beta ký kết nhượng quyền “Rạp chiếu phim triệu like”, nâng tổng số lên 19 cụm rạp Beta Cinemas (Hình 2).</p>
                    <p>Đối với rạp phim Beta Cinemas Trần Quang Khải, mỗi phòng vé đều được lắp đặt hệ thống âm thanh Dolby 7.1 và hệ thống cách âm chuẩn quốc tế
                    giúp đem lại trải nghiệm âm thanh và hình ảnh sống động chất lượng cho từng thước phim bom tấn. Các bộ phim điện ảnh được cập nhật liên tục,
                    đảm bảo độ HOT trên thị trường, mang đến những siêu phẩm chất lượng nhất cho khán giả. </p>
                    <p>Ngoài mức giá cạnh tranh phù hợp với chi tiêu của giới trẻ, đặc biệt là thế hệ Gen Z, Beta Cinemas Trần Quang Khải còn thường xuyên
                    có chương trình khuyến mại, ưu đãi cực kỳ đa dạng như Mad Sale Day vào thứ 2 đầu tiên của tháng, đồng giá vé vào các ngày Thứ 3 vui vẻ hàng tuần,...</p>
                    <p><strong>Đánh dấu cột mốc rạp Beta thứ 19 trong chuỗi rạp Beta Cinemas </strong></p>
                    <p>Beta Cinemas là mô hình rạp chiếu với giá vé hợp lý, hướng tới nhóm khách hàng học sinh, sinh viên và người thu nhập ở mức trung bình nhưng
                    vẫn đảm bảo những tiêu chuẩn chất lượng dịch vụ và trải nghiệm tốt. Sau gần 10 năm thành lập và phát triển, Beta Cinemas đã xây dựng 18 cụm
                    rạp trải dài khắp cả nước bao gồm: TP.HCM, Hà Nội, Thái Nguyên, Thanh Hóa, Bắc Giang… và mới nhất là cụm thứ 19 trong chuỗi rạp Beta Cinemas,
                    đây cũng là rạp thứ 2 ở TP. Hồ Chí Minh.</p>
                    <p>Xã hội - Shark Minh Beta ký kết nhượng quyền “Rạp chiếu phim triệu like”, nâng tổng số lên 19 cụm rạp Beta Cinemas (Hình 3).</p>
                    <p>Rạp chiếu phim được thành lập với mục tiêu đem đến cho khách hàng các sản phẩm và dịch vụ chất lượng tốt nhất, giá cả hợp lý nhất, với
                    2 mảng kinh doanh chính là: Tổ hợp dịch vụ ăn uống giải trí và cung cấp dịch vụ truyền thông. Cùng mục tiêu đem lại những trải nghiệm văn hoá
                    và giải trí tuyệt vời cho người dân Việt Nam. Với sứ mệnh mong muốn mang tới giá trị văn hóa hiện đại và chất lượng, Beta luôn lắng nghe,
                    nghiên cứu nhằm thấu hiểu và thoả mãn nhu cầu của khách hàng, sáng tạo trong từng sản phẩm, tận tâm đem đến chất lượng dịch vụ hàng đầu. </p>
                    <p>Beta Cinemas sẽ chính thức có mặt tại tòa nhà Trung tâm văn hoá đa năng IMC, tọa lạc tại 62 Trần Quang Khải, Quận 1, TP Hồ Chí Minh vào
                    đầu năm 2024. Thương hiệu hướng đến mục tiêu mở rộng thị trường tại TP Hồ Chí Minh và các tỉnh thành khác trên cả nước trong tương lai.
                    Đa dạng phân khúc khách hàng với nhiều mô hình ưu Việt phù hợp với các nhà đầu tư. Trung tâm văn hóa đa năng IMC với tổ hợp dịch vụ vui chơi
                    giải trí được đầu tư chỉn chu, kỹ lưỡng và tinh tế đáp ứng nhu cầu ngày càng đa dạng của khách hàng.</p>
                    <p>Bên cạnh đó, với vị thế đắc địa của trung tâm Quận 1, TP Hồ Chí Minh nơi giao thương sầm uất. Đây chính là tiền đề quan trọng cho Lễ ký
                    kết hợp tác nhượng quyền rạp phim Beta Cinemas Trần Quang Khải giữa Công Ty Cổ Phần Beta Media và Công Ty Cổ Phần APJ Entertainment. </p>
                    <p>Xã hội - Shark Minh Beta ký kết nhượng quyền “Rạp chiếu phim triệu like”, nâng tổng số lên 19 cụm rạp Beta Cinemas (Hình 4).</p>
                    <p>Với sứ mệnh luôn tự tin trong việc đi đầu trong phân khúc thị trường trung cấp và là chuỗi rạp đầu tiên hoàn thiện chính sách nhượng quyền
                    thương hiệu phát triển mạnh nhất tại thị trường trong nước. Đội ngũ quản lý chuyên nghiệp, sẽ sẵn sàng luôn hỗ trợ các nhà nhượng quyền trong
                    việc tiếp xúc với thị trường có số lượng khách nhất định. Đặc biệt, chỉ với một khoản đầu tư hợp lý, Beta sử dụng hiệu quả chi phí đầu tư &
                    tối ưu việc vận hành trong kinh doanh. Thời gian hoàn vốn nhanh chỉ từ 3 - 5 năm với tỷ suất lợi nhuận cao và ổn định.</p>
                    <p>Là thị trường nhượng quyền thu hút các nhiều nhà đầu tư lớn, Beta Cinemas sẽ luôn không ngừng nỗ lực để tạo ra nhiều giá trị hơn nữa đến các
                    phân khúc khách hàng.</p>
                ',
            ],
            // 10
            [
                'title' => 'BETA TRMALL PHÚ QUỐC CHÍNH THỨC KHAI TRƯƠNG VÀO 10/11/2023',
                'slug' => 'beta-trmall-phu-quoc-khai-truong-10-11-2023',
                'img_post' => 'https://files.betacorp.vn//media/images/2024/04/16/b8c25b2a-b154-4cf5-9a5d-c4b119b4477d-113630-160424-78.jpeg',
                'description' => 'Beta TRMall Phú Quốc chính thức khai trương vào ngày 10/11/2023. Đến ngay để trải nghiệm không gian giải trí đẳng cấp cùng
                nhiều ưu đãi hấp dẫn!',
                'content' => '
                    <p><strong>
                    NGÀY ẤY ĐÃ ĐẾN!!!</strong></p>
                    <p><strong>🎉🎉🎉 BETA TRMALL PHÚ QUỐC CHÍNH THỨC KHAI TRƯƠNG VÀO THỨ 6 TUẦN NÀY (10/11/2023) 🎉🎉🎉</strong></p>
                    <p><strong>Xin thông báo tới toàn thể server Beta, "người chơi" hệ Premium - Beta TRMall Phú Quốc đã sẵn sàng 🔥</strong></p>
                    <p><strong>Sinh sau đẻ muộn nhưng thần thái ngút ngàn, Beta TRMall Phú Quốc quyết tâm trình làng với diện mạo "chanh sả" hết cỡ,
                    khuyến mại tới tấp và list phim cực kỳ uy tín 😤</strong></p>
                    <p><strong>Nào anh em, full đồ max ping cùng ad ghé rạp săn góc sống ảo, săn sale, săn phim nào!</strong></p>
                    <p><strong>⛳ Địa chỉ rạp: TTTM TR MALL - Sonasea - Đường Bãi Trường - Xã Dương Tơ - Thành phố Phú Quốc - Tỉnh Kiên Giang</strong></p>
                    <p><strong>☎ Hotline: 0983 474 440</strong></p>
                ',
            ],
        ];

        foreach ($posts as $post) {
            Post::create([
                'user_id' => random_int(1, 4),
                'title' => $post['title'],
                'slug' => $post['slug'],
                'img_post' => $post['img_post'],
                'description' => $post['description'],
                'content' => $post['content'],
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
        // End tạo 10 bài viết

        // 5 dữ liệu liên hệ
        $contacts = [
            [
                'user_contact' => 'Bùi Đỗ Đạt',
                'email' => 'buidodat@gmail.com',
                'phone' => '0901234567',
                'title' => 'Lỗi khi đặt vé',
                'content' => 'Tôi gặp lỗi khi thanh toán vé online, vui lòng hỗ trợ.',
                'status' => 'pending',
                'created_at' => $now,
            ],
            [
                'user_contact' => 'Trương Công Lực',
                'email' => 'truongcongluc@gmail.com',
                'phone' => '0912345678',
                'title' => 'Hỏi về khuyến mãi',
                'content' => 'Cho tôi hỏi về chương trình khuyến mãi vào cuối tuần này.',
                'status' => 'resolved',
                'created_at' => $now->subDays(2),
            ],
            [
                'user_contact' => 'Nguyễn Viết Sơn',
                'email' => 'nguyenvietson@gmail.com',
                'phone' => '0923456789',
                'title' => 'Phản ánh dịch vụ',
                'content' => 'Nhân viên không hỗ trợ nhiệt tình, mong được cải thiện.',
                'status' => 'pending',
                'created_at' => $now->subDays(5),
            ],
            [
                'user_contact' => 'Đặng Phú An',
                'email' => 'dangphuan@gmail.com',
                'phone' => '0934567890',
                'title' => 'Hủy vé đã đặt',
                'content' => 'Tôi muốn hủy vé vì không thể đi vào ngày đã chọn.',
                'status' => 'resolved',
                'created_at' => $now->subDays(7),
            ],
            [
                'user_contact' => 'Hà Đắc Hiếu',
                'email' => 'hadachieu@gmail.com',
                'phone' => '0945678901',
                'title' => 'Góp ý giao diện',
                'content' => 'Giao diện trang web cần thêm màu sắc sinh động hơn.',
                'status' => 'pending',
                'created_at' => $now->subDays(10),
            ],
        ];
        foreach ($contacts as $ct) {
            Contact::create($ct);
        }


        // Phân quyền : Danh sách quyền
        $permissions = [
            'Danh sách chi nhánh',
            'Thêm chi nhánh',
            'Sửa chi nhánh',
            'Xóa chi nhánh',
            'Danh sách rạp',
            'Thêm rạp',
            'Sửa rạp',
            'Xóa rạp',
            'Danh sách phòng chiếu',
            'Thêm phòng chiếu',
            'Sửa phòng chiếu',
            'Xóa phòng chiếu',
            'Xem chi tiết phòng chiếu',
            'Danh sách mẫu sơ đồ ghế',
            'Thêm mẫu sơ đồ ghế',
            'Sửa mẫu sơ đồ ghế',
            'Xóa mẫu sơ đồ ghế',
            'Danh sách phim',
            'Thêm phim',
            'Sửa phim',
            'Xóa phim',
            'Xem chi tiết phim',
            'Danh sách suất chiếu',
            'Thêm suất chiếu',
            'Sửa suất chiếu',
            'Xóa suất chiếu',
            'Xem chi tiết suất chiếu',
            'Danh sách hóa đơn',
            'Quét hóa đơn',

            'Xem chi tiết hóa đơn',

            'Danh sách đồ ăn',
            'Thêm đồ ăn',
            'Sửa đồ ăn',
            'Xóa đồ ăn',
            'Danh sách combo',
            'Thêm combo',
            'Sửa combo',
            'Xóa combo',
            'Danh sách vouchers',
            'Thêm vouchers',
            'Sửa vouchers',
            'Xóa vouchers',
            'Danh sách thanh toán',
            'Thêm thanh toán',
            'Sửa thanh toán',
            'Xóa thanh toán',
            'Danh sách giá',
            // 'Thêm giá',
            'Sửa giá',
            // 'Xóa giá',
            'Danh sách bài viết',
            'Thêm bài viết',
            'Sửa bài viết',
            'Xóa bài viết',
            'Xem chi tiết bài viết',
            'Danh sách slideshows',
            'Thêm slideshows',
            'Sửa slideshows',
            'Xóa slideshows',
            'Danh sách liên hệ',
            // 'Thêm liên hệ',
            'Sửa liên hệ',
            // 'Xóa liên hệ',
            'Danh sách tài khoản',
            'Thêm tài khoản',
            'Sửa tài khoản',
            'Xóa tài khoản',
            'Cấu hình website',
            'Danh sách thống kê',
            'Thẻ thành viên'

        ];

        // Tạo các quyền từ danh sách
        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Tạo các vai trò
        $roles = [
            'System Admin',
            'Quản lý cơ sở',
            'Nhân viên'
        ];

        foreach ($roles as $roleName) {
            Role::create(['name' => $roleName]);
        }

        // Gán tất cả quyền cho System Admin
        $adminRole = Role::findByName('System Admin');
        $adminRole->syncPermissions(Permission::all());


        $user = User::find(1);
        $user->assignRole('System Admin');


        $managerRole = Role::findByName('Quản lý cơ sở');
        $managerRole->givePermissionTo([
            'Danh sách phòng chiếu',
            'Thêm phòng chiếu',
            'Sửa phòng chiếu',
            'Xóa phòng chiếu',
            'Xem chi tiết phòng chiếu',
            'Danh sách mẫu sơ đồ ghế',
            // 'Thêm mẫu sơ đồ ghế',
            // 'Sửa mẫu sơ đồ ghế',
            // 'Xóa mẫu sơ đồ ghế',
            'Danh sách phim',
            'Xem chi tiết phim',
            'Danh sách suất chiếu',
            'Thêm suất chiếu',
            'Sửa suất chiếu',
            'Xóa suất chiếu',
            'Xem chi tiết suất chiếu',
            'Danh sách hóa đơn',
            'Quét hóa đơn',
            'Xem chi tiết hóa đơn',
            // 'Danh sách đồ ăn',
            'Danh sách combo',
            // 'Danh sách vouchers',
            // 'Danh sách thanh toán',
            // 'Danh sách bài viết',
            // 'Danh sách slideshows',
            // 'Danh sách liên hệ',
            // 'Sửa liên hệ',
            // 'Danh sách tài khoản',
            'Danh sách thống kê',
        ]);

        $managerRole = Role::findByName('Nhân viên');
        $managerRole->givePermissionTo([
            'Danh sách hóa đơn',
            'Quét hóa đơn',
            'Xem chi tiết hóa đơn',
        ]);


        // $user = User::find(2);
        // $user->assignRole('Quản lý cơ sở');
        // $user = User::find(3);
        // $user->assignRole('Quản lý cơ sở');
        // $user = User::find(4);
        // $user->assignRole('Quản lý cơ sở');

        $user = User::find(8);
        $user->assignRole('Nhân viên');
        $user = User::find(9);
        $user->assignRole('Nhân viên');

        $user = User::find(10);
        $user->assignRole('Quản lý cơ sở');
        $user = User::find(11);
        $user->assignRole('Quản lý cơ sở');


        // $user = User::find(5);
        // $user->assignRole('Nhân viên');


        // Cấu hình website
        SiteSetting::create([
            'website_logo' => 'theme/client/images/header/P.svg',
            'site_name' => 'Poly Cinemas',
            'brand_name' => 'Hệ thống Rạp chiếu phim toàn quốc Poly Cinemas',
            'slogan' => 'Chất lượng dịch vụ luôn là số 1',
            'phone' => '0999999999',
            'email' => 'polycinemas@poly.cenimas.vn',
            'headquarters' => 'Tòa nhà FPT Polytechnic, Phố Trịnh Văn Bô, Nam Từ Liêm, Hà Nội',
            'business_license' => 'Đây là giấy phép kinh doanh',
            'working_hours' => '7:00 - 22:00',
            'facebook_link' => 'https://facebook.com/',
            'youtube_link' => 'https://youtube.com/',
            'instagram_link' => 'https://instagram.com/',
            'privacy_policy_image' => 'theme/client/images/z6051700744901_e30e7f1c520f5521d677eed36a1e7e3c.jpg',
            'privacy_policy' => '
                <b>Chào mừng Quý khách hàng đến với Hệ thống Bán Vé Online của chuỗi Rạp Chiếu Phim POLY CINEMAS!</b>
                <p>Xin cảm ơn và chúc Quý khách hàng có những giây phút xem phim tuyệt vời tại POLY CINEMAS!</p>
                <b>Sau đây là một số lưu ý trước khi thanh toán trực tuyến:</b> <br>
                <ul>
                    <li>1. Thẻ phải được kích hoạt chức năng thanh toán trực tuyến, và có đủ
                        hạn
                        mức/ số dư để thanh toán. Quý khách cần nhập chính xác thông tin thẻ
                        (tên
                        chủ thẻ, số thẻ, ngày hết hạn, số CVC, OTP,...).</li>
                    <li>2. Vé và hàng hóa đã thanh toán thành công không thể hủy/đổi
                        trả/hoàn tiền
                        vì bất kỳ lý do gì. POLY CINEMAS chỉ thực hiện hoàn tiền trong
                        trường hợp
                        thẻ của Quý khách đã bị trừ tiền nhưng hệ thống của Beta không ghi
                        nhận việc
                        đặt vé/đơn hàng của Quý khách, và Quý khách không nhận được xác nhận
                        đặt
                        vé/đơn hàng thành công.</li>
                    <li>3. Trong vòng 30 phút kể từ khi thanh toán thành công, POLY CINEMAS
                        sẽ gửi
                        Quý khách mã xác nhận thông tin vé/đơn hàng qua email của Quý khách.
                        Nếu Quý
                        khách cần hỗ trợ hay thắc mắc, khiếu nại về xác nhận mã vé/đơn hàng
                        thì vui
                        lòng phản hồi về Fanpage Facebook POLY CINEMAS trong vòng 60 phút kể
                        từ khi
                        thanh toán vé thành công. Sau khoảng thời gian trên, POLY CINEMAS sẽ
                        không
                        chấp nhận giải quyết bất kỳ khiếu nại nào.</li>
                    <li>4. POLY CINEMAS không chịu trách nhiệm trong trường hợp thông tin
                        địa chỉ
                        email, số điện thoại Quý khách nhập không chính xác dẫn đến không
                        nhận được
                        thư xác nhận. Vui lòng kiểm tra kỹ các thông tin này trước khi thực
                        hiện
                        thanh toán. POLY CINEMAS không hỗ trợ xử lý và không chịu trách
                        nhiệm trong
                        trường hợp đã gửi thư xác nhận mã vé/đơn hàng đến địa chỉ email của
                        Quý
                        khách nhưng vì một lý do nào đó mà Quý khách không thể đến xem phim.
                    </li>
                    <li>5. Vui lòng kiểm tra thông tin xác nhận vé cẩn thận và ghi nhớ mã
                        đặt vé/đơn
                        hàng. Khi đến nhận vé/hàng hóa tại Quầy vé của POLY CINEMAS, Quý
                        khách cũng
                        cần mang theo giấy tờ tùy thân như Căn cước công dân/Chứng minh nhân
                        dân,
                        Thẻ học sinh, Thẻ sinh viên hoặc hộ chiếu.</li>
                    <li>7. Vì một số sự cố kỹ thuật bất khả kháng, suất chiếu có thể bị huỷ
                        để đảm
                        bảo an toàn tối đa cho khách hàng, POLY CINEMAS sẽ thực hiện hoàn
                        trả số
                        tiền giao dịch về tài khoản mà Quý khách đã thực hiện mua vé. Beta
                        Cinemas
                        sẽ liên hệ với Quý khách qua các thông tin liên hệ trong mục Thông
                        tin thành
                        viên để thông báo và xác nhận.</li>
                    <li>8. Nếu Khách hàng mua vé tại website, khi đến quầy tại rạp cần xuất trình hóa đơn để nhân viên đối chiếu và cung cấp cho bạn vé vào rạp xem phim !.</li>
                </ul>',

            'terms_of_service_image' => 'theme/client/images/header/P.svg',

            'terms_of_service' => 'Đây là  điều khoản Dịch vụ',
            'introduction_image' => 'theme/client/images/thumbnail-1-144816-050424-68.jpeg',
            'introduction' => '
            <p>F5 Poly Media được thành lập bởi doanh nhân F5 Poly Cinemas (F5 Poly Beta) vào cuối năm 2014 với sứ mệnh "Mang trải nghiệm điện ảnh với mức giá hợp lý cho mọi người dân Việt Nam".</p>
            <p>Với thiết kế độc đáo, trẻ trung, F5 Poly Cinemas mang đến trải nghiệm điện ảnh chất lượng với chi phí đầu tư và vận hành tối ưu - nhờ việc chọn địa điểm phù hợp, tận dụng tối đa diện tích, bố trí khoa học, nhằm duy trì giá vé xem phim trung bình chỉ từ 40,000/1 vé - phù hợp với đại đa số người dân Việt Nam.</p>
            <p>Năm 2023 đánh dấu cột mốc vàng son cho Poly Cinemas khi ghi nhận mức tăng trưởng doanh thu ấn tượng 150% so với năm 2019 - là năm đỉnh cao của ngành rạp chiếu phim trước khi đại dịch Covid-19 diễn ra. Thành tích này cho thấy sức sống mãnh liệt và khả năng phục hồi ấn tượng của chuỗi rạp.</p>
            <p>Tính đến thời điểm hiện tại, Poly Cinemas đang có 20 cụm rạp trải dài khắp cả nước, phục vụ tới 6 triệu khách hàng mỗi năm, là doanh nghiệp dẫn đầu phân khúc đại chúng của thị trường điện ảnh Việt. Poly Media cũng hoạt động tích cực trong lĩnh vực sản xuất và phát hành phim.</p>
            <p>Ngoài đa số các cụm rạp do Poly Media tự đầu tư, ¼ số cụm rạp của Poly Media còn được phát triển bằng hình thức nhượng quyền linh hoạt. Chi phí đầu tư rạp chiếu phim Poly Cinemas được tối ưu giúp nhà đầu tư dễ dàng tiếp cận và nhanh chóng hoàn vốn, mang lại hiệu quả kinh doanh cao và đảm bảo.</p>',
            'copyright' => 'Bản quyền © 2024 Poly Cinemas',
            'created_at' => $now,
            'updated_at' => $now,
        ]);
    }

    private function generateSeatStructure1()
    {

        // 4 hàng đầu tiên: Ghế thường
        $structure = "[{\"coordinates_x\":\"2\",\"coordinates_y\":\"A\",\"type_seat_id\":\"1\"},{\"coordinates_x\":\"3\",\"coordinates_y\":\"A\",\"type_seat_id\":\"1\"},{\"coordinates_x\":\"4\",\"coordinates_y\":\"A\",\"type_seat_id\":\"1\"},{\"coordinates_x\":\"5\",\"coordinates_y\":\"A\",\"type_seat_id\":\"1\"},{\"coordinates_x\":\"6\",\"coordinates_y\":\"A\",\"type_seat_id\":\"1\"},{\"coordinates_x\":\"7\",\"coordinates_y\":\"A\",\"type_seat_id\":\"1\"},{\"coordinates_x\":\"8\",\"coordinates_y\":\"A\",\"type_seat_id\":\"1\"},{\"coordinates_x\":\"9\",\"coordinates_y\":\"A\",\"type_seat_id\":\"1\"},{\"coordinates_x\":\"10\",\"coordinates_y\":\"A\",\"type_seat_id\":\"1\"},{\"coordinates_x\":\"11\",\"coordinates_y\":\"A\",\"type_seat_id\":\"1\"},{\"coordinates_x\":\"1\",\"coordinates_y\":\"B\",\"type_seat_id\":\"1\"},{\"coordinates_x\":\"2\",\"coordinates_y\":\"B\",\"type_seat_id\":\"1\"},{\"coordinates_x\":\"3\",\"coordinates_y\":\"B\",\"type_seat_id\":\"1\"},{\"coordinates_x\":\"4\",\"coordinates_y\":\"B\",\"type_seat_id\":\"1\"},{\"coordinates_x\":\"5\",\"coordinates_y\":\"B\",\"type_seat_id\":\"1\"},{\"coordinates_x\":\"6\",\"coordinates_y\":\"B\",\"type_seat_id\":\"1\"},{\"coordinates_x\":\"7\",\"coordinates_y\":\"B\",\"type_seat_id\":\"1\"},{\"coordinates_x\":\"8\",\"coordinates_y\":\"B\",\"type_seat_id\":\"1\"},{\"coordinates_x\":\"9\",\"coordinates_y\":\"B\",\"type_seat_id\":\"1\"},{\"coordinates_x\":\"10\",\"coordinates_y\":\"B\",\"type_seat_id\":\"1\"},{\"coordinates_x\":\"11\",\"coordinates_y\":\"B\",\"type_seat_id\":\"1\"},{\"coordinates_x\":\"12\",\"coordinates_y\":\"B\",\"type_seat_id\":\"1\"},{\"coordinates_x\":\"1\",\"coordinates_y\":\"C\",\"type_seat_id\":\"1\"},{\"coordinates_x\":\"2\",\"coordinates_y\":\"C\",\"type_seat_id\":\"1\"},{\"coordinates_x\":\"3\",\"coordinates_y\":\"C\",\"type_seat_id\":\"1\"},{\"coordinates_x\":\"4\",\"coordinates_y\":\"C\",\"type_seat_id\":\"1\"},{\"coordinates_x\":\"5\",\"coordinates_y\":\"C\",\"type_seat_id\":\"1\"},{\"coordinates_x\":\"6\",\"coordinates_y\":\"C\",\"type_seat_id\":\"1\"},{\"coordinates_x\":\"7\",\"coordinates_y\":\"C\",\"type_seat_id\":\"1\"},{\"coordinates_x\":\"8\",\"coordinates_y\":\"C\",\"type_seat_id\":\"1\"},{\"coordinates_x\":\"9\",\"coordinates_y\":\"C\",\"type_seat_id\":\"1\"},{\"coordinates_x\":\"10\",\"coordinates_y\":\"C\",\"type_seat_id\":\"1\"},{\"coordinates_x\":\"11\",\"coordinates_y\":\"C\",\"type_seat_id\":\"1\"},{\"coordinates_x\":\"12\",\"coordinates_y\":\"C\",\"type_seat_id\":\"1\"},{\"coordinates_x\":\"1\",\"coordinates_y\":\"E\",\"type_seat_id\":\"2\"},{\"coordinates_x\":\"2\",\"coordinates_y\":\"E\",\"type_seat_id\":\"2\"},{\"coordinates_x\":\"3\",\"coordinates_y\":\"E\",\"type_seat_id\":\"2\"},{\"coordinates_x\":\"4\",\"coordinates_y\":\"E\",\"type_seat_id\":\"2\"},{\"coordinates_x\":\"5\",\"coordinates_y\":\"E\",\"type_seat_id\":\"2\"},{\"coordinates_x\":\"6\",\"coordinates_y\":\"E\",\"type_seat_id\":\"2\"},{\"coordinates_x\":\"7\",\"coordinates_y\":\"E\",\"type_seat_id\":\"2\"},{\"coordinates_x\":\"8\",\"coordinates_y\":\"E\",\"type_seat_id\":\"2\"},{\"coordinates_x\":\"9\",\"coordinates_y\":\"E\",\"type_seat_id\":\"2\"},{\"coordinates_x\":\"10\",\"coordinates_y\":\"E\",\"type_seat_id\":\"2\"},{\"coordinates_x\":\"11\",\"coordinates_y\":\"E\",\"type_seat_id\":\"2\"},{\"coordinates_x\":\"12\",\"coordinates_y\":\"E\",\"type_seat_id\":\"2\"},{\"coordinates_x\":\"1\",\"coordinates_y\":\"F\",\"type_seat_id\":\"2\"},{\"coordinates_x\":\"2\",\"coordinates_y\":\"F\",\"type_seat_id\":\"2\"},{\"coordinates_x\":\"3\",\"coordinates_y\":\"F\",\"type_seat_id\":\"2\"},{\"coordinates_x\":\"4\",\"coordinates_y\":\"F\",\"type_seat_id\":\"2\"},{\"coordinates_x\":\"5\",\"coordinates_y\":\"F\",\"type_seat_id\":\"2\"},{\"coordinates_x\":\"6\",\"coordinates_y\":\"F\",\"type_seat_id\":\"2\"},{\"coordinates_x\":\"7\",\"coordinates_y\":\"F\",\"type_seat_id\":\"2\"},{\"coordinates_x\":\"8\",\"coordinates_y\":\"F\",\"type_seat_id\":\"2\"},{\"coordinates_x\":\"9\",\"coordinates_y\":\"F\",\"type_seat_id\":\"2\"},{\"coordinates_x\":\"10\",\"coordinates_y\":\"F\",\"type_seat_id\":\"2\"},{\"coordinates_x\":\"11\",\"coordinates_y\":\"F\",\"type_seat_id\":\"2\"},{\"coordinates_x\":\"12\",\"coordinates_y\":\"F\",\"type_seat_id\":\"2\"},{\"coordinates_x\":\"1\",\"coordinates_y\":\"G\",\"type_seat_id\":\"2\"},{\"coordinates_x\":\"2\",\"coordinates_y\":\"G\",\"type_seat_id\":\"2\"},{\"coordinates_x\":\"3\",\"coordinates_y\":\"G\",\"type_seat_id\":\"2\"},{\"coordinates_x\":\"4\",\"coordinates_y\":\"G\",\"type_seat_id\":\"2\"},{\"coordinates_x\":\"5\",\"coordinates_y\":\"G\",\"type_seat_id\":\"2\"},{\"coordinates_x\":\"6\",\"coordinates_y\":\"G\",\"type_seat_id\":\"2\"},{\"coordinates_x\":\"7\",\"coordinates_y\":\"G\",\"type_seat_id\":\"2\"},{\"coordinates_x\":\"8\",\"coordinates_y\":\"G\",\"type_seat_id\":\"2\"},{\"coordinates_x\":\"9\",\"coordinates_y\":\"G\",\"type_seat_id\":\"2\"},{\"coordinates_x\":\"10\",\"coordinates_y\":\"G\",\"type_seat_id\":\"2\"},{\"coordinates_x\":\"11\",\"coordinates_y\":\"G\",\"type_seat_id\":\"2\"},{\"coordinates_x\":\"12\",\"coordinates_y\":\"G\",\"type_seat_id\":\"2\"},{\"coordinates_x\":\"1\",\"coordinates_y\":\"H\",\"type_seat_id\":\"2\"},{\"coordinates_x\":\"2\",\"coordinates_y\":\"H\",\"type_seat_id\":\"2\"},{\"coordinates_x\":\"3\",\"coordinates_y\":\"H\",\"type_seat_id\":\"2\"},{\"coordinates_x\":\"4\",\"coordinates_y\":\"H\",\"type_seat_id\":\"2\"},{\"coordinates_x\":\"5\",\"coordinates_y\":\"H\",\"type_seat_id\":\"2\"},{\"coordinates_x\":\"6\",\"coordinates_y\":\"H\",\"type_seat_id\":\"2\"},{\"coordinates_x\":\"7\",\"coordinates_y\":\"H\",\"type_seat_id\":\"2\"},{\"coordinates_x\":\"8\",\"coordinates_y\":\"H\",\"type_seat_id\":\"2\"},{\"coordinates_x\":\"9\",\"coordinates_y\":\"H\",\"type_seat_id\":\"2\"},{\"coordinates_x\":\"10\",\"coordinates_y\":\"H\",\"type_seat_id\":\"2\"},{\"coordinates_x\":\"11\",\"coordinates_y\":\"H\",\"type_seat_id\":\"2\"},{\"coordinates_x\":\"12\",\"coordinates_y\":\"H\",\"type_seat_id\":\"2\"},{\"coordinates_x\":\"1\",\"coordinates_y\":\"I\",\"type_seat_id\":\"2\"},{\"coordinates_x\":\"2\",\"coordinates_y\":\"I\",\"type_seat_id\":\"2\"},{\"coordinates_x\":\"3\",\"coordinates_y\":\"I\",\"type_seat_id\":\"2\"},{\"coordinates_x\":\"4\",\"coordinates_y\":\"I\",\"type_seat_id\":\"2\"},{\"coordinates_x\":\"5\",\"coordinates_y\":\"I\",\"type_seat_id\":\"2\"},{\"coordinates_x\":\"6\",\"coordinates_y\":\"I\",\"type_seat_id\":\"2\"},{\"coordinates_x\":\"7\",\"coordinates_y\":\"I\",\"type_seat_id\":\"2\"},{\"coordinates_x\":\"8\",\"coordinates_y\":\"I\",\"type_seat_id\":\"2\"},{\"coordinates_x\":\"9\",\"coordinates_y\":\"I\",\"type_seat_id\":\"2\"},{\"coordinates_x\":\"10\",\"coordinates_y\":\"I\",\"type_seat_id\":\"2\"},{\"coordinates_x\":\"11\",\"coordinates_y\":\"I\",\"type_seat_id\":\"2\"},{\"coordinates_x\":\"12\",\"coordinates_y\":\"I\",\"type_seat_id\":\"2\"},{\"coordinates_x\":\"1\",\"coordinates_y\":\"J\",\"type_seat_id\":\"2\"},{\"coordinates_x\":\"2\",\"coordinates_y\":\"J\",\"type_seat_id\":\"2\"},{\"coordinates_x\":\"3\",\"coordinates_y\":\"J\",\"type_seat_id\":\"2\"},{\"coordinates_x\":\"4\",\"coordinates_y\":\"J\",\"type_seat_id\":\"2\"},{\"coordinates_x\":\"5\",\"coordinates_y\":\"J\",\"type_seat_id\":\"2\"},{\"coordinates_x\":\"6\",\"coordinates_y\":\"J\",\"type_seat_id\":\"2\"},{\"coordinates_x\":\"7\",\"coordinates_y\":\"J\",\"type_seat_id\":\"2\"},{\"coordinates_x\":\"8\",\"coordinates_y\":\"J\",\"type_seat_id\":\"2\"},{\"coordinates_x\":\"9\",\"coordinates_y\":\"J\",\"type_seat_id\":\"2\"},{\"coordinates_x\":\"10\",\"coordinates_y\":\"J\",\"type_seat_id\":\"2\"},{\"coordinates_x\":\"11\",\"coordinates_y\":\"J\",\"type_seat_id\":\"2\"},{\"coordinates_x\":\"12\",\"coordinates_y\":\"J\",\"type_seat_id\":\"2\"},{\"coordinates_x\":\"1\",\"coordinates_y\":\"L\",\"type_seat_id\":\"3\"},{\"coordinates_x\":\"3\",\"coordinates_y\":\"L\",\"type_seat_id\":\"3\"},{\"coordinates_x\":\"5\",\"coordinates_y\":\"L\",\"type_seat_id\":\"3\"},{\"coordinates_x\":\"7\",\"coordinates_y\":\"L\",\"type_seat_id\":\"3\"},{\"coordinates_x\":\"9\",\"coordinates_y\":\"L\",\"type_seat_id\":\"3\"},{\"coordinates_x\":\"11\",\"coordinates_y\":\"L\",\"type_seat_id\":\"3\"}]";
        return $structure;
    }
    private function generateSeatStructure2()
    {

        // 4 hàng đầu tiên: Ghế thường
        $structure = "[{\"coordinates_x\":\"2\",\"coordinates_y\":\"A\",\"type_seat_id\":\"1\"},{\"coordinates_x\":\"3\",\"coordinates_y\":\"A\",\"type_seat_id\":\"1\"},{\"coordinates_x\":\"4\",\"coordinates_y\":\"A\",\"type_seat_id\":\"1\"},{\"coordinates_x\":\"5\",\"coordinates_y\":\"A\",\"type_seat_id\":\"1\"},{\"coordinates_x\":\"6\",\"coordinates_y\":\"A\",\"type_seat_id\":\"1\"},{\"coordinates_x\":\"7\",\"coordinates_y\":\"A\",\"type_seat_id\":\"1\"},{\"coordinates_x\":\"8\",\"coordinates_y\":\"A\",\"type_seat_id\":\"1\"},{\"coordinates_x\":\"9\",\"coordinates_y\":\"A\",\"type_seat_id\":\"1\"},{\"coordinates_x\":\"10\",\"coordinates_y\":\"A\",\"type_seat_id\":\"1\"},{\"coordinates_x\":\"11\",\"coordinates_y\":\"A\",\"type_seat_id\":\"1\"},{\"coordinates_x\":\"12\",\"coordinates_y\":\"A\",\"type_seat_id\":\"1\"},{\"coordinates_x\":\"13\",\"coordinates_y\":\"A\",\"type_seat_id\":\"1\"},{\"coordinates_x\":\"1\",\"coordinates_y\":\"B\",\"type_seat_id\":\"1\"},{\"coordinates_x\":\"2\",\"coordinates_y\":\"B\",\"type_seat_id\":\"1\"},{\"coordinates_x\":\"3\",\"coordinates_y\":\"B\",\"type_seat_id\":\"1\"},{\"coordinates_x\":\"4\",\"coordinates_y\":\"B\",\"type_seat_id\":\"1\"},{\"coordinates_x\":\"5\",\"coordinates_y\":\"B\",\"type_seat_id\":\"1\"},{\"coordinates_x\":\"6\",\"coordinates_y\":\"B\",\"type_seat_id\":\"1\"},{\"coordinates_x\":\"7\",\"coordinates_y\":\"B\",\"type_seat_id\":\"1\"},{\"coordinates_x\":\"8\",\"coordinates_y\":\"B\",\"type_seat_id\":\"1\"},{\"coordinates_x\":\"9\",\"coordinates_y\":\"B\",\"type_seat_id\":\"1\"},{\"coordinates_x\":\"10\",\"coordinates_y\":\"B\",\"type_seat_id\":\"1\"},{\"coordinates_x\":\"11\",\"coordinates_y\":\"B\",\"type_seat_id\":\"1\"},{\"coordinates_x\":\"12\",\"coordinates_y\":\"B\",\"type_seat_id\":\"1\"},{\"coordinates_x\":\"13\",\"coordinates_y\":\"B\",\"type_seat_id\":\"1\"},{\"coordinates_x\":\"14\",\"coordinates_y\":\"B\",\"type_seat_id\":\"1\"},{\"coordinates_x\":\"1\",\"coordinates_y\":\"C\",\"type_seat_id\":\"1\"},{\"coordinates_x\":\"2\",\"coordinates_y\":\"C\",\"type_seat_id\":\"1\"},{\"coordinates_x\":\"3\",\"coordinates_y\":\"C\",\"type_seat_id\":\"1\"},{\"coordinates_x\":\"4\",\"coordinates_y\":\"C\",\"type_seat_id\":\"1\"},{\"coordinates_x\":\"5\",\"coordinates_y\":\"C\",\"type_seat_id\":\"1\"},{\"coordinates_x\":\"6\",\"coordinates_y\":\"C\",\"type_seat_id\":\"1\"},{\"coordinates_x\":\"7\",\"coordinates_y\":\"C\",\"type_seat_id\":\"1\"},{\"coordinates_x\":\"8\",\"coordinates_y\":\"C\",\"type_seat_id\":\"1\"},{\"coordinates_x\":\"9\",\"coordinates_y\":\"C\",\"type_seat_id\":\"1\"},{\"coordinates_x\":\"10\",\"coordinates_y\":\"C\",\"type_seat_id\":\"1\"},{\"coordinates_x\":\"11\",\"coordinates_y\":\"C\",\"type_seat_id\":\"1\"},{\"coordinates_x\":\"12\",\"coordinates_y\":\"C\",\"type_seat_id\":\"1\"},{\"coordinates_x\":\"13\",\"coordinates_y\":\"C\",\"type_seat_id\":\"1\"},{\"coordinates_x\":\"14\",\"coordinates_y\":\"C\",\"type_seat_id\":\"1\"},{\"coordinates_x\":\"1\",\"coordinates_y\":\"D\",\"type_seat_id\":\"1\"},{\"coordinates_x\":\"2\",\"coordinates_y\":\"D\",\"type_seat_id\":\"1\"},{\"coordinates_x\":\"3\",\"coordinates_y\":\"D\",\"type_seat_id\":\"1\"},{\"coordinates_x\":\"4\",\"coordinates_y\":\"D\",\"type_seat_id\":\"1\"},{\"coordinates_x\":\"5\",\"coordinates_y\":\"D\",\"type_seat_id\":\"1\"},{\"coordinates_x\":\"6\",\"coordinates_y\":\"D\",\"type_seat_id\":\"1\"},{\"coordinates_x\":\"7\",\"coordinates_y\":\"D\",\"type_seat_id\":\"1\"},{\"coordinates_x\":\"8\",\"coordinates_y\":\"D\",\"type_seat_id\":\"1\"},{\"coordinates_x\":\"9\",\"coordinates_y\":\"D\",\"type_seat_id\":\"1\"},{\"coordinates_x\":\"10\",\"coordinates_y\":\"D\",\"type_seat_id\":\"1\"},{\"coordinates_x\":\"11\",\"coordinates_y\":\"D\",\"type_seat_id\":\"1\"},{\"coordinates_x\":\"12\",\"coordinates_y\":\"D\",\"type_seat_id\":\"1\"},{\"coordinates_x\":\"13\",\"coordinates_y\":\"D\",\"type_seat_id\":\"1\"},{\"coordinates_x\":\"14\",\"coordinates_y\":\"D\",\"type_seat_id\":\"1\"},{\"coordinates_x\":\"1\",\"coordinates_y\":\"F\",\"type_seat_id\":\"2\"},{\"coordinates_x\":\"2\",\"coordinates_y\":\"F\",\"type_seat_id\":\"2\"},{\"coordinates_x\":\"3\",\"coordinates_y\":\"F\",\"type_seat_id\":\"2\"},{\"coordinates_x\":\"4\",\"coordinates_y\":\"F\",\"type_seat_id\":\"2\"},{\"coordinates_x\":\"5\",\"coordinates_y\":\"F\",\"type_seat_id\":\"2\"},{\"coordinates_x\":\"6\",\"coordinates_y\":\"F\",\"type_seat_id\":\"2\"},{\"coordinates_x\":\"7\",\"coordinates_y\":\"F\",\"type_seat_id\":\"2\"},{\"coordinates_x\":\"8\",\"coordinates_y\":\"F\",\"type_seat_id\":\"2\"},{\"coordinates_x\":\"9\",\"coordinates_y\":\"F\",\"type_seat_id\":\"2\"},{\"coordinates_x\":\"10\",\"coordinates_y\":\"F\",\"type_seat_id\":\"2\"},{\"coordinates_x\":\"11\",\"coordinates_y\":\"F\",\"type_seat_id\":\"2\"},{\"coordinates_x\":\"12\",\"coordinates_y\":\"F\",\"type_seat_id\":\"2\"},{\"coordinates_x\":\"13\",\"coordinates_y\":\"F\",\"type_seat_id\":\"2\"},{\"coordinates_x\":\"14\",\"coordinates_y\":\"F\",\"type_seat_id\":\"2\"},{\"coordinates_x\":\"1\",\"coordinates_y\":\"G\",\"type_seat_id\":\"2\"},{\"coordinates_x\":\"2\",\"coordinates_y\":\"G\",\"type_seat_id\":\"2\"},{\"coordinates_x\":\"3\",\"coordinates_y\":\"G\",\"type_seat_id\":\"2\"},{\"coordinates_x\":\"4\",\"coordinates_y\":\"G\",\"type_seat_id\":\"2\"},{\"coordinates_x\":\"5\",\"coordinates_y\":\"G\",\"type_seat_id\":\"2\"},{\"coordinates_x\":\"6\",\"coordinates_y\":\"G\",\"type_seat_id\":\"2\"},{\"coordinates_x\":\"7\",\"coordinates_y\":\"G\",\"type_seat_id\":\"2\"},{\"coordinates_x\":\"8\",\"coordinates_y\":\"G\",\"type_seat_id\":\"2\"},{\"coordinates_x\":\"9\",\"coordinates_y\":\"G\",\"type_seat_id\":\"2\"},{\"coordinates_x\":\"10\",\"coordinates_y\":\"G\",\"type_seat_id\":\"2\"},{\"coordinates_x\":\"11\",\"coordinates_y\":\"G\",\"type_seat_id\":\"2\"},{\"coordinates_x\":\"12\",\"coordinates_y\":\"G\",\"type_seat_id\":\"2\"},{\"coordinates_x\":\"13\",\"coordinates_y\":\"G\",\"type_seat_id\":\"2\"},{\"coordinates_x\":\"14\",\"coordinates_y\":\"G\",\"type_seat_id\":\"2\"},{\"coordinates_x\":\"1\",\"coordinates_y\":\"H\",\"type_seat_id\":\"2\"},{\"coordinates_x\":\"2\",\"coordinates_y\":\"H\",\"type_seat_id\":\"2\"},{\"coordinates_x\":\"3\",\"coordinates_y\":\"H\",\"type_seat_id\":\"2\"},{\"coordinates_x\":\"4\",\"coordinates_y\":\"H\",\"type_seat_id\":\"2\"},{\"coordinates_x\":\"5\",\"coordinates_y\":\"H\",\"type_seat_id\":\"2\"},{\"coordinates_x\":\"6\",\"coordinates_y\":\"H\",\"type_seat_id\":\"2\"},{\"coordinates_x\":\"7\",\"coordinates_y\":\"H\",\"type_seat_id\":\"2\"},{\"coordinates_x\":\"8\",\"coordinates_y\":\"H\",\"type_seat_id\":\"2\"},{\"coordinates_x\":\"9\",\"coordinates_y\":\"H\",\"type_seat_id\":\"2\"},{\"coordinates_x\":\"10\",\"coordinates_y\":\"H\",\"type_seat_id\":\"2\"},{\"coordinates_x\":\"11\",\"coordinates_y\":\"H\",\"type_seat_id\":\"2\"},{\"coordinates_x\":\"12\",\"coordinates_y\":\"H\",\"type_seat_id\":\"2\"},{\"coordinates_x\":\"13\",\"coordinates_y\":\"H\",\"type_seat_id\":\"2\"},{\"coordinates_x\":\"14\",\"coordinates_y\":\"H\",\"type_seat_id\":\"2\"},{\"coordinates_x\":\"1\",\"coordinates_y\":\"I\",\"type_seat_id\":\"2\"},{\"coordinates_x\":\"2\",\"coordinates_y\":\"I\",\"type_seat_id\":\"2\"},{\"coordinates_x\":\"3\",\"coordinates_y\":\"I\",\"type_seat_id\":\"2\"},{\"coordinates_x\":\"4\",\"coordinates_y\":\"I\",\"type_seat_id\":\"2\"},{\"coordinates_x\":\"5\",\"coordinates_y\":\"I\",\"type_seat_id\":\"2\"},{\"coordinates_x\":\"6\",\"coordinates_y\":\"I\",\"type_seat_id\":\"2\"},{\"coordinates_x\":\"7\",\"coordinates_y\":\"I\",\"type_seat_id\":\"2\"},{\"coordinates_x\":\"8\",\"coordinates_y\":\"I\",\"type_seat_id\":\"2\"},{\"coordinates_x\":\"9\",\"coordinates_y\":\"I\",\"type_seat_id\":\"2\"},{\"coordinates_x\":\"10\",\"coordinates_y\":\"I\",\"type_seat_id\":\"2\"},{\"coordinates_x\":\"11\",\"coordinates_y\":\"I\",\"type_seat_id\":\"2\"},{\"coordinates_x\":\"12\",\"coordinates_y\":\"I\",\"type_seat_id\":\"2\"},{\"coordinates_x\":\"13\",\"coordinates_y\":\"I\",\"type_seat_id\":\"2\"},{\"coordinates_x\":\"14\",\"coordinates_y\":\"I\",\"type_seat_id\":\"2\"},{\"coordinates_x\":\"1\",\"coordinates_y\":\"J\",\"type_seat_id\":\"2\"},{\"coordinates_x\":\"2\",\"coordinates_y\":\"J\",\"type_seat_id\":\"2\"},{\"coordinates_x\":\"3\",\"coordinates_y\":\"J\",\"type_seat_id\":\"2\"},{\"coordinates_x\":\"4\",\"coordinates_y\":\"J\",\"type_seat_id\":\"2\"},{\"coordinates_x\":\"5\",\"coordinates_y\":\"J\",\"type_seat_id\":\"2\"},{\"coordinates_x\":\"6\",\"coordinates_y\":\"J\",\"type_seat_id\":\"2\"},{\"coordinates_x\":\"7\",\"coordinates_y\":\"J\",\"type_seat_id\":\"2\"},{\"coordinates_x\":\"8\",\"coordinates_y\":\"J\",\"type_seat_id\":\"2\"},{\"coordinates_x\":\"9\",\"coordinates_y\":\"J\",\"type_seat_id\":\"2\"},{\"coordinates_x\":\"10\",\"coordinates_y\":\"J\",\"type_seat_id\":\"2\"},{\"coordinates_x\":\"11\",\"coordinates_y\":\"J\",\"type_seat_id\":\"2\"},{\"coordinates_x\":\"12\",\"coordinates_y\":\"J\",\"type_seat_id\":\"2\"},{\"coordinates_x\":\"13\",\"coordinates_y\":\"J\",\"type_seat_id\":\"2\"},{\"coordinates_x\":\"14\",\"coordinates_y\":\"J\",\"type_seat_id\":\"2\"},{\"coordinates_x\":\"1\",\"coordinates_y\":\"K\",\"type_seat_id\":\"2\"},{\"coordinates_x\":\"2\",\"coordinates_y\":\"K\",\"type_seat_id\":\"2\"},{\"coordinates_x\":\"3\",\"coordinates_y\":\"K\",\"type_seat_id\":\"2\"},{\"coordinates_x\":\"4\",\"coordinates_y\":\"K\",\"type_seat_id\":\"2\"},{\"coordinates_x\":\"5\",\"coordinates_y\":\"K\",\"type_seat_id\":\"2\"},{\"coordinates_x\":\"6\",\"coordinates_y\":\"K\",\"type_seat_id\":\"2\"},{\"coordinates_x\":\"7\",\"coordinates_y\":\"K\",\"type_seat_id\":\"2\"},{\"coordinates_x\":\"8\",\"coordinates_y\":\"K\",\"type_seat_id\":\"2\"},{\"coordinates_x\":\"9\",\"coordinates_y\":\"K\",\"type_seat_id\":\"2\"},{\"coordinates_x\":\"10\",\"coordinates_y\":\"K\",\"type_seat_id\":\"2\"},{\"coordinates_x\":\"11\",\"coordinates_y\":\"K\",\"type_seat_id\":\"2\"},{\"coordinates_x\":\"12\",\"coordinates_y\":\"K\",\"type_seat_id\":\"2\"},{\"coordinates_x\":\"13\",\"coordinates_y\":\"K\",\"type_seat_id\":\"2\"},{\"coordinates_x\":\"14\",\"coordinates_y\":\"K\",\"type_seat_id\":\"2\"},{\"coordinates_x\":\"1\",\"coordinates_y\":\"L\",\"type_seat_id\":\"2\"},{\"coordinates_x\":\"2\",\"coordinates_y\":\"L\",\"type_seat_id\":\"2\"},{\"coordinates_x\":\"3\",\"coordinates_y\":\"L\",\"type_seat_id\":\"2\"},{\"coordinates_x\":\"4\",\"coordinates_y\":\"L\",\"type_seat_id\":\"2\"},{\"coordinates_x\":\"5\",\"coordinates_y\":\"L\",\"type_seat_id\":\"2\"},{\"coordinates_x\":\"6\",\"coordinates_y\":\"L\",\"type_seat_id\":\"2\"},{\"coordinates_x\":\"7\",\"coordinates_y\":\"L\",\"type_seat_id\":\"2\"},{\"coordinates_x\":\"8\",\"coordinates_y\":\"L\",\"type_seat_id\":\"2\"},{\"coordinates_x\":\"9\",\"coordinates_y\":\"L\",\"type_seat_id\":\"2\"},{\"coordinates_x\":\"10\",\"coordinates_y\":\"L\",\"type_seat_id\":\"2\"},{\"coordinates_x\":\"11\",\"coordinates_y\":\"L\",\"type_seat_id\":\"2\"},{\"coordinates_x\":\"12\",\"coordinates_y\":\"L\",\"type_seat_id\":\"2\"},{\"coordinates_x\":\"13\",\"coordinates_y\":\"L\",\"type_seat_id\":\"2\"},{\"coordinates_x\":\"14\",\"coordinates_y\":\"L\",\"type_seat_id\":\"2\"},{\"coordinates_x\":\"1\",\"coordinates_y\":\"N\",\"type_seat_id\":\"3\"},{\"coordinates_x\":\"4\",\"coordinates_y\":\"N\",\"type_seat_id\":\"3\"},{\"coordinates_x\":\"7\",\"coordinates_y\":\"N\",\"type_seat_id\":\"3\"},{\"coordinates_x\":\"10\",\"coordinates_y\":\"N\",\"type_seat_id\":\"3\"},{\"coordinates_x\":\"13\",\"coordinates_y\":\"N\",\"type_seat_id\":\"3\"}]";
        return $structure;
    }
}
