@extends('admin.layouts.master')

@section('title')
    Quản lý cấp bậc thẻ thành viên
@endsection

@section('style-libs')
    <!--datatable css-->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" />
    <!--datatable responsive css-->
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" />

    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css">
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Quản lý cấp bậc thẻ thành viên</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="">Cấp bậc thẻ thành viên</a></li>
                        <li class="breadcrumb-item active">Danh sách</li>
                    </ol>
                </div>

            </div>
        </div>
    </div>

    <!-- thông tin -->
    <div class="row">
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Thêm mới cấp bậc</h4>
                </div><!-- end card header -->

                <div class="card-body">
                    <div class="live-preview">
                        <form action="{{ route('admin.ranks.store') }}" method="post">
                            @csrf
                            <div class="row ">
                                <div class="mb-2">
                                    <label for="name" class="form-label">
                                        <span class="text-danger">*</span>Tên cấp bậc:
                                    </label>
                                    <input type="text" class='form-control' id="name" name="name"
                                        value="{{ old('name') }}" placeholder="Nhập tên cấp bậc">
                                    <div class="form-text">
                                        Tên hiển thị của cấp bậc thẻ thành viên.
                                    </div>
                                    @error('name')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="mb-2">
                                    <label for="total_spent" class="form-label">
                                        <span class="text-danger">*</span>Tổng chi tiêu:
                                    </label>
                                    <input type="number" class='form-control' id="total_spent" name="total_spent"
                                        value="{{ old('total_spent') }}" placeholder="Nhập tổng chi tiêu">
                                    <div class="form-text">Tổng số tiền(VNĐ) chi tiêu để đạt được cấp bậc đó.
                                    </div>
                                    @error('total_spent')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="mb-2">
                                    <label for="ticket_percentage" class="form-label">
                                        <span class="text-danger">*</span>Phần trăm vé:
                                    </label>
                                    <input type="number" class='form-control' id="ticket_percentage"
                                        value="{{ old('ticket_percentage') }}" name="ticket_percentage"
                                        placeholder="Nhập phần trăm vé">
                                    <div class="form-text">Tỷ lệ phần trăm(%) điểm tích lũy nhận được khi đặt vé.</div>
                                    @error('ticket_percentage')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="mb-2">
                                    <label for="combo_percentage" class="form-label">
                                        <span class="text-danger">*</span>Phần trăm combo:
                                    </label>
                                    <input type="number" class='form-control' id="combo_percentage" name="combo_percentage"
                                        value="{{ old('combo_percentage') }}" placeholder="Nhập phần trăm combo ">
                                    <div class="form-text">Tỷ lệ phần trăm(%) điểm tích lũy nhận được khi đặt combo.</div>
                                    @error('combo_percentage')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="text-end mt-2">
                                    <button type="submit" class="btn btn-primary mx-1">Thêm mới</button>
                                </div>

                            </div>
                        </form>

                        <!--end row-->
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">

                        <div class="card-header align-items-center d-flex">
                            <h4 class="card-title mb-0 flex-grow-1">Danh sách cập bậc</h4>
                        </div><!-- end card header -->

                        <div class="card-body ">
                            <table class="table table-bordered dt-responsive nowrap w-100" id="example">
                                <thead class='table-light'>
                                    <tr>
                                        <th>#</th>
                                        <th>Cấp bậc</th>
                                        <th>Tổng chi tiêu</th>
                                        <th>% vé</th>
                                        <th>% combo</th>
                                        <th>Ngày tạo</th>
                                        <th>Ngày cập nhật</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($ranks as $index => $rank)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>
                                                <div class='room-name'>
                                                    <div class='mb-1'>{{ $rank->name }} {!! $rank->is_default ? '<span class="text-black-50 small">(Mặc định)</span>' : null !!}
                                                    </div>
                                                    <div>

                                                        <a class="cursor-pointer text-info small openUpdateRankModal"
                                                            data-rank-id="{{ $rank->id }}"
                                                            data-rank-name="{{ $rank->name }}"
                                                            data-rank-total-spent="{{ $rank->total_spent }}"
                                                            data-rank-ticket-percentage="{{ $rank->ticket_percentage }}"
                                                            data-rank-combo-percentage={{ $rank->combo_percentage }}
                                                            data-rank-default="{{ $rank->is_default }}">Sửa</a>


                                                        @if (!$rank->is_default)
                                                            <a href="{{ route('admin.ranks.destroy', $rank) }}"
                                                                class="cursor-pointer  mx-1 text-danger small"
                                                                onclick="return confirm('Bạn có chắc chắn muốn xóa ?')">Xóa</a>
                                                        @endif

                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ number_format($rank->total_spent, 0, ',', '.') }} VNĐ</td>
                                            <td>{{ $rank->ticket_percentage }}%</td>
                                            <td>{{ $rank->combo_percentage }}%</td>
                                            <td class="small">{{ $rank->created_at->format('d/m/Y') }}
                                                <br>{{ $rank->created_at->format('H:i:s') }}
                                            </td>
                                            <td class="small">{{ $rank->updated_at->format('d/m/Y') }}
                                                <br>{{ $rank->updated_at->format('H:i:s') }}
                                            </td>
                                        </tr>
                                    @endforeach


                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--end col-->
    </div>
    <!-- Modal Cập nhật cấp bậc -->
    <div class="modal fade" id="updateRankModal" tabindex="-1" aria-labelledby="updateRankModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="updateRankModalLabel">Cập nhật cấp bậc <span
                            class="badge bg-primary-subtle text-primary fs-11" id="spanDefault"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="updateRankForm">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <input type="hidden" id="updateRankId" name="rank_id">
                            <div class="col-md-12 mb-3">
                                <label for="updateName" class="form-label"><span class="text-danger">*</span> Tên
                                    cấp bậc:</label>
                                <input type="text" class="form-control" id="updateName" name="name" required
                                    placeholder="Nhập tên cấp bậc">
                                <span class="text-danger mt-3" id="updateNameError"></span>
                            </div>
                            <div class="col-md-12 mb-3">
                                <label for="updateTotalSpent" class="form-label"><span class="text-danger">*</span> Tổng
                                    chi tiêu<span class="text-muted small">(VNĐ)</span>:</label>
                                <input type="text" class="form-control" id="updateTotalSpent" name="total_spent"
                                    required placeholder="Nhập tổng chi tiêu">
                                <span class="text-danger mt-3" id="updateTotalSpentError"></span>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="updateTicketPercentage" class="form-label"><span class="text-danger">*</span>
                                    Phần trăm vé<span class="text-muted small">(%)</span>:</label>
                                <input type="text" class="form-control" id="updateTicketPercentage"
                                    name="ticket_percentage" required placeholder="Nhập phần trăm vé">
                                <span class="text-danger mt-3" id="updateTicketPercentageError"></span>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="updateComboPercentage" class="form-label"><span class="text-danger">*</span>
                                    Phần trăm combo<span class="text-muted small">(%)</span>:</label>
                                <input type="text" class="form-control" id="updateComboPercentage"
                                    name="combo_percentage" required placeholder="Nhập phần trăm combo">
                                <span class="text-danger mt-3" id="updateComboPercentageError"></span>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Đóng</button>
                    <button type="button" class="btn btn-primary" id="updateRankBtn">Cập nhật</button>
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
        function filterPermissions() {
            const searchValue = document.getElementById("search-permission").value.toLowerCase();
            const permissionItems = document.querySelectorAll(".form-check");

            permissionItems.forEach(item => {
                const label = item.querySelector("label").textContent.toLowerCase();
                if (label.includes(searchValue)) {
                    item.style.display = "block"; // Hiển thị mục nếu khớp
                } else {
                    item.style.display = "none"; // Ẩn mục nếu không khớp
                }
            });
        }
        document.querySelectorAll('.openUpdateRankModal').forEach(button => {
            button.addEventListener('click', function() {
                const rankId = this.getAttribute(
                    'data-rank-id'); // Lấy roomId từ data attribute
                const rankName = this.getAttribute('data-rank-name');
                const rankTotalSpent = this.getAttribute('data-rank-total-spent');
                const rankTicketPercentage = this.getAttribute('data-rank-ticket-percentage');
                const rankComboPercentage = this.getAttribute('data-rank-combo-percentage');
                const rankDefault = this.getAttribute('data-rank-default');

                // Điền dữ liệu vào modal
                document.getElementById('updateRankId').value = rankId; // Gán giá trị roomId
                document.getElementById('updateName').value = rankName;
                document.getElementById('updateTotalSpent').value = rankTotalSpent;
                document.getElementById('updateTicketPercentage').value = rankTicketPercentage;
                document.getElementById('updateComboPercentage').value = rankComboPercentage;

                if (rankDefault == 1) {
                    document.getElementById("spanDefault").innerText = "Mặc định";
                    document.getElementById('updateTotalSpent').disabled = true;
                } else {
                    // Nếu chưa publish, cho phép chỉnh sửa tất cả
                    document.getElementById('updateTotalSpent').disabled = false;
                }
                // Reset các lỗi trước khi mở modal
                resetErrors('update');
                // Mở modal

                $('#updateRankModal').modal('show');
            });
        });

        document.getElementById('updateRankBtn').addEventListener('click', function(event) {
            const form = document.getElementById('updateRankForm');
            const formData = new FormData(form);
            console.log([...formData]);
            const rankId = document.getElementById('updateRankId').value; // Lấy ID phòng từ hidden input
            let hasErrors = false; // Biến để theo dõi có lỗi hay không

            const url = '{{ route('admin.ranks.update', ':id') }}'.replace(':id', rankId);
            fetch(url, {
                    method: 'POST',
                    body: formData,

                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(errorData => {
                            handleErrors(errorData.errors, 'update'); // Gọi hàm xử lý lỗi
                            hasErrors = true; // Đánh dấu có lỗi
                        });
                    }
                    return response.json(); // Chuyển đổi phản hồi thành JSON
                })
                .then(data => {
                    if (!hasErrors) {
                        console.log(data);
                        $('#updateRankModal').modal('hide');
                        form.reset();
                        location.reload();

                    }

                })
                .catch(error => console.error('Error updating room:', error));
        });

        function handleErrors(errors, prefix) {
            // Reset thông báo lỗi trước đó
            document.getElementById(`${prefix}NameError`).innerText = '';
            document.getElementById(`${prefix}TotalSpentError`).innerText = '';
            document.getElementById(`${prefix}TicketPercentageError`).innerText = '';
            document.getElementById(`${prefix}ComboPercentageError`).innerText = '';

            // Kiểm tra và hiển thị lỗi cho từng trường
            if (errors.name) {
                document.getElementById(`${prefix}NameError`).innerText = errors.name.join(', ');
            }
            if (errors.total_spent) {
                document.getElementById(`${prefix}TotalSpentError`).innerText = errors.total_spent.join(', ');
            }
            if (errors.ticket_percentage) {
                document.getElementById(`${prefix}TicketPercentageError`).innerText = errors.ticket_percentage.join(', ');
            }
            if (errors.combo_percentage) {
                document.getElementById(`${prefix}ComboPercentageError`).innerText = errors.combo_percentage.join(', ');
            }
        }

        function resetErrors(prefix) {
            // Reset thông báo lỗi trước đó
            document.getElementById(`${prefix}NameError`).innerText = '';
            document.getElementById(`${prefix}TotalSpentError`).innerText = '';
            document.getElementById(`${prefix}TicketPercentageError`).innerText = '';
            document.getElementById(`${prefix}ComboPercentageError`).innerText = '';
        }
    </script>
@endsection
