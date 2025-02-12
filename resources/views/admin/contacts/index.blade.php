@extends('admin.layouts.master')

@section('title')
    Liên hệ
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
                <h4 class="mb-sm-0">Liên hệ</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Liên hệ</a></li>
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
                    <h5 class="card-title mb-0">Danh sách liên hệ</h5>
                    {{-- <a href="{{ route('admin.contacts.create') }}" class="btn btn-primary">Thêm mới</a> --}}
                </div>
                @if (session()->has('success'))
                    <div class="alert alert-success m-3">
                        {{ session()->get('success') }}
                    </div>
                @endif

                <div class="card-body">
                    <table id="example"  class="table table-bordered dt-responsive nowrap align-middle w-100">
                        <thead class='table-light'>
                            <tr>
                                <th>#</th>
                                <th>Thông tin liên hệ</th>
                                <th>Tiêu đề</th>
                                <th>Ngày tạo</th>
                                <th>Trạng thái</th>
                                <th>Chức năng</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($contacts as $contact)
                                <tr>
                                    <td>{{ $contact->id }}</td>
                                    <td>
                                        <strong>Họ tên:</strong> {{ $contact->user_contact }}<br>
                                        <strong>Email:</strong> {{ $contact->email }}<br>
                                        <strong>SĐT:</strong> {{ $contact->phone }}
                                    </td>
                                    <td>   {{ \Illuminate\Support\Str::limit($contact->title, 30, '...') }}</td>
                                    <td>{{ $contact->created_at->format('d/m/Y') }}<br>{{ $contact->created_at->format('H:i:s') }}</td>
                                    <td>
                                        <select class="form-select status-select" data-id="{{ $contact->id }}" {{ $contact->status == 'resolved' ? 'disabled' : '' }} >
                                            <option value="pending" {{ $contact->status == 'pending' ? 'selected' : '' }}>Chưa xử lí</option>
                                            <option value="resolved" {{ $contact->status == 'resolved' ? 'selected' : '' }}>Đã xử lí</option>
                                        </select>
                                    </td>

                                    
                                    <td>
                                        {{-- <a href="{{ route('admin.contacts.show',$contact) }}">
                                            <button title="xem" class="btn btn-success btn-sm " type="button"><i
                                                    class="fas fa-eye"></i></button></a> --}}

                                        <button class="btn btn-success btn-sm view-contact" data-id="{{ $contact->id }}" data-bs-toggle="modal" data-bs-target="#contactModal">
                                            <i class="fas fa-eye"></i>
                                        </button>

                                        {{-- <a href="{{ route('admin.contacts.edit', $contact)}}">
                                            <button title="Sửa" class="btn btn-warning btn-sm " type="button"><i
                                                    class="fas fa-edit"></i></button>
                                        </a> --}}
                                        {{-- <form action="{{route('admin.contacts.destroy', $contact)}}" method="POST" class="d-inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có muốn xóa không')">
                                                <i class="ri-delete-bin-7-fill"></i>
                                            </button>
                                        </form> --}}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div><!--end col-->
    </div><!--end row-->
    <!-- Modal -->
    <div class="modal fade" id="contactModal" tabindex="-1" aria-labelledby="contactModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="contactModalLabel">Thông tin liên hệ</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="contactDetails">
                        <!-- Thông tin liên hệ sẽ được tải vào đây qua AJAX -->
                    </div>
                </div>
            </div>
        </div>
    </div>

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
    <script>
        $(document).on('change', '.status-select', function () {
        let contactId = $(this).data('id');
        let newStatus = $(this).val();

        if (newStatus === 'resolved') {
            if (!confirm('Bạn có chắc chắn muốn chuyển trạng thái sang "Đã xử lí"?')) {
                // Nếu người dùng không xác nhận, đặt lại trạng thái về trạng thái trước đó
                $(this).val('pending');
                return;
            }
            $(this).prop('disabled', true); // Vô hiệu hóa dropdown nếu trạng thái là 'Đã xử lí'
        }

        $.ajax({
            url: `/admin/contacts/${contactId}/status`,
            type: 'PATCH',
            data: {
                status: newStatus,
                _token: '{{ csrf_token() }}'
            },
            success: function (response) {
                alert(response.success);
            },
            error: function (xhr) {
                alert('Cập nhật trạng thái thất bại.');
            }
        });
    });
    </script>
    <script>
        $(document).on('click', '.view-contact', function() {
            var contactId = $(this).data('id');

            // Gửi AJAX để lấy thông tin liên hệ
            $.ajax({
                url: '/admin/contacts/' + contactId, // Route để lấy thông tin chi tiết liên hệ
                type: 'GET',
                success: function(response) {
                    // Hiển thị thông tin vào modal
                    var contact = response.contact;
                    var contactDetails = `
                        <p><strong>Họ và tên:</strong> ${contact.user_contact}</p>
                        <p><strong>Email:</strong> ${contact.email}</p>
                        <p><strong>Số điện thoại:</strong> ${contact.phone}</p>
                        <p><strong>Tiêu đề:</strong> ${contact.title}</p>
                        <p><strong>Nội dung:</strong> ${contact.content}</p>
                        <p><strong>Ngày tạo:</strong> ${new Date(contact.created_at).toLocaleString('en-GB')}</p> <!-- Chuyển đổi ngày giờ -->
                        <p><strong>Trạng thái:</strong> ${contact.status == 'pending' ? 'Chưa xử lí' : 'Đã xử lí'}</p>
                    `;
                    $('#contactDetails').html(contactDetails);
                },
                error: function() {
                    alert('Không thể tải thông tin liên hệ.');
                }
            });
        });
    </script>
@endsection
