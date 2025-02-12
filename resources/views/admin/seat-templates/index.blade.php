@extends('admin.layouts.master')

@section('title')
    Danh sách sơ đồ ghế
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
    <!-- Button to trigger modal -->


    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Quản lý mẫu sơ đồ ghế</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Mẫu sơ đồ ghế</a></li>
                        <li class="breadcrumb-item active">Danh sách</li>
                    </ol>
                </div>

            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header border-0">
                    <div class="row align-items-center gy-3">
                        <div class="col-sm">
                            <h5 class="card-title mb-0">Danh sách mẫu sơ đồ ghế</h5>
                        </div>
                        @can('Thêm mẫu sơ đồ ghế')
                            <div class="col-sm-auto">
                                <div class="d-flex gap-1 flex-wrap">
                                    <button class="btn btn-primary mb-3 " data-bs-toggle="modal"
                                        data-bs-target="#createSeatTemplate">Thêm
                                        mới</button>
                                </div>
                            </div>
                        @endcan
                    </div>
                </div>
                <div class="card-body pt-0">

                    <ul class="nav nav-tabs nav-tabs-custom nav-success mb-3" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link  All  py-3  {{ session('seatTemplates.selected_tab') === 'all' ? 'active' : '' }}"
                                data-bs-toggle="tab" href="#allSeatTemplate" role="tab" aria-selected="true"
                                data-tab-key='all'>
                                Tất cả
                                <span class="badge bg-dark align-middle ms-1">{{ $seatTemplates->count() }}</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link py-3  isPublish {{ session('seatTemplates.selected_tab') === 'publish' ? 'active' : '' }}"
                                data-bs-toggle="tab" href="#isPublish" role="tab" aria-selected="false"
                                data-tab-key='publish'>
                                Đã xuất bản
                                <span
                                    class="badge bg-success align-middle ms-1">{{ $seatTemplates->where('is_publish', 1)->count() }}</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link py-3 isDraft {{ session('seatTemplates.selected_tab') === 'draft' ? 'active' : '' }}"
                                data-bs-toggle="tab" href="#isDraft" role="tab" aria-selected="false"
                                data-tab-key='draft'>
                                Bản nháp<span
                                    class="badge bg-warning align-middle ms-1">{{ $seatTemplates->where('is_publish', 0)->count() }}</span>
                            </a>
                        </li>
                    </ul>
                    <div class="card-body tab-content ">
                        <div class="tab-pane {{ session('seatTemplates.selected_tab') === 'all' ? 'active' : '' }} "
                            id="allSeatTemplate" role="tabpanel">
                            <table id="tableAllSeatTemplate"
                                class="table table-bordered dt-responsive nowrap align-middle w-100" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Tên mẫu</th>
                                        <th>Mô tả</th>
                                        <th>Ma trận ghế</th>
                                        <th>Trạng thái</th>
                                        <th>Hoạt động</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($seatTemplates as $item)
                                        <tr>
                                            <td>{{ $item->id }}</td>
                                            <td>
                                                <div class='seat-template-name'>
                                                    <div class='mb-1 fs-6'> {{ $item->name }}</div>
                                                    <div>
                                                        <a class=" cursor-pointer text-primary small"
                                                            href="{{ route('admin.seat-templates.edit', $item) }}">Sơ đồ
                                                            ghế</a>
                                                        @can('Sửa mẫu sơ đồ ghế')
                                                            <a class="cursor-pointer text-info small mx-1 openUpdateSeatTemplateModal"
                                                                data-seat-template-id="{{ $item->id }}"
                                                                data-seat-template-name="{{ $item->name }}"
                                                                data-seat-template-description="{{ $item->description }}"
                                                                data-matrix-id="{{ $item->matrix_id }}"
                                                                data-seat-template-row-regular="{{ $item->row_regular }}"
                                                                data-seat-template-row-vip="{{ $item->row_vip }}"
                                                                data-seat-template-row-double="{{ $item->row_double }}"
                                                                data-is-publish={{ $item->is_publish }}>Sửa</a>
                                                        @endcan






                                                        {{-- @if (!$item->is_publish || $item->rooms()->doesntExist())
                                                            @can('Xóa mẫu sơ đồ ghế')
                                                                <a class="cursor-pointer text-danger small"
                                                                    href="{{ route('admin.seat-templates.destroy', $item) }}"
                                                                    onclick="return confirm('Sau khi xóa sẽ không thể khôi phục, bạn có chắc chắn ?')">Xóa</a>
                                                            @endcan
                                                        @endif --}}
                                                    </div>
                                                </div>
                                            </td>


                                            <td>{{ $item->description }}</td>
                                            @php
                                                $matrixSeatTemplate = App\Models\SeatTemplate::getMatrixById(
                                                    $item->matrix_id,
                                                );
                                            @endphp
                                            <td>{{ $matrixSeatTemplate['name'] }}</td>
                                            <td>
                                                {!! $item->is_publish == 1
                                                    ? '<span class="badge bg-success-subtle text-success">Đã xuất bản</span>'
                                                    : '<span class="badge bg-danger-subtle text-danger">Bản nháp</span>' !!}
                                            </td>
                                            <td>


                                                @can('Sửa mẫu sơ đồ ghế')
                                                    <div class="form-check form-switch form-switch-success">
                                                        <input class="form-check-input switch-is-active changeActive"
                                                            name="is_active" type="checkbox" role="switch"
                                                            data-id="{{ $item->id }}" @checked($item->is_active)
                                                            onclick="return confirm('Bạn có chắc muốn thay đổi ?')"
                                                            @disabled(!$item->is_publish)>
                                                    </div>
                                                @else
                                                    <div class="form-check form-switch form-switch-success">
                                                        <input class="form-check-input switch-is-active changeActive"
                                                            type="checkbox" role="switch" disabled readonly
                                                            @checked($item->is_active)>
                                                    </div>
                                                @endcan
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>

                            </table>
                        </div>
                        <div class="tab-pane {{ session('seatTemplates.selected_tab') === 'publish' ? 'active' : '' }} "
                            id="isPublish" role="tabpanel">
                            <table id="tableIsPublish" class="table table-bordered dt-responsive nowrap align-middle w-100">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Tên mẫu</th>
                                        <th>Mô tả</th>
                                        <th>Ma trận ghế</th>
                                        <th>Trạng thái</th>
                                        <th>Hoạt động</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($seatTemplates->where('is_publish', 1) as $item)
                                        <tr>
                                            <td>{{ $item->id }}</td>
                                            <td>
                                                <div class='seat-template-name'>
                                                    <div class='mb-1 fs-6'> {{ $item->name }}</div>
                                                    <div>
                                                        <a class=" cursor-pointer text-primary small"
                                                            href="{{ route('admin.seat-templates.edit', $item) }}">Sơ đồ
                                                            ghế</a>
                                                        @can('Sửa mẫu sơ đồ ghế')
                                                            <a class="cursor-pointer text-info small mx-1 openUpdateSeatTemplateModal"
                                                                data-seat-template-id="{{ $item->id }}"
                                                                data-seat-template-name="{{ $item->name }}"
                                                                data-seat-template-description="{{ $item->description }}"
                                                                data-matrix-id="{{ $item->matrix_id }}"
                                                                data-seat-template-row-regular="{{ $item->row_regular }}"
                                                                data-seat-template-row-vip="{{ $item->row_vip }}"
                                                                data-seat-template-row-double="{{ $item->row_double }}"
                                                                data-is-publish={{ $item->is_publish }}>Sửa</a>
                                                        @endcan


                                                        {{-- @if (!$item->is_publish || $item->rooms()->doesntExist())
                                                            @can('Xóa mẫu sơ đồ ghế')
                                                                <a class="cursor-pointer text-danger small"
                                                                    href="{{ route('admin.seat-templates.destroy', $item) }}"
                                                                    onclick="return confirm('Sau khi xóa sẽ không thể khôi phục, bạn có chắc chắn ?')">Xóa</a>
                                                            @endcan
                                                        @endif --}}

                                                    </div>
                                                </div>
                                            </td>

                                            <td style="width: 30%">
                                                {{ $item->description }}
                                            </td>
                                            @php
                                                $matrixSeatTemplate = App\Models\SeatTemplate::getMatrixById(
                                                    $item->matrix_id,
                                                );
                                            @endphp
                                            <td>{{ $matrixSeatTemplate['name'] }}</td>
                                            <td>
                                                {!! $item->is_publish == 1
                                                    ? '<span class="badge bg-success-subtle text-success">Đã xuất bản</span>'
                                                    : '<span class="badge bg-danger-subtle text-danger">Bản nháp</span>' !!}
                                            </td>
                                            <td>


                                                @can('Sửa mẫu sơ đồ ghế')
                                                    <div class="form-check form-switch form-switch-success">
                                                        <input class="form-check-input switch-is-active changeActive"
                                                            name="is_active" type="checkbox" role="switch"
                                                            data-id="{{ $item->id }}" @checked($item->is_active)
                                                            onclick="return confirm('Bạn có chắc muốn thay đổi ?')"
                                                            @disabled(!$item->is_publish)>
                                                    </div>
                                                @else
                                                    <div class="form-check form-switch form-switch-success">
                                                        <input class="form-check-input switch-is-active changeActive"
                                                            type="checkbox" role="switch" disabled readonly
                                                            @checked($item->is_active)>
                                                    </div>
                                                @endcan
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>

                            </table>
                        </div>
                        <div class="tab-pane {{ session('seatTemplates.selected_tab') === 'draft' ? 'active' : '' }}"
                            id="isDraft" role="tabpanel">

                            <table id="tableIsDraft" class="table table-bordered dt-responsive nowrap align-middle w-100"
                                style="width:100%">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Tên mẫu</th>
                                        <th>Mô tả</th>
                                        <th>Ma trận ghế</th>
                                        <th>Trạng thái</th>
                                        <th>Hoạt động</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($seatTemplates->where('is_publish', 0) as $item)
                                        <tr>
                                            <td>{{ $item->id }}</td>
                                            <td>
                                                <div class='seat-template-name'>
                                                    <div class='mb-1 fs-6'> {{ $item->name }}</div>
                                                    <div>
                                                        <a class=" cursor-pointer text-primary small"
                                                            href="{{ route('admin.seat-templates.edit', $item) }}">Sơ đồ
                                                            ghế</a>
                                                        @can('Sửa mẫu sơ đồ ghế')
                                                            <a class="cursor-pointer text-info small mx-1 openUpdateSeatTemplateModal"
                                                                data-seat-template-id="{{ $item->id }}"
                                                                data-seat-template-name="{{ $item->name }}"
                                                                data-seat-template-description="{{ $item->description }}"
                                                                data-matrix-id="{{ $item->matrix_id }}"
                                                                data-seat-template-row-regular="{{ $item->row_regular }}"
                                                                data-seat-template-row-vip="{{ $item->row_vip }}"
                                                                data-seat-template-row-double="{{ $item->row_double }}"
                                                                data-is-publish={{ $item->is_publish }}>Sửa</a>
                                                        @endcan



                                                        @if (!$item->is_publish || $item->rooms()->doesntExist())
                                                            @can('Xóa mẫu sơ đồ ghế')
                                                                <a class="cursor-pointer text-danger small"
                                                                    href="{{ route('admin.seat-templates.destroy', $item) }}"
                                                                    onclick="return confirm('Sau khi xóa sẽ không thể khôi phục, bạn có chắc chắn ?')">Xóa</a>
                                                            @endcan
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>

                                            <td>{{ $item->description }}</td>
                                            @php
                                                $matrixSeatTemplate = App\Models\SeatTemplate::getMatrixById(
                                                    $item->matrix_id,
                                                );
                                            @endphp
                                            <td>{{ $matrixSeatTemplate['name'] }}</td>
                                            <td>
                                                {!! $item->is_publish == 1
                                                    ? '<span class="badge bg-success-subtle text-success">Đã xuất bản</span>'
                                                    : '<span class="badge bg-danger-subtle text-danger">Bản nháp</span>' !!}
                                            </td>
                                            <td>
                                                @can('Sửa mẫu sơ đồ ghế')
                                                    <div class="form-check form-switch form-switch-success">
                                                        <input class="form-check-input switch-is-active changeActive"
                                                            name="is_active" type="checkbox" role="switch"
                                                            data-id="{{ $item->id }}" @checked($item->is_active)
                                                            onclick="return confirm('Bạn có chắc muốn thay đổi ?')"
                                                            @disabled(!$item->is_publish)>
                                                    </div>
                                                @else
                                                    <div class="form-check form-switch form-switch-success">
                                                        <input class="form-check-input switch-is-active changeActive"
                                                            type="checkbox" role="switch" disabled readonly
                                                            @checked($item->is_active)>
                                                    </div>
                                                @endcan
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
    </div>

    <!--Modal thêm mới mẫu sơ đồ ghế-->
    <div class="modal fade" id="createSeatTemplate" tabindex="-1" aria-labelledby="createSeatTemplateLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createSeatTemplateLabel">Thêm mới mẫu sơ đồ ghế</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="createSeatTemplateForm">
                        @csrf
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="name" class="form-label"><span class="text-danger">*</span> Tên
                                    mẫu</label>
                                <input type="text" class="form-control" id="name" name="name" required
                                    placeholder="Tiêu chuẩn">
                                <span class="text-danger mt-3" id="createNameError"></span> <!-- Thêm thông báo lỗi -->
                            </div>
                            <div class="col-md-12 mb-3">
                                <label for="matrix_id" class="form-label"><span class="text-danger">*</span> Ma trận
                                    ghế</label>
                                <select class="form-select" id="createMatrixId" name="matrix_id" required>
                                    @foreach (App\Models\SeatTemplate::MATRIXS as $matrix)
                                        <option value="{{ $matrix['id'] }}">{{ $matrix['name'] }} -
                                            {{ $matrix['description'] }}</option>
                                    @endforeach
                                </select>
                                <span class="text-danger mt-3" id="createMatrixSeatError"></span>
                            </div>
                            <div class='col-md-12 mb-3'>
                                <div class="row">
                                    <div class="col-md-4 ">
                                        <label for="row_regular" class="form-label"><span class="text-danger">*</span>
                                            Hàng
                                            ghế thường</label>
                                        <input type="number" class="form-control" id="createRowRegular"
                                            name="row_regular" required value="4">
                                        <span class="text-danger mt-3" id="createRowRegularError"></span>
                                    </div>
                                    <div class="col-md-4 ">
                                        <label for="row_vip" class="form-label"><span class="text-danger">*</span> Hàng
                                            ghế vip</label>
                                        <input type="number" class="form-control " id="createRowVip" name="row_vip"
                                            required value="6">
                                        <span class="text-danger mt-3" id="createRowVipError"></span>
                                    </div>
                                    <div class="col-md-4 ">
                                        <label for="row_double" class="form-label"><span class="text-danger">*</span>
                                            Hàng
                                            ghế đôi</label>
                                        <input type="number" class="form-control" id="createRowDouble"
                                            name="row_double" required value="2">
                                        <span class="text-danger mt-3" id="createRowDoubleError"></span>
                                    </div>
                                    <span class="text-danger mt-1" id="createRowError"></span>
                                </div>
                            </div>

                            <div class="col-md-12 mb-3">
                                <label for="name" class="form-label"><span class="text-danger">*</span> Mô tả</label>
                                <textarea name="description" class='form-control' rows="3"
                                    placeholder="Mẫu sơ đồ ghế tiêu chuẩn: 4 hàng hế thường, 8 hàng ghế vip."></textarea>
                                <span class="text-danger mt-3" id="createDescriptionError"></span>
                                <!-- Thêm thông báo lỗi -->
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Đóng</button>
                    <button type="button" class="btn btn-primary" id="createSeatTemplateBtn">Thêm mới</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal cập nhật mẫu sơ đồ ghế -->
    <div class="modal fade" id="updateSeatTemplateModal" tabindex="-1" aria-labelledby="updateSeatTemplateModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="updateSeatTemplateModalLabel">Cập Nhật Mẫu Sơ Đồ Ghế</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="updateSeatTemplateForm">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <input type="hidden" id="updateSeatTemplateId" name="seat_template_id">
                            <div class="col-md-12 mb-3">
                                <label for="updateName" class="form-label"><span class="text-danger">*</span> Tên
                                    mẫu</label>
                                <input type="text" class="form-control" id="updateName" name="name" required
                                    placeholder="Poly 202">
                                <span class="text-danger mt-3" id="updateNameError"></span>
                            </div>

                            <div class="col-md-12 mb-3">
                                <label for="updateMatrixId" class="form-label"><span class="text-danger">*</span> Ma trận
                                    ghế</label>
                                <select class="form-select" id="updateMatrixId" name="matrix_id" required>
                                    @foreach (App\Models\SeatTemplate::MATRIXS as $matrix)
                                        <option value="{{ $matrix['id'] }}">{{ $matrix['name'] }} -
                                            {{ $matrix['description'] }}</option>
                                    @endforeach
                                </select>
                                <span class="text-danger mt-3" id="updateMatrixSeatError"></span>
                            </div>
                            <div class="col-md-12 mb-3">
                                <div class="row">
                                    <div class="col-md-4">
                                        <label for="row_regular" class="form-label"><span class="text-danger">*</span>
                                            Hàng
                                            ghế thường</label>
                                        <input type="number" class="form-control" id="updateRowRegular"
                                            name="row_regular" required value="">
                                        <span class="text-danger mt-3" id="updateRowRegularError" value="4"></span>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="row_vip" class="form-label"><span class="text-danger">*</span> Hàng
                                            ghế vip</label>
                                        <input type="number" class="form-control" id="updateRowVip" name="row_vip"
                                            required value="6">
                                        <span class="text-danger mt-3" id="updateRowVipError"></span>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="row_double" class="form-label"><span class="text-danger">*</span>
                                            Hàng
                                            ghế đôi</label>
                                        <input type="number" class="form-control" id="updateRowDouble"
                                            name="row_double" required value="2">
                                        <span class="text-danger mt-3" id="updateRowDoubleError"></span>
                                    </div>
                                    <span class="text-danger mt-1" id="updateRowError"></span>
                                </div>
                            </div>

                            <div class="col-md-12 mb-3">
                                <label for="name" class="form-label"><span class="text-danger">*</span> Mô tả</label>
                                <textarea name="description" class='form-control' rows="3" id="updateDescription"
                                    placeholder="Mẫu sơ đồ ghế tiêu chuẩn: 4 hàng hế thường, 8 hàng ghế vip."></textarea>
                                <span class="text-danger mt-3" id="updateDescriptionError"></span>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Đóng</button>
                    <button type="button" class="btn btn-primary" id="updateSeatTemplateBtn">Cập nhật</button>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('script-libs')
    <script>
        const matrixs = @json(App\Models\SeatTemplate::MATRIXS);
    </script>



    {{-- Hàm load các rạp chiếu khi chọn chi nhánh & modal create rạp chiếu --}}
    <script>
        document.getElementById('createSeatTemplateBtn').addEventListener('click', function(event) {
            const form = document.getElementById('createSeatTemplateForm');
            const formData = new FormData(form);
            let hasErrors = false; // Biến để theo dõi có lỗi hay không
            const url = APP_URL + `/api/seat-templates/store`
            fetch(url, {
                    method: 'POST',
                    body: formData,
                })
                .then(response => {
                    if (!response.ok) {
                        // Nếu có lỗi (400, 422, 500, ...), chuyển đến phần xử lý lỗi
                        return response.json().then(errorData => {
                            handleErrors(errorData.errors, 'create'); // Gọi hàm xử lý lỗi
                            hasErrors = true; // Đánh dấu có lỗi
                        });
                    }
                    return response.json(); // Chuyển đổi phản hồi thành JSON
                })
                .then(data => {
                    if (!hasErrors) { // Chỉ đóng modal và reset form khi không có lỗi
                        console.log(data);
                        $('#createSeatTemplateModal').modal('hide');
                        form.reset();
                        window.location.reload();
                        window.location.href =
                            `${APP_URL}/admin/seat-templates/${data.seatTemplate.id}/edit`;
                    }
                })
                .catch(error => console.error('Error adding room:', error));
        });
        document.querySelectorAll('.openUpdateSeatTemplateModal').forEach(button => {
            button.addEventListener('click', function() {

                const seatTemplateId = this.getAttribute(
                    'data-seat-template-id'); // Lấy roomId từ data attribute
                const seatTemplateName = this.getAttribute('data-seat-template-name');
                const seatTemplateDescription = this.getAttribute('data-seat-template-description');
                const matrixId = this.getAttribute('data-matrix-id');
                const rowRegular = this.getAttribute('data-seat-template-row-regular');
                const rowVip = this.getAttribute('data-seat-template-row-vip');
                const rowDouble = this.getAttribute('data-seat-template-row-double');
                const isPublish = this.getAttribute('data-is-publish');

                // Điền dữ liệu vào modal
                document.getElementById('updateSeatTemplateId').value =
                    seatTemplateId; // Gán giá trị roomId
                document.getElementById('updateName').value = seatTemplateName;
                document.getElementById('updateDescription').value = seatTemplateDescription;
                document.getElementById('updateMatrixId').value = matrixId;
                document.getElementById(`updateRowRegular`).value = rowRegular;
                document.getElementById(`updateRowVip`).value = rowVip;
                document.getElementById(`updateRowDouble`).value = rowDouble;

                if (isPublish == 1) {
                    document.getElementById('updateMatrixId').disabled = true;
                    document.getElementById('updateRowRegular').disabled = true;
                    document.getElementById('updateRowVip').disabled = true;
                    document.getElementById('updateRowDouble').disabled = true;
                } else {
                    // Nếu chưa publish, cho phép chỉnh sửa tất cả
                    document.getElementById('updateMatrixId').disabled = false;
                    document.getElementById('updateRowRegular').disabled = false;
                    document.getElementById('updateRowVip').disabled = false;
                    document.getElementById('updateRowDouble').disabled = false;
                }

                // Mở modal

                $('#updateSeatTemplateModal').modal('show');
            });
        });

        // Hàm để cập nhật thông tin phòng chiếu
        document.getElementById('updateSeatTemplateBtn').addEventListener('click', function(event) {
            const form = document.getElementById('updateSeatTemplateForm');
            const formData = new FormData(form);
            console.log([...formData]);
            const seatTemplateId = document.getElementById('updateSeatTemplateId')
                .value; // Lấy ID phòng từ hidden input
            let hasErrors = false; // Biến để theo dõi có lỗi hay không
            const url = APP_URL + `/api/seat-templates/${seatTemplateId}`; // URL cập nhật phòng chiếu

            fetch(url, {
                    method: 'POST',
                    body: formData,

                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(errorData => {
                            handleErrors(errorData.error, 'update'); // Gọi hàm xử lý lỗi
                            hasErrors = true; // Đánh dấu có lỗi
                        });
                    }
                    return response.json(); // Chuyển đổi phản hồi thành JSON
                })
                .then(data => {
                    if (!hasErrors) {
                        console.log(data);
                        $('#updateSeatTemplateModal').modal('hide');
                        location.reload();

                    }

                })
                .catch(error => console.error('Error updating room:', error));
        });

        function handleErrors(errors, prefix) {
            // Reset thông báo lỗi trước đó
            document.getElementById(`${prefix}NameError`).innerText = '';
            document.getElementById(`${prefix}MatrixSeatError`).innerText = '';

            document.getElementById(`${prefix}DescriptionError`).innerText = '';

            // Kiểm tra và hiển thị lỗi cho từng trường
            if (errors.name) {
                document.getElementById(`${prefix}NameError`).innerText = errors.name.join(', ');
            }
            if (errors.description) {
                document.getElementById(`${prefix}DescriptionError`).innerText = errors.description.join(', ');
            }
            if (errors.matrix_id) {
                document.getElementById(`${prefix}MatrixSeatError`).innerText = errors.matrix_id.join(', ');
            }
            // if (errors.row_regular) {
            //     console.log('row_rgl');
            //     document.getElementById(`${prefix}RowRegularError`).innerText = errors.row_regular.join(', ');
            // }
            // if (errors.row_vip) {
            //     console.log('row_vip');

            //     document.getElementById(`${prefix}RowVipError`).innerText = errors.row_vip.join(', ');
            // }
            // if (errors.row_double) {
            //     console.log('row_db');
            //     document.getElementById(`${prefix}RowDoubleError`).innerText = errors.row_double.join(', ');
            // }
            if (errors.row_regular) {
                document.getElementById(`${prefix}RowRegular`).classList.add('is-invalid');
            } else {
                document.getElementById(`${prefix}RowRegular`).classList.remove('is-invalid');
            }
            if (errors.row_vip) {
                document.getElementById(`${prefix}RowVip`).classList.add('is-invalid');
            } else {
                document.getElementById(`${prefix}RowVip`).classList.remove('is-invalid');
            }
            if (errors.row_double) {
                document.getElementById(`${prefix}RowDouble`).classList.add('is-invalid');
            } else {
                document.getElementById(`${prefix}RowDouble`).classList.remove('is-invalid');
            }
            if (errors.rows) {
                document.getElementById(`${prefix}RowError`).innerText = errors.rows[0];
            } else {
                document.getElementById(`${prefix}RowError`).innerText = '';
            }
        }
    </script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

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

                fetch('{{ route('admin.seat-templates.selected-tab') }}', {
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
            let tableAllSeatTemplate = new DataTable("#tableAllSeatTemplate", {
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
            $(document).on('change', '.changeActive', function() {
                let seatTemplateId = $(this).data('id');
                let is_active = $(this).is(':checked') ? 1 : 0;
                let tableId = $(this).closest('table').attr('id'); // Lấy ID của bảng


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
                    url: '{{ route('seat-templates.change-active') }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        id: seatTemplateId,
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
                            let row = table.row($(`[data-id="${seatTemplateId}"]`).closest(
                                'tr'));
                            console.log(row);

                            let statusHtml = response.data.is_active ?
                                `<div class="form-check form-switch form-switch-success">
                                <input class="form-check-input switch-is-active changeActive"
                                    type="checkbox" data-id="${seatTemplateId}" checked onclick="return confirm('Bạn có chắc muốn thay đổi ?')">
                            </div>` :
                                `<div class="form-check form-switch form-switch-success">
                                <input class="form-check-input switch-is-active changeActive"
                                    type="checkbox" data-id="${seatTemplateId}" onclick="return confirm('Bạn có chắc muốn thay đổi ?')">
                            </div>`;

                            updateStatusInTable(table, seatTemplateId, statusHtml);

                            // Cập nhật trạng thái cho các bảng còn lại
                            if (tableId !== 'tableAllSeatTemplate') {
                                updateStatusInTable(tableAllSeatTemplate, seatTemplateId,
                                    statusHtml);
                            }
                            if (tableId !== 'tableIsPublish') {
                                updateStatusInTable(tableIsPublish, seatTemplateId, statusHtml);
                            }
                            if (tableId !== 'tableIsDraft') {
                                updateStatusInTable(tableIsDraft, seatTemplateId, statusHtml);
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
                            showConfirmButton: true,
                        });

                        let checkbox = $(`[data-id="${seatTemplateId}"]`).closest('tr').find(
                            '.changeActive');
                        checkbox.prop('checked', !is_active);
                    }
                });
                console.log('Đã thay đổi trạng thái active');
            });
        });

        function updateStatusInTable(table, seatTemplateId, statusHtml) {
            // Cập nhật trạng thái trong bảng
            table.rows().every(function() {
                let row = this.node();
                let rowId = $(row).find('.changeActive').data('id');
                if (rowId === seatTemplateId) {
                    table.cell(row, 5).data(statusHtml).draw(false);
                }
            });
        }
    </script>


    {{-- ajax load matrix --}}
    <script>
        $(document).ready(function() {
            // Lắng nghe sự kiện change cho cả hai select (create và update)
            $('#createMatrixId, #updateMatrixId').on('change', function() {
                const selectedId = $(this).val(); // Lấy ID từ select
                const target = $(this).attr('id'); // Xác định ID của select đang thay đổi

                if (selectedId !== "") {
                    // Tìm ma trận có id khớp với selectedId
                    const selectedMatrix = matrixs.find(matrix => matrix.id == selectedId);

                    if (selectedMatrix) {
                        // Xác định prefix của form (create hoặc update)
                        const prefix = target === 'createMatrixId' ? 'create' : 'update';

                        // Cập nhật giá trị vào các input tương ứng
                        $(`#${prefix}RowRegular`).val(selectedMatrix.row_default.regular);
                        $(`#${prefix}RowVip`).val(selectedMatrix.row_default.vip);
                        $(`#${prefix}RowDouble`).val(selectedMatrix.row_default.double);
                    }
                }
            });
        });
    </script>
@endsection
