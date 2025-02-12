@extends('admin.layouts.master')

@section('title')
    Slideshows
@endsection

@section('style-libs')
    <!--datatable css-->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css"/>
    <!--datatable responsive css-->
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css"/>

    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css">
    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css">
    <style>
        .overflow-x-auto {
            overflow-x: auto;
            scrollbar-width: thin;
            padding: 10px;
        }

        .overflow-x-auto::-webkit-scrollbar {
            height: 6px;
        }

        .overflow-x-auto::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 3px;
        }

        .overflow-x-auto::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 3px;
        }
    </style>
@endsection



@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Slideshows</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Danh sách</a></li>
                        <li class="breadcrumb-item active">Slideshows</li>
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
                    <h5 class="card-title mb-0"> Danh sách slideshow </h5>
                    @can('Thêm slideshows')
                        <a href="{{ route('admin.slideshows.create') }}" class="btn btn-primary">Thêm mới</a>
                    @endcan
                </div>

                @if (session()->has('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session()->has('error'))
                    <div class="alert alert-success">
                        {{ session('error') }}
                    </div>
                @endif

                <div class="card-body">
                    <table id="example" class="table table-bordered dt-responsive nowrap align-middle w-100">
                        <thead class='table-light'>
                        <tr>
                            <th style="width: 30px;">#</th>
                            <th>Hình ảnh</th>
                            <th>Mô tả ngắn</th>
                            <th style="width: 85px;">Hoạt động</th>
                            <th style="width: 85px;">Chức năng</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($slideshows as $slideshow)
                            <tr>
                                <td>{{ $slideshow->id }}</td>
                                <td class="text-center" style="width: 400px;">
                                    <div class="overflow-x-auto">
                                        <div style="display: grid; grid-template-columns: repeat(7, 1fr); gap: 10px;">
                                            @if (is_array($slideshow->img_thumbnail))
                                                @foreach ($slideshow->img_thumbnail as $image)
                                                    @if (filter_var($image, FILTER_VALIDATE_URL))
                                                        <img src="{{ $image }}" width="100px"
                                                             alt="Slideshow image" class="rounded-2">
                                                    @else
                                                        <img src="{{ Storage::url($image) }}" width="100px"
                                                             alt="Slideshow image" class="rounded-2">
                                                    @endif
                                                @endforeach
                                            @else
                                                <p>No image !</p>
                                            @endif
                                        </div>

                                    </div>


                                    <!-- Swiper -->
                                    {{-- <div
                                    class="swiper effect-coverflow-swiper rounded pb-5 swiper-coverflow swiper-3d swiper-initialized swiper-horizontal swiper-watch-progress">
                                    <div class="swiper-wrapper" id="swiper-wrapper-faf810d4c84b10918c" aria-live="off"
                                         style="cursor: grab; transition-duration: 300ms; transform: translate3d(-77.875px, 0px, 0px);">
                                        <div class="swiper-slide swiper-slide-visible" role="group" aria-label="4 / 6" data-swiper-slide-index="3"
                                             style="width: 155.75px; transition-duration: 300ms; transform: translate3d(0px, 0px, -200px) rotateX(0deg) rotateY(100deg) scale(1); z-index: -1;">
                                            <img src="assets/images/small/img-7.jpg" alt="" class="img-fluid">
                                            <div class="swiper-slide-shadow-left" style="opacity: 2; transition-duration: 300ms;"></div>
                                            <div class="swiper-slide-shadow-right" style="opacity: 0; transition-duration: 300ms;"></div>
                                        </div>
                                        <div class="swiper-slide swiper-slide-visible swiper-slide-prev" role="group" aria-label="5 / 6"
                                             data-swiper-slide-index="4"
                                             style="width: 155.75px; transition-duration: 300ms; transform: translate3d(0px, 0px, -99.8395px) rotateX(0deg) rotateY(49.9197deg) scale(1); z-index: 0;">
                                            <img src="assets/images/small/img-8.jpg" alt="" class="img-fluid">
                                            <div class="swiper-slide-shadow-left" style="opacity: 0.998395; transition-duration: 300ms;"></div>
                                            <div class="swiper-slide-shadow-right" style="opacity: 0; transition-duration: 300ms;"></div>
                                        </div>
                                        <div class="swiper-slide swiper-slide-visible swiper-slide-active" role="group" aria-label="6 / 6"
                                             data-swiper-slide-index="5"
                                             style="width: 155.75px; transition-duration: 300ms; transform: translate3d(0px, 0px, -0.321027px) rotateX(0deg) rotateY(-0.160514deg) scale(1); z-index: 1;">
                                            <img src="assets/images/small/img-9.jpg" alt="" class="img-fluid">
                                            <div class="swiper-slide-shadow-left" style="opacity: 0; transition-duration: 300ms;"></div>
                                            <div class="swiper-slide-shadow-right" style="opacity: 0.00321027; transition-duration: 300ms;"></div>
                                        </div>
                                        <div class="swiper-slide swiper-slide-visible swiper-slide-next" role="group" aria-label="1 / 6"
                                             data-swiper-slide-index="0"
                                             style="width: 155.75px; transition-duration: 300ms; transform: translate3d(0px, 0px, -99.8395px) rotateX(0deg) rotateY(-49.9197deg) scale(1); z-index: 0;">
                                            <img src="assets/images/small/img-4.jpg" alt="" class="img-fluid">
                                            <div class="swiper-slide-shadow-left" style="opacity: 0; transition-duration: 300ms;"></div>
                                            <div class="swiper-slide-shadow-right" style="opacity: 0.998395; transition-duration: 300ms;"></div>
                                        </div>
                                        <div class="swiper-slide swiper-slide-visible" role="group" aria-label="2 / 6" data-swiper-slide-index="1"
                                             style="width: 155.75px; transition-duration: 300ms; transform: translate3d(0px, 0px, -200px) rotateX(0deg) rotateY(-100deg) scale(1); z-index: -1;">
                                            <img src="assets/images/small/img-5.jpg" alt="" class="img-fluid">
                                            <div class="swiper-slide-shadow-left" style="opacity: 0; transition-duration: 300ms;"></div>
                                            <div class="swiper-slide-shadow-right" style="opacity: 2; transition-duration: 300ms;"></div>
                                        </div>
                                        <div class="swiper-slide" role="group" aria-label="3 / 6" data-swiper-slide-index="2"
                                             style="width: 155.75px; transition-duration: 300ms; transform: translate3d(0px, 0px, -300.161px) rotateX(0deg) rotateY(-150.08deg) scale(1); z-index: -2;">
                                            <img src="assets/images/small/img-6.jpg" alt="" class="img-fluid">
                                            <div class="swiper-slide-shadow-left" style="opacity: 0; transition-duration: 300ms;"></div>
                                            <div class="swiper-slide-shadow-right" style="opacity: 3.00161; transition-duration: 300ms;"></div>
                                        </div>
                                    </div>
                                    <div
                                        class="swiper-pagination swiper-pagination-dark swiper-pagination-clickable swiper-pagination-bullets swiper-pagination-horizontal swiper-pagination-bullets-dynamic"
                                        style="width: 150px;"><span class="swiper-pagination-bullet" tabindex="0" role="button" aria-label="Go to slide 1"
                                                                    style="left: -90px;"></span><span class="swiper-pagination-bullet" tabindex="0" role="button"
                                                                                                      aria-label="Go to slide 2" style="left: -90px;"></span><span
                                            class="swiper-pagination-bullet" tabindex="0" role="button" aria-label="Go to slide 3"
                                            style="left: -90px;"></span><span class="swiper-pagination-bullet swiper-pagination-bullet-active-prev-prev"
                                                                              tabindex="0" role="button" aria-label="Go to slide 4"
                                                                              style="left: -90px;"></span><span
                                            class="swiper-pagination-bullet swiper-pagination-bullet-active-prev" tabindex="0" role="button"
                                            aria-label="Go to slide 5" style="left: -90px;"></span><span
                                            class="swiper-pagination-bullet swiper-pagination-bullet-active swiper-pagination-bullet-active-main" tabindex="0"
                                            role="button" aria-label="Go to slide 6" style="left: -90px;" aria-current="true"></span></div>
                                    <span class="swiper-notification" aria-live="assertive" aria-atomic="true"></span>
                                </div> --}}

                                </td>
                                <td>{{ $slideshow->description }}</td>
                                <td>
                                    @can('Sửa slideshows')
                                        <div class="form-check form-switch form-switch-success">
                                            <input class="form-check-input switch-is-active changeActive" name="is_active"
                                                   type="checkbox" role="switch" data-slideshow-id="{{ $slideshow->id }}"
                                                   @checked($slideshow->is_active) onclick="return checkActive(this)">
                                        </div>
                                    @else
                                        <div class="form-check form-switch form-switch-success">
                                            <input class="form-check-input switch-is-active changeActive" name="is_active"
                                                   type="checkbox" role="switch" disabled readonly data-slideshow-id="{{ $slideshow->id }}"
                                                   @checked($slideshow->is_active) onclick="return checkActive(this)">
                                        </div>
                                    @endcan

                                </td>
                                <td class="text-center">
                                    @if (!$slideshow->is_active)
                                        @can('Sửa slideshows')
                                            {{-- <a href="{{ route('admin.slideshows.edit', $slideshow) }}">
                                                <button title="xem" class="btn btn-warning btn-sm " type="button"><i
                                                        class="fas fa-edit"></i></button>
                                            </a> --}}
                                        @endcan
                                        @can('Xóa slideshows')
                                            <form action="{{ route('admin.slideshows.destroy', $slideshow) }}" method="POST"
                                                  class="d-inline-block">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm"
                                                        onclick="return confirm('Bạn có muốn xóa không')">
                                                    <i class="ri-delete-bin-7-fill"></i>
                                                </button>
                                            </form>
                                        @endcan
                                    @endif
                                </td>

                            </tr>
                        @endforeach
                        </tbody>

                    </table>
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
    <!-- Swiper JS -->
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    <script>
        new DataTable("#example", {
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
    </script>
    {{-- <script>
        $(document).ready(function() {
            $('.changeActive').on('change', function() {
                let slideshowId = $(this).data('slideshow-id');
                let is_active = $(this).is(':checked') ? 1 : 0;
                // Gửi yêu cầu AJAX
                $.ajax({
                    url: '{{ route('slideshows.change-active') }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        id: slideshowId,
                        is_active: is_active
                    },
                    success: function(response) {
                        if (!response.success) {
                            alert('Có lỗi xảy ra, vui lòng thử lại.');
                        }
                    },
                    error: function(xhr, status, error) {
                        alert('Lỗi kết nối hoặc server không phản hồi.');
                        console.error(error);
                    }
                });
            });
        });
    </script> --}}
    <script>
        $(document).ready(function () {
            // Set về trạng thái mặc định khi load trang
            $('.changeActive').each(function () {
                let isActive = $(this).is(':checked') ? 1 : 0;
                $(this).data('current-active', isActive);
            });

            // Xử lý sự kiện thay đổi trạng thái
            $('.changeActive').on('change', function () {
                let currentCheckbox = $(this);
                let slideshowId = currentCheckbox.data('slideshow-id');
                let is_active = currentCheckbox.is(':checked') ? 1 : 0;

                // Kiểm tra nếu checkbox đang bật mà bị tắt chính nó
                if (!is_active && currentCheckbox.data('current-active') === 1) {
                    alert('Bạn không thể tắt slideshow đang hoạt động!');
                    currentCheckbox.prop('checked', true); // Hoàn tác thay đổi
                    return;
                }

                // Xác nhận nếu người dùng muốn thay đổi
                if (!confirm('Bạn có chắc muốn thay đổi?')) {
                    currentCheckbox.prop('checked', !currentCheckbox.is(':checked')); // Hoàn tác thay đổi
                    return;
                }

                // Tắt tất cả các checkbox khác nếu bật checkbox hiện tại
                if (is_active === 1) {
                    $('.changeActive').not(currentCheckbox).prop('checked', false);
                }

                // Gửi yêu cầu AJAX để cập nhật trạng thái
                $.ajax({
                    url: '{{ route('slideshows.change-active') }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        id: slideshowId,
                        is_active: is_active
                    },
                    success: function (response) {
                        if (response.success) {
                            // Cập nhật trạng thái hiện tại
                            $('.changeActive').data('current-active',
                                0); // Reset trạng thái cho tất cả
                            if (is_active === 1) {
                                currentCheckbox.data('current-active',
                                    1); // Đặt trạng thái mới cho checkbox đang bật
                            }
                            Swal.fire({
                                icon: 'success',
                                title: 'Thành công!',
                                text: 'Trạng thái hoạt động đã được cập nhật.',
                                confirmButtonText: 'Đóng',
                                timer: 3000,
                                timerProgressBar: true,
                            }).then(() => location.reload());
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Lỗi!',
                                text: 'Có lỗi xảy ra, vui lòng thử lại.',
                                confirmButtonText: 'Đóng',
                                timer: 3000,
                                timerProgressBar: true,
                            });
                            console.error(response.message);
                        }
                    },
                    error: function (xhr, status, error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Lỗi!',
                            text: 'Lỗi kết nối hoặc server không phản hồi.',
                            confirmButtonText: 'Đóng',
                            timer: 3000,
                            timerProgressBar: true,
                        });
                        console.error(error);

                        // Hoàn tác thay đổi nếu server phản hồi lỗi
                        currentCheckbox.prop('checked', !currentCheckbox.is(':checked'));
                    }
                });
            });
        });
    </script>
@endsection
