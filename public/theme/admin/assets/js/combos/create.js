document.addEventListener('DOMContentLoaded', function() {
    let foodCount = 0;
    const minFoodItems = 2;
    const maxFoodItems = 4;
    const foodList = document.getElementById('food_list');
    const foodPrices = JSON.parse(document.querySelector('meta[name="foodPrices"]').content); // Lấy dữ liệu foodPrices

    // Lấy các lỗi từ meta
    const validationErrors = document.querySelector('meta[name="validation-errors"]') ? 
        JSON.parse(document.querySelector('meta[name="validation-errors"]').content) : {};

    for (let i = 0; i < minFoodItems; i++) {
        addFood();
    }

    function addFood() {
        if (foodCount >= maxFoodItems) {
            alert('Chỉ được thêm tối đa ' + maxFoodItems + ' đồ ăn.');
            return;
        }

        const id = 'gen_' + Math.random().toString(36).substring(2, 15).toLowerCase();
        const html = `
            <div class="col-md-12 mb-1" id="${id}_item">
                <div class="d-flex">
                    <div class="col-md-6">
                        <label for="${id}_select" class="form-label">Đồ ăn</label>
                        <select name="combo_food[]" id="${id}_select" class="form-control mb-3 mx-2 food-select">
                            <option value="">Chọn đồ ăn</option>
                            ${foodOptionsHtml} <!-- Biến foodOptionsHtml từ Blade -->
                        </select>
                        <span class="text-danger" id="${id}_error_food"></span> <!-- Nơi hiển thị lỗi -->
                    </div>
                    <div class="col-md-5 mx-3">
                        <label for="${id}" class="form-label">Số lượng</label>
                        <input type="number" class="form-control food-quantity" name="combo_quantity[]" id="${id}" placeholder="Nhập số lượng" min="1" value="1">
                        <span class="text-danger" id="${id}_error_quantity"></span> <!-- Nơi hiển thị lỗi -->
                    </div>
                    <div class="col-md-1">
                        <button type="button" class="btn btn-danger remove-btn mt-4">
                            <span class="bx bx-trash"></span>
                        </button>
                    </div>
                </div>
            </div>
        `;

        foodList.insertAdjacentHTML('beforeend', html);

        // Gán sự kiện cho nút xóa và select box
        document.querySelector(`#${id}_item .remove-btn`).addEventListener('click', function() {
            removeFood(`${id}_item`);
        });

        const newSelect = document.querySelector(`#${id}_select`);
        newSelect.addEventListener('change', updateTotalPrice);
        newSelect.addEventListener('change', updateSelectOptions);
        document.querySelector(`#${id}_item .food-quantity`).addEventListener('input', updateTotalPrice);

        foodCount++;
        updateSelectOptions();

        // Hiển thị lỗi nếu có
        showValidationErrors(id, foodCount);
    }

    function showValidationErrors(id, index) {
        if (validationErrors[`combo_food.${index}`]) {
            document.getElementById(`${id}_error_food`).textContent = validationErrors[`combo_food.${index}`][0];
        }
        if (validationErrors[`combo_quantity.${index}`]) {
            document.getElementById(`${id}_error_quantity`).textContent = validationErrors[`combo_quantity.${index}`][0];
        }
    }

    function removeFood(id) {
        if (foodCount > minFoodItems) {
            if (confirm('Bạn có chắc muốn xóa không?')) {
                document.getElementById(id).remove();
                foodCount--;
                updateTotalPrice();
                updateSelectOptions();
            }
        } else {
            alert('Phải có ít nhất ' + minFoodItems + ' đồ ăn.');
        }
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

        document.getElementById('price').value = totalPrice;
        document.getElementById('price_sale').value = totalPrice;
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
});
