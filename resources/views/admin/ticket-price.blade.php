@extends('admin.layouts.master')

@section('title')
    Quản lý giá vé
@endsection

@section('style-libs')
    <!--datatable css-->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" />
    <!--datatable responsive css-->
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" />

    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css">
@endsection



@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Quản lý giá vé </h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Giá vé</a></li>
                        <li class="breadcrumb-item active">Danh sách</li>
                    </ol>
                </div>

            </div>
        </div>
    </div>
    <!-- end page title -->

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="row">
        <div class="col-md-6">
            <div class="card">

                <div class="card-header d-flex justify-content-between">
                    <h5 class="card-title mb-0"> Giá vé theo Ghế - Loại phòng</h5>
                </div>


                @if (session()->has('success'))
                    <div class="alert alert-success m-3">
                        {{ session()->get('success') }}
                    </div>
                @endif

                <div class="card-body pt-0">

                    <div class="card-body tab-content w-75 mx-auto ">
                        <div class="tab-pane active " id="priceDefault" role="tabpanel">
                            {{-- vl quả route, chịu luôn --}}
                            <form action="{{ route('admin.ticket-update') }}" method="POST">
                                @csrf
                                <table class="table table-bordered rounded align-middle">
                                    <thead>
                                        <tr class="table-light">
                                            <th colspan='2' class="text-center">GIÁ THEO GHẾ <span
                                                    class="text-muted">(VNĐ)</span></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($typeSeats as $typeSeat)
                                            <tr>
                                                <td>{{ $typeSeat->name }}</td>
                                                <td>
                                                    <input type="number" name="prices[{{ $typeSeat->id }}]" id=""
                                                        class="form-control" value="{{ $typeSeat->price }}">
                                                    @error('price')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>

                                    <thead>
                                        <tr class="table-light">
                                            <th colspan='2' class="text-center">PHỤ THU THEO LOẠI PHÒNG <span
                                                    class="text-muted">(VNĐ)</span></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($typeRooms as $typeRoom)
                                            <tr>
                                                <td>{{ $typeRoom->name }}</td>
                                                <td><input type="number" name="surcharges[{{ $typeRoom->id }}]"
                                                        id="" class="form-control"
                                                        value="{{ $typeRoom->surcharge }}">
                                                    @error('surcharge')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>

                                </table>

                                @can('Sửa giá')
                                    <div class='text-end'>
                                        <button class='btn btn-primary'>Cập nhật</button>
                                    </div>
                                @endcan
                            </form>
                        </div>


                    </div>

                </div>
            </div>
        </div>
        <div class="col-md-6">

            <div class="row">
                <div class="card">
                    <div class="row">
                        {{-- @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif --}}
                        <div class="card-header d-flex justify-content-between">

                            <div class="col-md-6">
                                <h5 class="card-title mb-0">Phụ thu theo rạp </h5>
                            </div>

                            <div class="col-md-6 d-flex align-items-center">
                                <form action="{{ route('admin.ticket-price') }}" method="get"
                                    class="d-flex  align-items-center " align='right'>
                                    {{-- @csrf --}}
                                    <select name="branch_id" id="branch" class="form-select">
                                        <option value="">Lọc theo Chi nhánh</option>
                                        @foreach ($branches as $branch)
                                            <option value="{{ $branch->id }}">
                                                {{-- {{ request('branch_id') == $branch->id ? 'selected' : '' }} --}}
                                                {{ $branch->name }}</option>
                                        @endforeach
                                    </select>
                                    <button class="btn btn-primary ms-2" type="submit">Lọc</button>
                                </form>
                            </div>
                        </div>
                    </div>

                    @if (session()->has('success'))
                        <div class="alert alert-success m-3">
                            {{ session()->get('success') }}
                        </div>
                    @endif

                    <form action="{{ route('admin.ticket-update') }}" method="POST">
                        @csrf
                        <div class="card-body pt-0" style="max-height: 474px; overflow-y: auto;">
                            <table id="example" class="table table-bordered dt-responsive nowrap align-middle w-100">
                                <thead class='table-light'>
                                    <tr>
                                        <th>Tên rạp</th>
                                        <th>Giá <span class="text-muted">(VNĐ)</span> </th>
                                        {{-- <th>Thao tác</th> --}}
                                    </tr>
                                </thead>

                                <tbody>

                                    @foreach ($cinemasPaginate as $cinema)
                                        <tr>
                                            <td>{{ $cinema->name }}</td>
                                            <td><input type="number" class="form-control"
                                                    name="surchargesCinema[{{ $cinema->id }}]" id="{{ $cinema->id }}"
                                                    value="{{ $cinema->surcharge }}"></td>
                                        </tr>
                                    @endforeach

                                </tbody>
                            </table>
                        </div>
                        @can('Sửa giá')
                            <div class='text-end'>
                                <button class='btn btn-primary m-3'>Cập nhật</button>
                            </div>
                        @endcan
                    </form>
                </div>
            </div>
            {{-- <div class="row">
                <div class="card">
                    <div class="row">
                        <div class="card-header d-flex justify-content-between">

                            <div class="col-md-6">
                                <h5 class="card-title mb-0">Phụ thu theo phim</h5>
                            </div>
                            <div class="col-md-6 d-flex align-items-center">
                                <form action="" method="post" class="d-flex  align-items-center " align='right'>
                                    <input type="text" class="form-control" placeholder="Tìm kiếm theo phim...">
                                    <button class="btn btn-primary ms-2" type="submit">Tìm </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    @if (session()->has('success'))
                        <div class="alert alert-success m-3">
                            {{ session()->get('success') }}
                        </div>
                    @endif
                    <form action="" method="post">
                        <div class="card-body pt-0" style="max-height: 300px; overflow-y: auto;">

                            <table id="example"
                                class="table table-bordered dt-responsive nowrap table-striped align-middle"
                                style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Tên phim</th>
                                        <th>Phụ thu</th>

                                    </tr>
                                </thead>
                                <tbody>

                                    @foreach ($movies as $movie)
                                        <tr>
                                            <td>{{ $movie->name }}</td>
                                            <td><input type="text" class="form-control" id="{{ $movie->id }}"
                                                    placeholder="{{ number_format($movie->surcharge) }}đ"></td>
                                        </tr>
                                    @endforeach


                                </tbody>

                            </table>


                        </div>
                        <div class="row">
                            <div class="col-md-12 my-3 pr-4" align='right'>
                                <button title="xem" class="btn btn-success btn-sm" type="button">
                                    Cập nhật
                                </button>
                            </div>
                        </div>

                    </form>
                </div>
            </div> --}}

        </div>
    </div>
@endsection


@section('script-libs')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
@endsection
