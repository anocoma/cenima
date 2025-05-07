@extends('admin.layouts.master')

@section('title')
    Thêm mới phòng chiếu
@endsection

@section('content')
    {{-- @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif --}}
    <form action="{{ route('admin.rooms.store') }}" method="post" enctype="multipart/form-data">
        @csrf

        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Thêm mới phòng chiếu</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.rooms.index') }}">Danh sách</a></li>
                            <li class="breadcrumb-item active">Thêm mới</li>
                        </ol>
                    </div>

                </div>
            </div>
        </div>

        <!-- thông tin -->
        <div class="row">
            <div class="col-md-12">
                @if (session()->has('error'))
                    <div class="alert alert-danger m-3">
                        {{ session()->get('error') }}
                    </div>
                @endif
            </div>
            <div class="col-lg-9 mb-3">
                <div class="card card-left">
                    <div class="card-header align-items-center d-flex">
                        <h4 class="card-title mb-0 flex-grow-1">Thông tin phòng chiếu</h4>
                    </div><!-- end card header -->
                    <div class="card-body">
                        <div class="live-preview">
                            <div class="row gy-4">
                                <div class="col-md-12">
                                    <div class="row ">
                                        <div class="col-md-8 mb-3">
                                            <span class='text-danger'>*</span>
                                            <label for="name" class="form-label ">Tên phòng chiếu:</label>
                                            <input type="text" class="form-control" id="name" name="name"
                                                value="{{ old('name') }}" placeholder="Poly Cinema 01">
                                            @error('name')
                                                <div class='mt-1'>
                                                    <span class="text-danger">{{ $message }}</span>
                                                </div>
                                            @enderror
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <span class='text-danger'>*</span>
                                            <label for="surcharge" class="form-label ">Loại phòng chiếu:</label>
                                            <select name="type_room_id" id="" class="form-select">

                                                @foreach ($typeRooms as $id => $name)
                                                    <option value="{{ $id }}" @selected(old('type_room_id') == $id)>
                                                        {{ $name }}</option>
                                                @endforeach
                                            </select>
                                            @error('type_room_id')
                                                <div class='mt-1'>
                                                    <span class="text-danger">{{ $message }}</span>
                                                </div>
                                            @enderror
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <span class='text-danger'>*</span>
                                            <label for="branch" class="form-label">Chi nhánh:</label>
                                            <select name="branch_id" id="branch" class="form-select">
                                                <option value="">Chọn chi nhánh</option>
                                                @foreach ($branches as $id => $name)
                                                    <option value="{{ $id }}" @selected($id == old('branch_id'))>
                                                        {{ $name }}</option>
                                                @endforeach
                                            </select>
                                            @error('branch_id')
                                                <div class='mt-1'>
                                                    <span class="text-danger">{{ $message }}</span>
                                                </div>
                                            @enderror
                                        </div>

                                        <div class="col-md-4 mb-3">
                                            <span class='text-danger'>*</span>
                                            <label for="cinema" class="form-label">Rạp chiếu:</label>
                                            <select name="cinema_id" id="cinema" class="form-select">
                                                <option value="">Chọn rạp chiếu</option>

                                            </select>
                                            @error('cinema_id')
                                                <div class='mt-1'>
                                                    <span class="text-danger">{{ $message }}</span>
                                                </div>
                                            @enderror
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <span class='text-danger'>*</span>
                                            <label for="surcharge" class="form-label ">Ma trận ghế:</label>
                                            <select name="matrix_id" id="" class="form-select">
                                                @foreach (App\Models\SeatTemplate::MATRIXS as $matrix)
                                                    <option value="{{ $matrix['id'] }}" @selected(old('matrix_id') == $matrix['id'])>
                                                        {{ $matrix['name'] }}</option>
                                                @endforeach
                                            </select>
                                            @error('matrix_id')
                                                <div class='mt-1'>
                                                    <span class="text-danger">{{ $message }}</span>
                                                </div>
                                            @enderror
                                        </div>


                                    </div>
                                </div>
                            </div>

                            <!--end row-->
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="mb-2">
                                            <label class="form-check-label" for="is_active">Is Active</label>
                                            <div class="form-check form-switch form-switch-default">
                                                <input class="form-check-input" type="checkbox" role=""
                                                    name="is_active" checked>
                                            </div>
                                        </div>

                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>


                </div>
            </div>

        </div>


        <div class="row">
            <div class="col-lg-9">
                <div class="card card-left">
                    <div class="card-header align-items-center d-flex">
                        <h4 class="card-title mb-0 flex-grow-1">Sơ đồ ghế</h4>
                    </div><!-- end card header -->
                    <div class="card-body mb-4 ">



                        @php

                            $maxCol = App\Models\Room::MAX_COL;
                            $maxRow = App\Models\Room::MAX_ROW;
                            $rowSeatRegular = App\Models\Room::ROW_SEAT_REGULAR;
                        @endphp
                        <table class="table-chart-chair table-none align-middle mx-auto text-center">
                            <thead>
                                <tr>
                                    <th></th> <!-- Ô trống góc trên bên trái -->
                                    @for ($col = 0; $col < $maxCol; $col++)
                                        <th class="box-item">
                                            {{-- thao tác 1 loạt trên 1 cột --}}
                                            {{-- <i class="fas fa-edit primary"></i> --}}
                                        </th>
                                    @endfor
                                    <th></th> <!-- Ô trống góc trên bên trái -->
                                </tr>
                            </thead>
                            <tbody>
                                @for ($row = 0; $row < $maxRow; $row++)
                                    <tr>
                                        {{-- cột hàng ghế A,B,C --}}
                                        <td class="box-item">
                                            {{ chr(65 + $row) }}
                                        </td>
                                        @for ($col = 0; $col < $maxCol; $col++)
                                            @if ($row < $rowSeatRegular)
                                                {{-- bắt đầu hàng ghế thường --}}
                                                <td class="box-item-seat border-1 light-orange">
                                                    <div class="box-item-seat-selected">
                                                        <img src="{{ asset('svg/seat-regular.svg') }}" class='seat'
                                                            width="100%">
                                                        <input type="hidden" name="seatJsons[]"
                                                            value='{"coordinates_x": {{ $col + 1 }}, "coordinates_y": "{{ chr(65 + $row) }}"}'>
                                                    </div>
                                                </td>
                                                {{-- kết thúchàng ghế thường --}}
                                            @else
                                                {{-- bắt đầu hàng ghế vip --}}
                                                <td class="box-item-seat border-1 light-blue">
                                                    <div class="box-item-seat-selected">
                                                        <img src="{{ asset('svg/seat-vip.svg') }}" class='seat'
                                                            width="100%">
                                                        <input type="hidden" name="seatJsons[]"
                                                            value='{"coordinates_x": {{ $col + 1 }}, "coordinates_y": "{{ chr(65 + $row) }}"}'>
                                                    </div>
                                                </td>
                                                {{-- kết thúc hàng ghế vip --}}
                                            @endif
                                        @endfor
                                        <td class="box-item">
                                            {{-- thao tác 1 loạt trên 1 hàng --}}
                                            <i class="fas fa-edit primary"></i>
                                        </td>
                                    </tr>
                                @endfor

                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="card ">
                    <div class="card-header align-items-center d-flex">
                        <h4 class="card-title mb-0 flex-grow-1">Chú thích</h4>
                    </div><!-- end card header -->
                    <div class="card-body ">

                        <div class="row mb-3">
                            <div class="col-lg-8 col-md-8 col-8">
                                <label class="form-label">Hàng ghế thường</label>
                            </div>
                            <div class="col-lg-4 col-md-4 col-4 ">
                                <div class='box-item border light-orange'>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-8 col-md-8 col-8">
                                <label class="form-label">Hàng ghế vip</label>
                            </div>
                            <div class="col-lg-4 col-md-4 col-4">
                                <div class='box-item border  light-blue'>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-8 col-md-8 col-8">
                                <label class="form-label">Hàng ghế đôi</label>
                            </div>
                            <div class="col-lg-4 col-md-4 col-4">
                                <div class='box-item border  light-pink'>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>



        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header align-items-center d-flex">
                        <a href="{{ route('admin.rooms.index') }}" class="btn btn-info">Danh sách</a>
                        <button type="submit" class="btn btn-primary mx-1">Thêm mới</button>
                    </div>
                </div>
            </div>
            <!--end col-->
        </div>
    </form>
@endsection

@section('style-libs')
    <link rel="stylesheet" href="{{ asset('theme/admin/assets/css/mainstyle.css') }}">
@endsection


@section('script-libs')
<script>
     document.querySelectorAll('.box-item-seat').forEach(function(seat) {
            // Lưu trữ nội dung ban đầu của .box-item-seat-selected
            let originalContent = seat.querySelector('.box-item-seat-selected').innerHTML;

            seat.addEventListener('click', function() {
                let seatSelected = seat.querySelector('.box-item-seat-selected');

                // Kiểm tra nếu div đang chứa nội dung ban đầu
                if (seatSelected.innerHTML.trim() === originalContent.trim()) {
                    // Nếu là nội dung ban đầu, thay đổi thành hình ảnh mới
                    seatSelected.innerHTML =
                        `<img src="{{ asset('svg/seat-add.svg') }}" class='seat' width="60%" >`;
                } else {
                    // Nếu không phải nội dung ban đầu, khôi phục lại nội dung ban đầu
                    seatSelected.innerHTML = originalContent;
                }
            });
        });
</script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Lấy giá trị branchId và cinemaId từ Laravel
            var selectedBranchId = "{{ old('branch_id', '') }}";
            var selectedCinemaId = "{{ old('cinema_id', '') }}";

            // Xử lý sự kiện thay đổi chi nhánh
            $('#branch').on('change', function() {
                var branchId = $(this).val();
                var cinemaSelect = $('#cinema');
                cinemaSelect.empty();
                cinemaSelect.append('<option value="">Chọn rạp chiếu</option>');

                if (branchId) {
                    $.ajax({
                        url: "{{ env('APP_URL') }}/api/cinemas/" + branchId,
                        method: 'GET',
                        success: function(data) {
                            $.each(data, function(index, cinema) {
                                cinemaSelect.append('<option value="' + cinema.id +
                                    '">' + cinema.name + '</option>');
                            });

                            // Chọn lại cinema nếu có selectedCinemaId
                            if (selectedCinemaId) {
                                cinemaSelect.val(selectedCinemaId);
                                selectedCinemaId = false;
                            }
                        }
                    });
                }
            });

            // Nếu có selectedBranchId thì tự động kích hoạt thay đổi chi nhánh để load danh sách cinema
            if (selectedBranchId) {
                $('#branch').val(selectedBranchId).trigger('change');

            }
        });


    </script>
@endsection
