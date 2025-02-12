@extends('admin.layouts.master')

@section('title')
    Quản lý bài viết
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
                <h4 class="mb-sm-0">Quản lý bài viết</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Bài viết</a></li>
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
                    <h5 class="card-title mb-0">Danh sách bài viết</h5>
                    @can('Thêm bài viết')
                        <a href="{{ route('admin.posts.create') }}" class="btn btn-primary mb-3 ">Thêm mới</a>
                    @endcan
                </div>
                @if (session()->has('success'))
                    <div class="alert alert-success m-3">
                        {{ session()->get('success') }}
                    </div>
                @endif

                <div class="card-body">
                    <table id="example" class="table table-bordered dt-responsive nowrap align-middle w-100"">
                        <thead class='table-light'>
                            <tr>
                                <th>#</th>
                                <th>Tiêu đề</th>
                                <th>Hình ảnh</th>
                                <th>Người tạo</th>
                                <th>Lượt xem</th>
                                <th>Ngày tạo</th>  
                                <th>Hoạt động</th>                     
                                <th>Chức năng</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($posts as $post)
                                <tr>
                                    <td>{{ $post->id }}</td>
                                    <td style="width: 5%;">{{ \Illuminate\Support\Str::limit($post->title, 30, '...') }}</td>
                                    <td>
                                        @if (!empty($post->img_post))
                                            <img width="100px" src="{{ Storage::url($post->img_post) }}" alt="">
                                        @else
                                            Không có ảnh
                                        @endif
                                    </td>
                                    <td>{{ $post->user->name }}</td>
                                    <td>{{ number_format($post->view_count) }}</td>
                                    <td>{{ $post->created_at->format('d/m/Y') }}<br>{{ $post->created_at->format('H:i:s') }}</td>
                                    <td>
                                        @can('Sửa bài viết')
                                            <div class="form-check form-switch form-switch-success">
                                                <input class="form-check-input switch-is-active changeActive" name="is_active"
                                                    type="checkbox" role="switch" data-post-id="{{ $post->id }}"
                                                    @checked($post->is_active)
                                                    onclick="return confirm('Bạn có chắc muốn thay đổi ?')">
                                            </div>
                                        @else
                                            <div class="form-check form-switch form-switch-success">
                                                <input class="form-check-input switch-is-active changeActive" name="is_active"
                                                    type="checkbox" role="switch" disabled readonly
                                                    data-post-id="{{ $post->id }}" @checked($post->is_active)
                                                    onclick="return confirm('Bạn có chắc muốn thay đổi ?')">
                                            </div>
                                        @endcan
                                    </td>
                                    
                                    <td>
                                        <div class="d-flex">
                                            @can('Xem chi tiết bài viết')
                                                <a href="{{ route('admin.posts.show', $post) }}">
                                                    <button title="xem" class="btn btn-success btn-sm " type="button"><i
                                                            class="fas fa-eye"></i></button></a>
                                            @endcan
                                            @can('Sửa bài viết')
                                                <a class="mx-1" href="{{ route('admin.posts.edit', $post) }}">
                                                    <button title="xem" class="btn btn-warning btn-sm " type="button"><i
                                                            class="fas fa-edit"></i></button>
                                                </a>
                                            @endcan
                                            {{-- Xóa --}}
                                            @can('Xóa bài viết')
                                                <form action="{{ route('admin.posts.destroy', $post) }}" method="post"
                                                    class="d-inline-block">
                                                    @csrf
                                                    @method('delete')
                                                    <button type="submit" class="btn btn-danger btn-sm"
                                                        onclick="return confirm('Bạn chắc chắn muốn xóa không?')"><i
                                                            class="ri-delete-bin-7-fill"></i></button>
                                                </form>
                                            @endcan
                                        </div>
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
    <script>
        $(document).ready(function() {
            // Khởi tạo DataTable
            let table = $('#example').DataTable({
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
            $(document).on('change', '.changeActive', function() {
                let postId = $(this).data('post-id');
                let is_active = $(this).is(':checked') ? 1 : 0;

                Swal.fire({
                    title: 'Đang xử lý...',
                    text: 'Vui lòng chờ trong giây lát.',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                // Gửi yêu cầu AJAX để thay đổi trạng thái
                $.ajax({
                    url: '{{ route('posts.change-active') }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        id: postId,
                        is_active: is_active
                    },
                    success: function(response) {
                        if (response.success) {
                            let row = table.row($(`[data-post-id="${postId}"]`).closest(
                                'tr'));
                            console.log(row);

                            // Cập nhật cột trạng thái (cột thứ 2) trong dòng này
                            let statusHtml = response.data.is_active ?
                                `<div class="form-check form-switch form-switch-success">
                                    <input class="form-check-input switch-is-active changeActive"
                                        type="checkbox" data-post-id="${postId}" checked   onclick="return confirm('Bạn có chắc muốn thay đổi ?')">
                                </div>` :
                                `<div class="form-check form-switch form-switch-success">
                                    <input class="form-check-input switch-is-active changeActive"
                                        type="checkbox" data-post-id="${postId}"   onclick="return confirm('Bạn có chắc muốn thay đổi ?')">
                                </div> `;
                            row.cell(row.index(), 6).data(statusHtml).draw(false);
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
                            showConfirmButton: true,
                        });

                        let checkbox = $(`[data-post-id="${postId}"]`).closest('tr').find(
                            '.changeActive');
                        checkbox.prop('checked', !is_active);
                    }
                });

                console.log('Đã thay đổi trạng thái active');
            });
        });
    </script>
@endsection
