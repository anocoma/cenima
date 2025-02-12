@extends('admin.layouts.master')

@section('title')
    Cập nhật combo {{ $combo->name }}
@endsection

@section('content')
    <form id="comboForm" action="{{ route('admin.combos.update', $combo) }}" method="post" enctype="multipart/form-data">
        @csrf
        @method('put')

        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Quản lý combo</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.combos.index') }}">Danh sách</a></li>
                            <li class="breadcrumb-item active">Cập nhật</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <!-- thông tin -->
        <div class="row">
            <div class="col-lg-9">
                <div class="card">
                    <div class="card-header align-items-center d-flex">
                        <h4 class="card-title mb-0 flex-grow-1">Thông tin combo</h4>
                    </div><!-- end card header -->

                    @if (session()->has('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session()->has('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <meta name="validation-errors" content="{{ json_encode($errors->messages()) }}">
                    @endif

                    <div class="card-body">
                        <div class="live-preview">
                            <div class="row gy-4">
                                <div class="col-md-12 px-4">
                                    <div class="row">
                                        {{-- <div class="col-md-12 d-flex justify-content-between">
                                            <label for="" class="form-label"></label>
                                            <button type="button" class="btn btn-primary" onclick="addFood()">Thêm đồ
                                                ăn</button>
                                        </div> --}}
                                        <div id="food_list" class="col-md-12">
                                            <!-- Các phần tử food sẽ được thêm vào đây -->
                                            @foreach ($combo->comboFood as $item)
                                                <div class="col-md-12 mb-3" id="${id}_item">
                                                    <div class="d-flex">
                                                        <div class="col-md-7">
                                                            <label for="${id}_select" class="form-label">Đồ ăn</label>
                                                            <select name="combo_food[]" id="${id}_select"
                                                                class="form-control food-select" disabled>
                                                                @foreach ($food as $itemId => $itemName)
                                                                    <option value="{{ $itemId }}"
                                                                        @selected($item->food_id == $itemId)>{{ $itemName }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                            <span class="text-danger" id="${id}_food_error"></span>
                                                        </div>
                                                        <div class="col-md-4 mx-4">
                                                            <label for="${id}" class="form-label">Số lượng</label>
                                                            <div class="d-flex flex-wrap align-items-start">
                                                                <div class="input-step full-width p-1">
                                                                    <button type="button" class="minuss">-</button>
                                                                    <input type="number" name="combo_quantity[]"
                                                                        class="food-quantity" id="${id}"
                                                                        value="{{ $item->quantity }}" min="0"
                                                                        max="10" disabled>
                                                                    <button type="button" class="pluss">+</button>
                                                                </div>
                                                            </div>
                                                            <span class="text-danger" id="${id}_quantity_error"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>

                                        <div class="col-md-4 mb-3">
                                            <label for="name" class="form-label "> <span class="text-danger">*</span>Tên
                                                combo</label>
                                            <input type="text" class="form-control" id="name" name="name"
                                                value="{{ $combo->name }}" placeholder="Nhập tên combo">
                                            @error('name')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="col-md-4 mb-3">
                                            <label for="price" class="form-label ">Giá gốc</label>
                                            <input type="number" class="form-control" id="price" name="price"
                                                value="{{ $combo->price }}" disabled>
                                        </div>

                                        <div class="col-md-4 mb-3">
                                            <label for="price_sale" class="form-label "> <span
                                                    class="text-danger">*</span>Giá bán</label>
                                            <input type="number" class="form-control" id="price_sale" name="price_sale"
                                                value="{{ $combo->price_sale }}" placeholder="Nhập giá bán">
                                            @error('price_sale')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="col-md-12 mb-3">
                                            <label for="description" class="form-label"> <span
                                                    class="text-danger">*</span>Mô tả ngắn</label>
                                            <textarea class="form-control" rows="3" name="description" placeholder="Nhập mô tả">{{ $combo->description }}</textarea>
                                            @error('description')
                                                <span class="text-danger">{{ $message }}</span>
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
                                <div class="mb-2">
                                    <label for="img_thumbnail" class="form-label"> <span class="text-danger">*</span>Hình
                                        ảnh</label>
                                    <input type="file" name="img_thumbnail" id="img_thumbnail" class="form-control">

                                    @if ($combo->img_thumbnail && \Storage::exists($combo->img_thumbnail))
                                        <div class="text-center">
                                            <img src="{{ Storage::url($combo->img_thumbnail) }}" alt=""
                                                class="mt-3" width="150px" height="90px">
                                        </div>
                                    @else
                                        No image !
                                    @endif

                                    @error('img_thumbnail')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="mb-2">
                                            <label class="form-check-label" for="is_active">Is active:</label>
                                            <div class="form-check form-switch form-switch-default">
                                                <input class="form-check-input" type="checkbox" role=""
                                                    name="is_active" @checked($combo->is_active) value="1">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--end col-->
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header align-items-center d-flex">
                        <a href="{{ route('admin.combos.index') }}" class="btn btn-info">Danh sách</a>
                        <button type="submit" class="btn btn-primary mx-1">Cập nhật</button>
                    </div>
                </div>
            </div>
            <!--end col-->
        </div>
    </form>
@endsection

@section('scripts')
    {{-- <script>
        document.addEventListener('DOMContentLoaded', function() {
            let foodCount = 0;
            const minFoodItems = 2;
            const maxFoodItems = 8;

            const foodList = document.getElementById('food_list');
            const validationErrors = document.querySelector('meta[name="validation-errors"]') ?
                JSON.parse(document.querySelector('meta[name="validation-errors"]').content) : {};

            // Thay thế `@json($foodPrice->pluck('price', 'id'))` với dữ liệu thực tế từ backend
            const foodPrices = @json($foodPrice->pluck('price', 'id'));

            // Thêm sẵn tối thiểu 2 món ăn
            for (let i = 0; i < minFoodItems; i++) {
                addFood(i);
            }

            function addFood(index) {
                if (foodCount >= maxFoodItems) {
                    alert('Chỉ được thêm tối đa ' + maxFoodItems + ' đồ ăn.');
                    return;
                }

                const id = 'gen_' + Math.random().toString(36).substring(2, 15).toLowerCase();
                const html = `
                    @foreach ($combo->comboFood as $item)
                        <div class="col-md-12 mb-3" id="${id}_item">
                            <div class="d-flex">
                                <div class="col-md-7">
                                    <label for="${id}_select" class="form-label">Đồ ăn</label>
                                    <select name="combo_food[]" id="${id}_select" class="form-control food-select">
                                        <option value="">--Chọn đồ ăn--</option>
                                        @foreach ($food as $itemId => $itemName)
                                            <option value="{{ $itemId }}" @selected($item->food_id == $itemId) >{{ $itemName }}</option>
                                        @endforeach
                                    </select>
                                    <span class="text-danger" id="${id}_food_error"></span>
                                </div>
                                <div class="col-md-3 mx-4">
                                    <label for="${id}" class="form-label">Số lượng</label>
                                    <div class="d-flex flex-wrap align-items-start">
                                        <div class="input-step step-primary full-width p-1">
                                            <button type="button" class="minuss">-</button>
                                            <input type="number" name="combo_quantity[]"
                                                class="food-quantity" id="${id}" value="{{ $item->quantity }}" min="0" max="10" readonly>
                                            <button type="button" class="pluss">+</button>
                                        </div>
                                    </div>
                                    <span class="text-danger" id="${id}_quantity_error"></span>
                                </div>

                                <div class="col-md-5 pt-4 mt-1">
                                    <button type="button" class="btn btn-danger remove-btn">
                                        <span class="bx bx-trash"></span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach

                `;

                foodList.insertAdjacentHTML('beforeend', html);

                // Gán sự kiện cho nút xóa và select box
                foodList.querySelector(`#${id}_item .remove-btn`).addEventListener('click', function() {
                    removeFood(`${id}_item`);
                });

                const newSelect = foodList.querySelector(`#${id}_select`);
                newSelect.addEventListener('change', updateTotalPrice);
                newSelect.addEventListener('change', updateSelectOptions);

                foodList.querySelector(`#${id}_item .food-quantity`).addEventListener('input', updateTotalPrice);

                foodCount++;

                // Hiển thị lỗi nếu có
                if (validationErrors[`combo_food.${index}`]) {
                    document.getElementById(`${id}_food_error`).innerText = validationErrors[`combo_food.${index}`][
                        0
                    ];
                }
                if (validationErrors[`combo_quantity.${index}`]) {
                    document.getElementById(`${id}_quantity_error`).innerText = validationErrors[
                        `combo_quantity.${index}`][0];
                }
                updateSelectOptions(); // Cập nhật các tùy chọn sau khi thêm món ăn mới
            }

            // Lắng nghe sự kiện tăng/giảm số lượng cho tất cả các nút + và -
            document.addEventListener('click', function(event) {
                if (event.target.classList.contains('pluss')) {
                    let quantityInput = event.target.closest('.input-step').querySelector('.food-quantity');
                    let currentValue = parseInt(quantityInput.value);
                    quantityInput.value = currentValue + 1; // Tăng số lượng
                    updateTotalPrice(); // Tính lại giá
                }

                if (event.target.classList.contains('minuss')) {
                    let quantityInput = event.target.closest('.input-step').querySelector('.food-quantity');
                    let currentValue = parseInt(quantityInput.value);
                    if (currentValue > 0) {
                        quantityInput.value = currentValue - 1; // Giảm số lượng
                        updateTotalPrice(); // Tính lại giá
                    }
                }
            });

            function removeFood(id) {
                if (foodCount > minFoodItems) {
                    if (confirm('Bạn có chắc muốn xóa không?')) {
                        const element = document.getElementById(id);
                        element.style.transition = 'opacity 0.5s ease';
                        element.style.opacity = '0';

                        setTimeout(() => {
                            element.remove();
                            foodCount--;
                            updateTotalPrice();
                            updateSelectOptions();
                        }, 500);
                    }
                } else {
                    alert('Phải có ít nhất ' + minFoodItems + ' đồ ăn.');
                }
            }

            function updateSelectOptions() {
                const selectedValues = Array.from(document.querySelectorAll('.food-select'))
                    .map(select => select.value)
                    .filter(value => value !== "");

                document.querySelectorAll('.food-select').forEach(select => {
                    const currentValue = select.value;
                    Array.from(select.options).forEach(option => {
                        if (option.value !== currentValue) {
                            option.disabled = selectedValues.includes(option.value);
                        } else {
                            option.disabled = false;
                        }
                    });
                });
            }

            function updateTotalPrice() {
                let totalPrice = 0;

                document.querySelectorAll('.food-select').forEach((select, index) => {
                    const foodId = select.value;
                    const quantityInput = document.querySelectorAll('.food-quantity')[index];
                    const quantity = parseInt(quantityInput.value) || 0;

                    if (foodId && quantity > 0) {
                        totalPrice += foodPrices[foodId] * quantity;
                    }
                });

                const priceInput = document.getElementById('price');
                priceInput.value = totalPrice;

                const priceSaleInput = document.getElementById('price_sale');
                priceSaleInput.value = totalPrice;
            }

            document.querySelector('button[onclick="addFood()"]').addEventListener('click', function() {
                addFood(foodCount);
            });
        });
    </script> --}}
@endsection
