import './bootstrap.js';

window.Echo.channel('vouchers')
    .listen('VoucherCreated', (e) => {
        const startDateTime = new Date(e.start_date_time);
        const endDateTime = new Date(e.end_date_time);
        const now = new Date();

        console.log(`Voucher: ${e.title}`);
        console.log(`Thời gian bắt đầu: ${startDateTime}`);
        console.log(`Thời gian hiện tại: ${now}`);
        console.log(`Thời gian kết thúc: ${endDateTime}`);

        if (now >= startDateTime && now <= endDateTime) {
            Swal.fire({
                title: 'Chúc mừng',
                text: `Bạn vừa nhận được mã voucher ${e.code} trị giá: ${e.discount} VNĐ`,
                icon: 'success',
                timer: 5000,
                timerProgressBar: true,
                confirmButtonText: 'Mua Vé Ngay'
            });
        } else {
            const timeStart = startDateTime - now;
            if (timeStart > 0) {
                setTimeout(() => {
                    Swal.fire({
                        title: 'Chúc mừng',
                        text: `Bạn vừa nhận được mã voucher ${e.code} trị giá: ${e.discount} VNĐ`,
                        icon: 'success',
                        timer: 5000,
                        timerProgressBar: true,
                        confirmButtonText: 'Mua Vé Ngay'
                    });
                }, timeStart);
            }
        }
    });



