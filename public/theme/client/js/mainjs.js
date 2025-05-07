
// Code Js Fixed thanh header
window.onscroll = function () {
    var headerTop = document.querySelector('.header-top');
    var headerButtom = document.querySelector('.header-buttom');

    // Lấy độ cao đã cuộn
    var scrollPosition = window.scrollY || window.pageYOffset;

    // Khi người dùng cuộn xuống qua 100px, ẩn header-top
    if (scrollPosition > 100) {
        headerTop.style.display = 'none'; // Ẩn header-top
        headerButtom.classList.add('fixed-header'); // Cố định header-buttom
    } else {
        headerTop.style.display = 'block'; // Hiện lại header-top
        headerButtom.classList.remove('fixed-header'); // Bỏ cố định header-buttom
    }
};

// LỰC ĐÓNG
// Js cho tăng giảm số lượng trang thanh toán
// document.addEventListener('DOMContentLoaded', function () {
//     const decreaseBtns = document.querySelectorAll('.quantity-btn.decrease');   //dấu trừ
//     const increaseBtns = document.querySelectorAll('.quantity-btn.increase');   //dấu cộng
//     const quantityInputs = document.querySelectorAll('.quantity-input');
//     const totalPriceElement = document.querySelector('.total-price-checkout .total-price-checkout');
//     const totalPriceInput = document.getElementById('total-price');

//     // Hàm tính tổng tiền
//     function calculateTotal() {
//         let totalPrice = 0;

//         quantityInputs.forEach(input => {
//             const quantity = parseInt(input.value); //parseInt: chuyển thành số nguyên
//             const pricePerCombo = parseInt(input.closest('tr').querySelector('.combo-price').dataset.price);
//             totalPrice += quantity * pricePerCombo;
//         });

//         totalPriceElement.textContent = totalPrice.toLocaleString() + ' VNĐ';

//         // Cập nhật tổng tiền trong ô input
//         totalPriceInput.value = totalPrice;
//     }

//     // Sự kiện bấm nút tăng số lượng
//     increaseBtns.forEach(button => {
//         button.addEventListener('click', function () {
//             const input = this.closest('.quantity-container').querySelector('.quantity-input');
//             let currentValue = parseInt(input.value);
//             const max = parseInt(input.getAttribute('max'));
//             if (currentValue < max) { // Chỉ tăng nếu giá trị hiện tại nhỏ hơn max
//                 input.value = currentValue + 1;
//                 calculateTotal(); // Cập nhật tổng tiền
//             }
//         });
//     });

//     // Sự kiện bấm nút giảm số lượng
//     decreaseBtns.forEach(button => {
//         button.addEventListener('click', function () {
//             const input = this.closest('.quantity-container').querySelector('.quantity-input');
//             let currentValue = parseInt(input.value);
//             if (currentValue > 0) { // Chỉ giảm khi giá trị lớn hơn 0
//                 input.value = currentValue - 1;
//                 calculateTotal(); // Cập nhật tổng tiền
//             }
//         });
//     });
// });

document.addEventListener('DOMContentLoaded', function () {
    // Khai báo biến tổng tiền từ session và số tiền giảm giá
    let sessionTotalPrice = parseInt(document.getElementById('total-price').value, 10);
    let discountAmount = 0; // Giảm giá mặc định

    const decreaseBtns = document.querySelectorAll('.quantity-btn.decrease');
    const increaseBtns = document.querySelectorAll('.quantity-btn.increase');
    const quantityInputs = document.querySelectorAll('.quantity-input');
    const totalPriceElement = document.querySelector('.total-price-display'); // Đổi class thành 'total-price-display'
    const totalPriceInput = document.getElementById('total-price');
    const totalPaymentElement = document.querySelector('.total-price-payment');
    const totalDiscountElement = document.querySelector('.total-discount');

    // Hàm tính tổng tiền
    // function calculateTotal() {
    //     let totalPrice = sessionTotalPrice; // Bắt đầu từ tổng tiền trong session

    //     // Tính tiền combo dựa trên số lượng
    //     quantityInputs.forEach(input => {
    //         const quantity = parseInt(input.value, 10); // Chuyển thành số nguyên
    //         const pricePerCombo = parseInt(input.closest('tr').querySelector('.combo-price').dataset.price, 10);
    //         totalPrice += quantity * pricePerCombo;
    //     });

    //     // Cập nhật tổng tiền
    //     totalPriceElement.textContent = totalPrice.toLocaleString() + ' Vnđ';
    //     totalPriceInput.value = totalPrice; // Cập nhật giá trị ô input ẩn

    //     // Tính số tiền cần thanh toán sau khi giảm giá
    //     let totalPayment = totalPrice - discountAmount;
    //     totalPayment = Math.max(totalPayment, 0); // Đảm bảo không âm

    //     // Cập nhật hiển thị số tiền cần thanh toán
    //     totalPaymentElement.textContent = totalPayment.toLocaleString() + ' Vnđ';
    // }
    //Đạt comment lôi ra ngoài
    // // Sự kiện khi bấm nút tăng số lượng
    // increaseBtns.forEach(button => {
    //     button.addEventListener('click', function () {
    //         const input = this.closest('.quantity-container').querySelector(
    //             '.quantity-input');
    //         let currentValue = parseInt(input.value);
    //         const max = parseInt(input.getAttribute('max'));
    //         if (currentValue < max) { // Chỉ tăng nếu giá trị nhỏ hơn max
    //             input.value = currentValue + 1;
    //             calculateTotal(); // Cập nhật tổng tiền
    //         }
    //     });
    // });

    // // Sự kiện khi bấm nút giảm số lượng
    // decreaseBtns.forEach(button => {
    //     button.addEventListener('click', function () {
    //         const input = this.closest('.quantity-container').querySelector(
    //             '.quantity-input');
    //         let currentValue = parseInt(input.value);
    //         if (currentValue > 0) { // Chỉ giảm khi giá trị lớn hơn 0
    //             input.value = currentValue - 1;
    //             calculateTotal(); // Cập nhật tổng tiền
    //         }
    //     });
    // });

    // function attachCancelVoucherEvent() {
    //     $('#cancel-voucher-btn').on('click', function () {
    //         // Phục hồi giá trị và nút bấm về trạng thái ban đầu
    //         $('#voucher_code').val(''); // Đặt giá trị của input về rỗng
    //         $('#voucher-response').html('');

    //         // Lấy giá trị tổng tiền ban đầu và định dạng lại
    //         var originalTotalPrice = parseInt($('#total-price').val());
    //         $('.total-price-payment').text(originalTotalPrice.toLocaleString() + ' Vnđ');
    //         $('.total-discount').text('0 Vnđ');

    //         // Cập nhật lại trạng thái nút
    //         $('#apply-voucher-btn').attr('disabled', false);

    //         // Cập nhật lại discountAmount về 0
    //         discountAmount = 0;
    //         calculateTotal(); // Tính lại tổng tiền thanh toán sau khi hủy voucher
    //     });
    // }

    //Đạt đóng xử lý voucher
    // $('#apply-voucher-btn').on('click', function (e) {
    //     e.preventDefault(); // Ngăn chặn hành động mặc định

    //     $(this).attr('disabled', true); // Vô hiệu hóa nút để ngăn gửi nhiều lần

    //     var formData = {
    //         code: $('#voucher_code').val(),
    //         _token: $('input[name="_token"]').val() // Lấy CSRF token từ input
    //     };

    //     $.ajax({
    //         url: routeUrl,
    //         type: "POST",
    //         data: formData,
    //         success: function (response) {
    //             var discountAmountReceived = response.discount;
    //             discountAmount = discountAmountReceived; // Cập nhật discountAmount
    //             var discountAmountFormatted = discountAmountReceived.toLocaleString();

    //             $('#voucher-response').html(`
    //             <div class="t-success">${response.success}</div>
    //             <div class="show-text">
    //                 <span>Voucher: <b>${response.voucher_code}</b></span>
    //                 <span>Giảm giá: <b>${discountAmountFormatted}</b> Vnđ</span>
    //                 <button type="button" id="cancel-voucher-btn" data-voucher-id="${response.id}">Hủy</button>
    //             </div>
    //         `);

    //             var totalPrice = parseInt($('#total-price').val());
    //             var totalPricePayment = totalPrice - discountAmount;

    //             $('.total-price-payment').text(totalPricePayment.toLocaleString() + ' Vnđ');
    //             $('.total-discount').text(discountAmountFormatted + ' Vnđ');

    //             $('#apply-voucher-btn').attr('disabled', false);
    //             attachCancelVoucherEvent(); // Gán sự kiện cho nút "Hủy"
    //         },
    //         error: function (xhr) {
    //             var error = xhr.responseJSON.error || 'Voucher không hợp lệ';
    //             showModalError(error);
    //             $('#apply-voucher-btn').attr('disabled', false);
    //         }
    //     });
    // });

    // function showModalError(errorMessage) {
    //     const modalHTML = `
    //                 <div id="error-modal" class="modal">
    //                     <div class="modal-content" >
    //                         <p class="text-error">${errorMessage}</p>
    //                         <span class="close-modal button-error">Hủy</span>
    //                     </div>
    //                 </div>
    //             `;

    //     $('body').append(modalHTML);
    //     $('#error-modal').css('display', 'block');
    //     $('.close-modal').on('click', function () {
    //         $('#error-modal').remove();
    //         $('#voucher_code').val(''); // Đặt giá trị của input về rỗng
    //     });

    //     $(window).on('click', function (event) {
    //         if ($(event.target).is('#error-modal')) {
    //             $('#error-modal').remove();
    //         }
    //     });
    // }

    // Tính toán ban đầu khi trang được tải
    // calculateTotal();

    document.getElementById('payment-form').addEventListener('submit', function (e) {
        // Lấy giá trị từ phần tử hiển thị
        const discountAmount = document.querySelector('.total-discount').textContent.replace(' Vnđ', '').replace('.', '').replace(',', ''); // Xóa ' Vnđ' và chuyển đổi về số
        const totalPayment = document.querySelector('.total-price-payment').textContent.replace(' Vnđ', '').replace('.', '').replace(',', ''); // Xóa ' Vnđ' và chuyển đổi về số

        // Cập nhật giá trị vào ô input ẩn
        document.getElementById('total-discount').value = discountAmount;
        document.getElementById('total-price-payment').value = totalPayment;
    });
});


// Js cho đoạn nhập voucher và điểm trang thanh toán
document.querySelectorAll('.voucher-title, .points-title').forEach(title => {
    title.addEventListener('click', function () {
        const section = this.parentElement;
        section.classList.toggle('active');
    });
});

//ajax voucher
// $(document).ready(function () {
//     $('#voucher-form').on('submit', function (e) {
//         e.preventDefault();

//         $('#apply-voucher-btn').attr('disabled', true);

//         var formData = {
//             code: $('#voucher_code').val(),
//             _token: csrfToken
//         };

//         console.log(formData);

//         $.ajax({
//             url: routeUrl,
//             type: "POST",
//             data: formData,
//             success: function (response) {
//                 var discountAmount = response.discount;
//                 var discountAmountFormated = response.discount.toLocaleString();

//                 $('#voucher-response').html(`
//                         <div class="t-success" style="">${response.success}</div>
//                         <div class="show-text">
//                         <span>Voucher: <b>${response.voucher_code}</b></span>
//                         <span>Giảm giá: <b>${discountAmountFormated}</b> vnđ</span>
//                         <button id="cancel-voucher-btn" data-voucher-id="${response.id}">Hủy</button>
//                         </div>
//                     `);

//                 // Tính toán số tiền cần thanh toán
//                 var totalPrice = parseInt($('#total-price').val());
//                 var totalPricePayment = totalPrice - discountAmount;

//                 // Hiển thị số tiền cần thanh toán với toLocaleString
//                 $('.total-price-payment').text(totalPricePayment.toLocaleString() + ' Vnđ');
//                 $('.total-discount').text(discountAmount.toLocaleString() + ' Vnđ');


//                 $('#apply-voucher-btn').attr('disabled', false);
//                 attachCancelVoucherEvent();
//             },
//             error: function (xhr) {
//                 var error = xhr.responseJSON.error || 'Voucher không hợp lệ';
//                 showModalError(error);
//                 $('#apply-voucher-btn').attr('disabled', false);
//             }
//         });
//     });

//     function showModalError(errorMessage) {
//         const modalHTML = `
//                     <div id="error-modal" class="modal">
//                         <div class="modal-content" >
//                             <p class="text-error">${errorMessage}</p>
//                             <span class="close-modal button-error">Hủy</span>
//                         </div>
//                     </div>
//                 `;

//         $('body').append(modalHTML);

//         $('#error-modal').css('display', 'block');

//         $('.close-modal').on('click', function () {
//             $('#error-modal').remove();
//         });

//         $(window).on('click', function (event) {
//             if ($(event.target).is('#error-modal')) {
//                 $('#error-modal').remove();
//             }
//         });
//     }
// });

// LỰC ĐÓNG
// function attachCancelVoucherEvent() {
//     $('#cancel-voucher-btn').on('click', function () {
//         // Phục hồi giá trị và nút bấm về trạng thái ban đầu
//         $('#voucher-form')[0].reset();
//         $('#voucher-response').html('');

//         // Lấy giá trị tổng tiền ban đầu và định dạng lại
//         var originalTotalPrice = parseInt($('#total-price').val());
//         $('.total-price-payment').text(originalTotalPrice.toLocaleString() + ' Vnđ');

//         $('.total-discount').text('0 Vnđ');

//         // Cập nhật lại trạng thái nút
//         $('#apply-voucher-btn').attr('disabled', false);
//     });
// }

// Code xử lý chính
// $('#voucher-form').on('submit', function (e) {
//     e.preventDefault();

//     $('#apply-voucher-btn').attr('disabled', true);

//     var formData = {
//         code: $('#voucher_code').val(),
//         _token: csrfToken
//     };

//     $.ajax({
//         url: routeUrl,
//         type: "POST",
//         data: formData,
//         success: function (response) {
//             var discountAmount = response.discount;
//             var discountAmountFormated = response.discount.toLocaleString();

//             $('#voucher-response').html(`
//         <div class="t-success" style="">${response.success}</div>
//         <div class="show-text">
//         <span>Voucher: <b>${response.voucher_code}</b></span>
//         <span>Giảm giá: <b>${discountAmountFormated}</b> vnđ</span>
//         <button id="cancel-voucher-btn" data-voucher-id="${response.id}">Hủy</button>
//         </div>
//       `);

//             var totalPrice = parseInt($('#total-price').val());
//             var totalPricePayment = totalPrice - discountAmount;

//             $('.total-price-payment').text(totalPricePayment.toLocaleString() + ' Vnđ');
//             $('.total-discount').text(discountAmount.toLocaleString() + ' Vnđ');

//             $('#apply-voucher-btn').attr('disabled', false);
//             attachCancelVoucherEvent();
//         },
//         error: function (xhr) {
//             var error = xhr.responseJSON.error || 'Voucher không hợp lệ';
//             showModalError(error);
//             $('#apply-voucher-btn').attr('disabled', false);
//         }
//     });
// });



// js cho modal chọn suất chiếu trang home

document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.date-display').forEach(btn => {

        btn.addEventListener('click', () => {
            // console.log('Button clicked:', btn);

            const currentActive = document.querySelector('.date-display.active');
            if (currentActive) {
                currentActive.classList.remove('active');
            }
            btn.classList.add('active');
        });
    });
});

//
document.querySelectorAll('.location-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        document.querySelector('.location-btn.active').classList.remove('active');
        btn.classList.add('active');
    });
});

//
document.querySelectorAll('.format-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        document.querySelector('.format-btn.active').classList.remove('active');
        btn.classList.add('active');
    });
});

//
document.querySelectorAll('.time-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        // You can add custom functionality here for when a time is selected
        alert(`You selected the ${btn.innerText} showtime.`);
    });
});


// An đóng
//cancer voucher
// function attachCancelVoucherEvent() {
//     $('#voucher-response').on('click', '#cancel-voucher-btn', function () {
//         let voucherId = $(this).data('voucher-id');  // Lấy bằng đúng thuộc tính
//         console.log('Voucher ID:', voucherId);  // Kiểm tra ID trong console
//
//         if (voucherId !== undefined) {
//             $.ajax({
//                 url: "{{ route('cancelVoucher') }}",
//                 type: "POST",
//                 data: {
//                     voucher_id: voucherId,
//                     _token: '{{ csrf_token() }}'
//                 },
//                 success: function (response) {
//                     $('#voucher-response').html('<span class="success">' + response.success + '</span>');
//                 },
//                 error: function (xhr) {
//                     var error = xhr.responseJSON.error || 'Có lỗi xảy ra';
//                     $('#voucher-response').html('<span class="error">' + error + '</span>');
//                 }
//             });
//         } else {
//             console.error('Voucher ID is undefined.');
//         }
//     });
// }
