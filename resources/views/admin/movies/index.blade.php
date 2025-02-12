@extends('admin.layouts.master')

@section('title')
    Quản lý phim
@endsection

@section('style-libs')
    <!--datatable css-->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" />
    <!--datatable responsive css-->
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" />

    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css">
    <style>
        .movie-name {
            color: #434f89;
            font-weight: 600;
            letter-spacing: -1px !important;
            font-family: Oswald !important;
        }

        .content-movie {
            letter-spacing: -0.2px !important;
        }
    </style>
@endsection



@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Quản lý phim</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Phim</a></li>
                        <li class="breadcrumb-item active">Danh sách</li>
                    </ol>
                </div>

            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h5 class="card-title mb-0">Danh sách phim</h5>
                    @can('Thêm phim')
                        <a href="{{ route('admin.movies.create') }}" class="btn btn-primary mb-3 ">Thêm mới</a>
                    @endcan
                </div>
                <div class="card-body">

                    <ul class="nav nav-tabs nav-tabs-custom nav-success mb-3" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link  All py-3 {{ session('movies.selected_tab') === 'all' ? 'active' : '' }}"
                                data-bs-toggle="tab" href="#allMovie" role="tab" aria-selected="true"
                                data-tab-key='all'>
                                Tất cả
                                <span class="badge bg-dark align-middle ms-1">{{ $movies->count() }}</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link py-3 isPublish {{ session('movies.selected_tab') === 'publish' ? 'active' : '' }}"
                                data-bs-toggle="tab" href="#isPublish" role="tab" aria-selected="false"
                                data-tab-key='publish'>
                                Đã xuất bản
                                <span
                                    class="badge bg-success align-middle ms-1">{{ $movies->where('is_publish', true)->count() }}</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link py-3 isDraft {{ session('movies.selected_tab') === 'draft' ? 'active' : '' }}"
                                data-bs-toggle="tab" href="#isDraft" role="tab" aria-selected="false"
                                data-tab-key='draft'>
                                Bản nháp<span
                                    class="badge bg-warning align-middle ms-1">{{ $movies->where('is_publish', false)->count() }}</span>
                            </a>
                        </li>
                    </ul>


                    <div class="card-body tab-content ">
                        {{-- Tất cả ok rồi --}}
                        <div class="tab-pane {{ session('movies.selected_tab') === 'all' ? 'active' : '' }} " id="allMovie" role="tabpanel">
                            <table class="table table-bordered dt-responsive nowrap align-middle w-100" id="tableAllMovie">
                                <thead class='table-light'>
                                    <tr>
                                        <th>#</th>
                                        <th class="text-center">Hình ảnh</th>
                                        <th>Thông tin phim</th>
                                        <th>Hoạt động</th>
                                        <th>Nổi bật</th>
                                        <th>Trạng thái</th>
                                        <th>Chức năng</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($movies as $movie)
                                        <tr>
                                            <td>{{ $movie->id }}</td>
                                            <td class="text-center">

                                                @php
                                                    $url = $movie->img_thumbnail;

                                                    if (!\Str::contains($url, 'http')) {
                                                        $url = Storage::url($url);
                                                    }

                                                @endphp
                                                @if (!empty($movie->img_thumbnail))
                                                    <img src="{{ $url }}" alt="" width="130px">
                                                @else
                                                    No image !
                                                @endif

                                            </td>
                                            <td>
                                                <h4 class="movie-name">{{ $movie->name }}</h4>
                                                <ul class="nav nav-sm flex-column content-movie">
                                                    <li class="nav-item mb-1"><span class="fw-semibold">Đạo diễn:</span>
                                                        {{ $movie->director }}</li>
                                                    <li class="nav-item mb-1"><span class="fw-semibold">Diễn viên:</span>
                                                        {{ $movie->cast }}</li>
                                                    <li class="nav-item mb-1"><span class="fw-semibold">Thể loại:</span>
                                                        {{ $movie->category }}</li>
                                                    <li class="nav-item mb-1"><span class="fw-semibold">Ngày khởi
                                                            chiếu:</span>
                                                        {{ \Carbon\Carbon::parse($movie->release_date)->format('d/m/Y') }}
                                                    </li>
                                                    <li class="nav-item mb-1"><span class="fw-semibold">Ngày kết
                                                            thúc:</span>
                                                        {{ \Carbon\Carbon::parse($movie->end_date)->format('d/m/Y') }}</li>
                                                    <li class="nav-item mb-1"><span class="fw-semibold">Phân loại:</span>
                                                        {{ $movie->rating }}</li>
                                                    <li class="nav-item mb-1">
                                                        <span class="fw-semibold">Phiên bản:</span>
                                                        @foreach ($movie->movieVersions as $version)
                                                            <span class="badge bg-info">{{ $version->name }}</span>
                                                        @endforeach
                                                    </li>
                                                    <li class="nav-item mb-1"><span class="fw-semibold">Code Youtube:</span>
                                                        <input type="text" disabled
                                                            value="{{ $movie->trailer_url }}2121">
                                                    </li>
                                                </ul>
                                            </td>
                                            <td>
                                                @can('Sửa phim')
                                                    <div class="form-check form-switch form-switch-success">
                                                        <input class="form-check-input switch-is-active change-is-active"
                                                            name="is_active" type="checkbox" role="switch"
                                                            data-id="{{ $movie->id }}" @checked($movie->is_active)
                                                            onclick="return confirm('Bạn có chắc muốn thay đổi ?')">
                                                    </div>
                                                @else
                                                    <div class="form-check form-switch form-switch-success">
                                                        <input class="form-check-input switch-is-active change-is-active"
                                                            name="is_active" disabled readonly type="checkbox" role="switch"
                                                            data-id="{{ $movie->id }}" @checked($movie->is_active)
                                                            onclick="return confirm('Bạn có chắc muốn thay đổi ?')">
                                                    </div>
                                                @endcan
                                            </td>

                                            <td>
                                                @can('Sửa phim')
                                                    <div class="form-check form-switch form-switch-danger">
                                                        <input class="form-check-input switch-is-active change-is-hot"
                                                            name="is_hot" type="checkbox" role="switch"
                                                            data-id="{{ $movie->id }}" @checked($movie->is_hot)
                                                            onclick="return confirm('Bạn có chắc muốn thay đổi ?')">
                                                    </div>
                                                @else
                                                    <div class="form-check form-switch form-switch-danger">
                                                        <input class="form-check-input switch-is-active change-is-hot"
                                                            name="is_hot" disabled readonly type="checkbox" role="switch"
                                                            data-id="{{ $movie->id }}" @checked($movie->is_hot)
                                                            onclick="return confirm('Bạn có chắc muốn thay đổi ?')">
                                                    </div>
                                                @endcan
                                            </td>
                                            <td>
                                                {!! $movie->is_publish == 1
                                                    ? '<span class="badge bg-success-subtle text-success">Đã xuất bản</span>'
                                                    : '<span class="badge bg-danger-subtle text-danger">Bản nháp</span>' !!}
                                            </td>
                                            <td>
                                                <div class='d-flex'>
                                                    @can('Xem chi tiết phim')
                                                        <a href="{{ route('admin.movies.show', $movie) }}">
                                                            <button title="xem" class="btn btn-success btn-sm "
                                                                type="button"><i class="fas fa-eye"></i></button></a>
                                                    @endcan
                                                    @can('Sửa phim')
                                                        <a href="{{ route('admin.movies.edit', $movie) }}">
                                                            <button title="sủa" class="btn btn-warning btn-sm mx-1"
                                                                type="button"><i class="fas fa-edit"></i></button>
                                                        </a>
                                                    @endcan
                                                    {{-- @can('Xóa phim')
                                                        @if (!$movie->is_publish || $movie->showtimes()->doesntExist())
                                                            <form action="{{ route('admin.movies.destroy', $movie) }}"
                                                                method="POST" class="d-inline-block">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-danger btn-sm"
                                                                    onclick="return confirm('Bạn có muốn xóa không')">
                                                                    <i class="ri-delete-bin-7-fill"></i>
                                                                </button>
                                                            </form>
                                                        @endif
                                                    @endcan --}}


                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach

                                </tbody>
                            </table>
                        </div>
                        {{-- Đã xuất bản --}}
                        <div class="tab-pane {{ session('movies.selected_tab') === 'publish' ? 'active' : '' }} " id="isPublish" role="tabpanel">
                            <table class="table table-bordered dt-responsive nowrap align-middle w-100"
                                id="tableIsPublish">
                                <thead class='table-light'>
                                    <tr>
                                        <th>#</th>
                                        <th class="text-center">Hình ảnh</th>
                                        <th>Thông tin phim</th>
                                        <th>Hoạt động</th>
                                        <th>Nổi bật</th>
                                        <th>Trạng thái</th>
                                        <th>Chức năng</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($movies->where('is_publish') as $movie)
                                        <tr>
                                            <td>{{ $movie->id }}</td>
                                            <td class="text-center">

                                                @php
                                                    $url = $movie->img_thumbnail;

                                                    if (!\Str::contains($url, 'http')) {
                                                        $url = Storage::url($url);
                                                    }

                                                @endphp
                                                @if (!empty($movie->img_thumbnail))
                                                    <img src="{{ $url }}" alt="" width="130px">
                                                @else
                                                    No image !
                                                @endif

                                            </td>
                                            <td>
                                                <h4 class="movie-name">{{ $movie->name }}</h4>
                                                <ul class="nav nav-sm flex-column content-movie">
                                                    <li class="nav-item mb-1"><span class="fw-semibold">Đạo diễn:</span>
                                                        {{ $movie->director }}</li>
                                                    <li class="nav-item mb-1"><span class="fw-semibold">Diễn viên:</span>
                                                        {{ $movie->cast }}</li>
                                                    <li class="nav-item mb-1"><span class="fw-semibold">Thể loại:</span>
                                                        {{ $movie->category }}</li>
                                                    <li class="nav-item mb-1"><span class="fw-semibold">Ngày khởi
                                                            chiếu:</span>
                                                        {{ \Carbon\Carbon::parse($movie->release_date)->format('d/m/Y') }}
                                                    </li>
                                                    <li class="nav-item mb-1"><span class="fw-semibold">Ngày kết
                                                            thúc:</span>
                                                        {{ \Carbon\Carbon::parse($movie->end_date)->format('d/m/Y') }}</li>
                                                    <li class="nav-item mb-1"><span class="fw-semibold">Phân loại:</span>
                                                        {{ $movie->rating }}</li>
                                                    <li class="nav-item mb-1">
                                                        <span class="fw-semibold">Phiên bản:</span>
                                                        @foreach ($movie->movieVersions as $version)
                                                            <span class="badge bg-info">{{ $version->name }}</span>
                                                        @endforeach
                                                    </li>
                                                    <li class="nav-item mb-1"><span class="fw-semibold">Code
                                                            Youtube:</span>
                                                        <input type="text" disabled
                                                            value="{{ $movie->trailer_url }}2121">
                                                    </li>
                                                </ul>
                                            </td>
                                            <td>
                                                @can('Sửa phim')
                                                    <div class="form-check form-switch form-switch-success">
                                                        <input class="form-check-input switch-is-active change-is-active"
                                                            name="is_active" type="checkbox" role="switch"
                                                            data-id="{{ $movie->id }}" @checked($movie->is_active)
                                                            onclick="return confirm('Bạn có chắc muốn thay đổi ?')">
                                                    </div>
                                                @else
                                                    <div class="form-check form-switch form-switch-success">
                                                        <input class="form-check-input switch-is-active change-is-active"
                                                            name="is_active" disabled readonly type="checkbox" role="switch"
                                                            data-id="{{ $movie->id }}" @checked($movie->is_active)
                                                            onclick="return confirm('Bạn có chắc muốn thay đổi ?')">
                                                    </div>
                                                @endcan
                                            </td>

                                            <td>
                                                @can('Sửa phim')
                                                    <div class="form-check form-switch form-switch-danger">
                                                        <input class="form-check-input switch-is-active change-is-hot"
                                                            name="is_hot" type="checkbox" role="switch"
                                                            data-id="{{ $movie->id }}" @checked($movie->is_hot)
                                                            onclick="return confirm('Bạn có chắc muốn thay đổi ?')">
                                                    </div>
                                                @else
                                                    <div class="form-check form-switch form-switch-danger">
                                                        <input class="form-check-input switch-is-active change-is-hot"
                                                            name="is_hot" disabled readonly type="checkbox" role="switch"
                                                            data-id="{{ $movie->id }}" @checked($movie->is_hot)
                                                            onclick="return confirm('Bạn có chắc muốn thay đổi ?')">
                                                    </div>
                                                @endcan
                                            </td>
                                            <td>
                                                {!! $movie->is_publish == 1
                                                    ? '<span class="badge bg-success-subtle text-success">Đã xuất bản</span>'
                                                    : '<span class="badge bg-danger-subtle text-danger">Bản nháp</span>' !!}
                                            </td>
                                            <td>
                                                <div class='d-flex'>
                                                    @can('Xem chi tiết phim')
                                                        <a href="{{ route('admin.movies.show', $movie) }}">
                                                            <button title="xem" class="btn btn-success btn-sm "
                                                                type="button"><i class="fas fa-eye"></i></button></a>
                                                    @endcan
                                                    @can('Sửa phim')
                                                        <a href="{{ route('admin.movies.edit', $movie) }}">
                                                            <button title="sủa" class="btn btn-warning btn-sm mx-1"
                                                                type="button"><i class="fas fa-edit"></i></button>
                                                        </a>
                                                    @endcan
                                                    {{-- @can('Xóa phim')
                                                        @if (!$movie->is_publish || $movie->showtimes()->doesntExist())
                                                            <form action="{{ route('admin.movies.destroy', $movie) }}"
                                                                method="POST" class="d-inline-block">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-danger btn-sm"
                                                                    onclick="return confirm('Bạn có muốn xóa không')">
                                                                    <i class="ri-delete-bin-7-fill"></i>
                                                                </button>
                                                            </form>
                                                        @endif
                                                    @endcan --}}


                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach

                                </tbody>
                            </table>
                        </div>
                        {{-- Bản nháp --}}
                        <div class="tab-pane {{ session('movies.selected_tab') === 'draft' ? 'active' : '' }}" id="isDraft" role="tabpanel">
                            <table class="table table-bordered dt-responsive nowrap align-middle w-100" id="tableIsDraft">
                                <thead class='table-light'>
                                    <tr>
                                        <th>#</th>
                                        <th class="text-center">Hình ảnh</th>
                                        <th>Thông tin phim</th>
                                        <th>Hoạt động</th>
                                        <th>Nổi bật</th>
                                        <th>Trạng thái</th>
                                        <th>Chức năng</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($movies->where('is_publish', false) as $movie)
                                        <tr>
                                            <td>{{ $movie->id }}</td>
                                            <td class="text-center">

                                                @php
                                                    $url = $movie->img_thumbnail;

                                                    if (!\Str::contains($url, 'http')) {
                                                        $url = Storage::url($url);
                                                    }

                                                @endphp
                                                @if (!empty($movie->img_thumbnail))
                                                    <img src="{{ $url }}" alt="" width="130px">
                                                @else
                                                    No image !
                                                @endif

                                            </td>
                                            <td>
                                                <h4 class="movie-name">{{ $movie->name }}</h4>
                                                <ul class="nav nav-sm flex-column content-movie">
                                                    <li class="nav-item mb-1"><span class="fw-semibold">Đạo diễn:</span>
                                                        {{ $movie->director }}</li>
                                                    <li class="nav-item mb-1"><span class="fw-semibold">Diễn viên:</span>
                                                        {{ $movie->cast }}</li>
                                                    <li class="nav-item mb-1"><span class="fw-semibold">Thể loại:</span>
                                                        {{ $movie->category }}</li>
                                                    <li class="nav-item mb-1"><span class="fw-semibold">Ngày khởi
                                                            chiếu:</span>
                                                        {{ \Carbon\Carbon::parse($movie->release_date)->format('d/m/Y') }}
                                                    </li>
                                                    <li class="nav-item mb-1"><span class="fw-semibold">Ngày kết
                                                            thúc:</span>
                                                        {{ \Carbon\Carbon::parse($movie->end_date)->format('d/m/Y') }}</li>
                                                    <li class="nav-item mb-1"><span class="fw-semibold">Phân loại:</span>
                                                        {{ $movie->rating }}</li>
                                                    <li class="nav-item mb-1">
                                                        <span class="fw-semibold">Phiên bản:</span>
                                                        @foreach ($movie->movieVersions as $version)
                                                            <span class="badge bg-info">{{ $version->name }}</span>
                                                        @endforeach
                                                    </li>
                                                    <li class="nav-item mb-1"><span class="fw-semibold">Code
                                                            Youtube:</span>
                                                        <input type="text" disabled
                                                            value="{{ $movie->trailer_url }}2121">
                                                    </li>
                                                </ul>
                                            </td>
                                            <td>
                                                @can('Sửa phim')
                                                    <div class="form-check form-switch form-switch-success">
                                                        <input class="form-check-input switch-is-active change-is-active"
                                                            name="is_active" type="checkbox" role="switch"
                                                            data-id="{{ $movie->id }}" @checked($movie->is_active)
                                                            onclick="return confirm('Bạn có chắc muốn thay đổi ?')">
                                                    </div>
                                                @else
                                                    <div class="form-check form-switch form-switch-success">
                                                        <input class="form-check-input switch-is-active change-is-active"
                                                            name="is_active" disabled readonly type="checkbox" role="switch"
                                                            data-id="{{ $movie->id }}" @checked($movie->is_active)
                                                            onclick="return confirm('Bạn có chắc muốn thay đổi ?')">
                                                    </div>
                                                @endcan
                                            </td>

                                            <td>
                                                @can('Sửa phim')
                                                    <div class="form-check form-switch form-switch-danger">
                                                        <input class="form-check-input switch-is-active change-is-hot"
                                                            name="is_hot" type="checkbox" role="switch"
                                                            data-id="{{ $movie->id }}" @checked($movie->is_hot)
                                                            onclick="return confirm('Bạn có chắc muốn thay đổi ?')">
                                                    </div>
                                                @else
                                                    <div class="form-check form-switch form-switch-danger">
                                                        <input class="form-check-input switch-is-active change-is-hot"
                                                            name="is_hot" disabled readonly type="checkbox" role="switch"
                                                            data-id="{{ $movie->id }}" @checked($movie->is_hot)
                                                            onclick="return confirm('Bạn có chắc muốn thay đổi ?')">
                                                    </div>
                                                @endcan
                                            </td>
                                            <td>
                                                {!! $movie->is_publish == 1
                                                    ? '<span class="badge bg-success-subtle text-success">Đã xuất bản</span>'
                                                    : '<span class="badge bg-danger-subtle text-danger">Bản nháp</span>' !!}
                                            </td>
                                            <td>
                                                <div class='d-flex'>
                                                    @can('Xem chi phim')
                                                        <a href="{{ route('admin.movies.show', $movie) }}">
                                                            <button title="xem" class="btn btn-success btn-sm "
                                                                type="button"><i class="fas fa-eye"></i></button></a>
                                                    @endcan
                                                    @can('Sửa phim')
                                                        <a href="{{ route('admin.movies.edit', $movie) }}">
                                                            <button title="sủa" class="btn btn-warning btn-sm mx-1"
                                                                type="button"><i class="fas fa-edit"></i></button>
                                                        </a>
                                                    @endcan
                                                    @can('Xóa phim')
                                                        @if (!$movie->is_publish || $movie->showtimes()->doesntExist())
                                                            <form action="{{ route('admin.movies.destroy', $movie) }}"
                                                                method="POST" class="d-inline-block">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-danger btn-sm"
                                                                    onclick="return confirm('Bạn có muốn xóa không')">
                                                                    <i class="ri-delete-bin-7-fill"></i>
                                                                </button>
                                                            </form>
                                                        @endif
                                                    @endcan


                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach

                                </tbody>
                            </table>
                        </div>


                    </div>

                </div>
            </div>
        </div><!--end col-->
    </div><!--end row-->
@endsection


@section('script-libs')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

    <!--datatable js-->
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script>
        document.querySelectorAll('.nav-link').forEach(tab => {
            tab.addEventListener('click', function() {
                const tabKey = this.getAttribute('data-tab-key');
                console.log(tabKey);

                fetch('{{ route('admin.movies.selected-tab') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            tab_key: tabKey
                        })
                    }).then(response => response.json())
                    .then(data => console.log('Tab saved:', data));
            });
        });
    </script>


    <script>
        $(document).ready(function() {
            // Khởi tạo DataTable
            let tableAllSeatTemplate = new DataTable("#tableAllMovie", {
                order: [],
                language: {
                    search: "Tìm kiếm:",
                    paginate: {
                        next: "Tiếp theo",
                        previous: "Trước"
                    },
                    lengthMenu: "Hiển thị _MENU_ mục",
                    info: "Hiển thị từ _START_ đến _END_ trong tổng số _TOTAL_ mục",
                    emptyTable: "Không có dữ liệu để hiển thị",
                    zeroRecords: "Không tìm thấy kết quả phù hợp"
                },
            });

            let tableIsPublish = new DataTable("#tableIsPublish", {
                order: [],
                language: {
                    search: "Tìm kiếm:",
                    paginate: {
                        next: "Tiếp theo",
                        previous: "Trước"
                    },
                    lengthMenu: "Hiển thị _MENU_ mục",
                    info: "Hiển thị từ _START_ đến _END_ trong tổng số _TOTAL_ mục",
                    emptyTable: "Không có dữ liệu để hiển thị",
                    zeroRecords: "Không tìm thấy kết quả phù hợp"
                },
            });

            let tableIsDraft = new DataTable("#tableIsDraft", {
                order: [],
                language: {
                    search: "Tìm kiếm:",
                    paginate: {
                        next: "Tiếp theo",
                        previous: "Trước"
                    },
                    lengthMenu: "Hiển thị _MENU_ mục",
                    info: "Hiển thị từ _START_ đến _END_ trong tổng số _TOTAL_ mục",
                    emptyTable: "Không có dữ liệu để hiển thị",
                    zeroRecords: "Không tìm thấy kết quả phù hợp"
                },
            });

            // Xử lý sự kiện change cho checkbox .changeActive
            $(document).on('change', '.change-is-active', function() {
                let movieId = $(this).data('id');
                let is_active = $(this).is(':checked') ? 1 : 0;
                let tableId = $(this).closest('table').attr('id'); // Lấy ID của bảng

                Swal.fire({
                    title: 'Đang xử lý...',
                    text: 'Vui lòng chờ trong giây lát.',
                    allowOutsideClick: false, // Không cho phép đóng ngoài khi đang xử lý
                    didOpen: () => {
                        Swal.showLoading(); // Hiển thị spinner loading
                    }
                });
                // Gửi yêu cầu AJAX để thay đổi trạng thái
                $.ajax({
                    url: '{{ route('movies.update-active') }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        id: movieId,
                        is_active: is_active
                    },
                    success: function(response) {
                        if (response.success) {
                            let table;


                            // Tùy vào ID của bảng mà chọn đúng đối tượng DataTable
                            if (tableId === 'tableAllSeatTemplate') {
                                table = tableAllSeatTemplate;
                            } else if (tableId === 'tableIsPublish') {
                                table = tableIsPublish;
                            } else if (tableId === 'tableIsDraft') {
                                table = tableIsDraft;
                            }

                            // Cập nhật cột trạng thái (cột thứ 6) trong dòng này
                            let row = table.row($(`[data-id="${movieId}"]`).closest(
                                'tr'));
                            console.log(row);

                            let statusHtml = response.data.is_active ?
                                `<div class="form-check form-switch form-switch-success">
                        <input class="form-check-input switch-is-active change-is-active"
                            type="checkbox" data-id="${movieId}" checked onclick="return confirm('Bạn có chắc muốn thay đổi ?')">` :
                                `<div class="form-check form-switch form-switch-success">
                        <input class="form-check-input switch-is-active change-is-active"
                            type="checkbox" data-id="${movieId}" onclick="return confirm('Bạn có chắc muốn thay đổi ?')">`;

                            updateStatusInTable(table, movieId, statusHtml);

                            // Cập nhật trạng thái cho các bảng còn lại
                            if (tableId !== 'tableAllSeatTemplate') {
                                updateStatusInTable(tableAllSeatTemplate, movieId,
                                    statusHtml);
                            }
                            if (tableId !== 'tableIsPublish') {
                                updateStatusInTable(tableIsPublish, movieId, statusHtml);
                            }
                            if (tableId !== 'tableIsDraft') {
                                updateStatusInTable(tableIsDraft, movieId, statusHtml);
                            }
                            Swal.fire({
                                icon: 'success',
                                title: 'Thành công!',
                                text: 'Trạng thái hoạt động đã được cập nhật.',
                                confirmButtonText: 'Đóng',
                                timer: 3000,
                                timerProgressBar: true,

                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Lỗi!',
                            text: 'Có lỗi xảy ra khi cập nhật trạng thái.',
                            confirmButtonText: 'Đóng',
                            timer: 3000,
                            showConfirmButton: true, // Hiển thị nút "Đóng"
                        });

                        // Hoàn lại thao tác (set lại trạng thái ban đầu cho checkbox)
                        let checkbox = $(`[data-id="${movieId}"]`).closest('tr').find(
                            '.change-is-active');
                        checkbox.prop('checked', !is_active);
                    }
                });
                console.log('Đã thay đổi trạng thái active');
            });

            $(document).on('change', '.change-is-hot', function() {
                let movieId = $(this).data('id');
                let is_hot = $(this).is(':checked') ? 1 : 0;
                let tableId = $(this).closest('table').attr('id'); // Lấy ID của bảng

                Swal.fire({
                    title: 'Đang xử lý...',
                    text: 'Vui lòng chờ trong giây lát.',
                    allowOutsideClick: false, // Không cho phép đóng ngoài khi đang xử lý
                    didOpen: () => {
                        Swal.showLoading(); // Hiển thị spinner loading
                    }
                });

                // Gửi yêu cầu AJAX để thay đổi trạng thái
                $.ajax({
                    url: '{{ route('movies.update-hot') }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        id: movieId,
                        is_hot: is_hot
                    },
                    success: function(response) {
                        if (response.success) {
                            let table;

                            // Tùy vào ID của bảng mà chọn đúng đối tượng DataTable
                            if (tableId === 'tableAllSeatTemplate') {
                                table = tableAllSeatTemplate;
                            } else if (tableId === 'tableIsPublish') {
                                table = tableIsPublish;
                            } else if (tableId === 'tableIsDraft') {
                                table = tableIsDraft;
                            }

                            // Cập nhật cột trạng thái (cột thứ 6) trong dòng này
                            let row = table.row($(`[data-id="${movieId}"]`).closest(
                                'tr'));
                            console.log(row);

                            let statusHtml = response.data.is_hot ?
                                `<div class="form-check form-switch form-switch-danger">
                        <input class="form-check-input switch-is-active change-is-hot"
                            type="checkbox" data-id="${movieId}" checked onclick="return confirm('Bạn có chắc muốn thay đổi ?')">` :
                                `<div class="form-check form-switch form-switch-danger">
                        <input class="form-check-input switch-is-active change-is-hot"
                            type="checkbox" data-id="${movieId}" onclick="return confirm('Bạn có chắc muốn thay đổi ?')">`;

                            updateStatusInTable2(table, movieId, statusHtml);

                            // Cập nhật trạng thái cho các bảng còn lại
                            if (tableId !== 'tableAllSeatTemplate') {
                                updateStatusInTable2(tableAllSeatTemplate, movieId,
                                    statusHtml);
                            }
                            if (tableId !== 'tableIsPublish') {
                                updateStatusInTable2(tableIsPublish, movieId, statusHtml);
                            }
                            if (tableId !== 'tableIsDraft') {
                                updateStatusInTable2(tableIsDraft, movieId, statusHtml);
                            }
                            Swal.fire({
                                icon: 'success',
                                title: 'Thành công!',
                                text: 'Trạng thái nổi bật đã được cập nhật.',
                                confirmButtonText: 'Đóng',
                                timer: 3000,
                                timerProgressBar: true,

                            });


                        }
                    },
                    error: function(xhr, status, error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Lỗi!',
                            text: 'Có lỗi xảy ra khi cập nhật trạng thái.',
                            confirmButtonText: 'Đóng',
                            timer: 3000,
                            showConfirmButton: true, // Hiển thị nút "Đóng"
                        });

                        // Hoàn lại thao tác (set lại trạng thái ban đầu cho checkbox)
                        let checkbox = $(`[data-id="${movieId}"]`).closest('tr').find(
                            '.change-is-hot');
                        checkbox.prop('checked', !is_hot);
                    }
                });
            });
        });

        function updateStatusInTable(table, movieId, statusHtml) {
            // Cập nhật trạng thái trong bảng
            table.rows().every(function() {
                let row = this.node();
                let rowId = $(row).find('.change-is-active').data('id');
                if (rowId === movieId) {
                    table.cell(row, 3).data(statusHtml).draw(false);
                }
            });
        }

        function updateStatusInTable2(table, movieId, statusHtml) {
            // Cập nhật trạng thái trong bảng
            table.rows().every(function() {
                let row = this.node();
                let rowId = $(row).find('.change-is-hot').data('id');
                if (rowId === movieId) {
                    table.cell(row, 4).data(statusHtml).draw(false);
                }
            });
        }
    </script>
@endsection
