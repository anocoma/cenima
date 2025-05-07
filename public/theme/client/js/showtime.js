
function openModalMovieScrening(movieId) {
    const modalMovieScrening = document.getElementById("modalMovieScrening");
    modalMovieScrening.style.display = "block"; // Mở modal
    // Lưu movie_id vào modal
    modalMovieScrening.setAttribute('data-movie-id', movieId);
    document.body.classList.add("no-scroll");

    // Gửi AJAX để lấy dữ liệu xuất chiếu của phim
    const routeApi = `${APP_URL}/api/movie/${movieId}/showtimes`;
    fetch(routeApi)
        .then(response => response.json())
        .then(data => {
            // Cập nhật tiêu đề modal với tên phim từ dữ liệu
            document.getElementById("modalMovieTitle").textContent = `LỊCH CHIẾU - ${data.movie.name}`; // Giả sử bạn có thuộc tính movie_title trong data

            // Cập nhật nội dung modal với lịch chiếu nhận được
            updateModalContent(data);
        })
        .catch(error => {
            console.error('Error fetching showtimes:', error);
        });
}

// Đóng modal khi nhấn nút đóng
const spanClose = document.getElementsByClassName("closeModalMovieScrening")[0];
spanClose.onclick = function () {
    const modalMovieScrening = document.getElementById("modalMovieScrening");
    modalMovieScrening.style.display = "none"; // Đóng modal
    document.body.classList.remove("no-scroll")
}

// Đóng modal khi nhấn bên ngoài modal
window.onclick = function (event) {
    const modalMovieScrening = document.getElementById("modalMovieScrening");
    if (event.target == modalMovieScrening) {
        modalMovieScrening.style.display = "none"; // Đóng modal
        document.body.classList.remove("no-scroll")
    }
}

function updateModalContent(data) {
    const modalBody = document.querySelector('.modalMovieScrening-body');
    modalBody.innerHTML = ''; // Xóa nội dung cũ

    // Tạo HTML cho phần Date Picker
    let dateShowtimes = '<div class="listMovieScrening-date">';
    data.dates.forEach((date, index) => {
        dateShowtimes += `
            <div class="movieScrening-date-item ${index === 0 ? 'active' : ''}" data-day="${date.day_id}">
                ${date.date_label}
            </div>`;
    });
    dateShowtimes += '</div>';
    modalBody.innerHTML += dateShowtimes;


    let showtimesHTML = ''; // Khởi tạo biến chứa HTML

    data.dates.forEach((date, index) => {

        showtimesHTML += `
        <div class="movieScrening-list-showtime-day" id="${date.day_id}" style="display: ${index === 0 ? 'block' : 'none'};">`;

        // Lặp qua các định dạng suất chiếu
        for (const format in date.showtimes) {

            showtimesHTML += `
            <div class="movieScrening-showtime-version">
                <h4 class="version-movie">${format}</h4>
                <div class="list-showtimes">`;

            date.showtimes[format].forEach(showtime => {
                showtimesHTML += `
                    <div class="showtime-item">
                        <div class="showtime-item-start-time" onclick="window.location.href='${APP_URL}/choose-seat/${showtime.slug}'">
                            ${new Date(showtime.start_time).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })}
                        </div>

                    </div>`;
                    // <div class="empty-seat-showtime">150 ghế trống</div> Giả định số ghế trống
            });

            showtimesHTML += `
                </div>
            </div>`;
        }

        showtimesHTML += `
        </div>
    </div>`;
    });
    // Thêm HTML nội dung suất chiếu vào modal
    modalBody.innerHTML += showtimesHTML;

    // Nếu không có suất chiếu nào


    // Gắn sự kiện cho việc chọn ngày
    const dateItems = document.querySelectorAll('.movieScrening-date-item');
    dateItems.forEach(item => {
        item.onclick = function () {
            // Xóa class 'active' khỏi tất cả các ngày
            dateItems.forEach(i => i.classList.remove('active'));
            // Thêm class 'active' cho ngày đang được chọn
            item.classList.add('active');

            // Ẩn tất cả các suất chiếu
            document.querySelectorAll('.movieScrening-list-showtime-day').forEach(showtime => showtime.style.display = 'none');
            // Hiển thị suất chiếu tương ứng với ngày được chọn
            const selectedShowtimeDiv = document.getElementById(item.getAttribute('data-day'));
            selectedShowtimeDiv.style.display = 'block';

            // Kiểm tra nếu không có suất chiếu
            const noShowtimeMessage = selectedShowtimeDiv.querySelector('.no-showtime-message');
            if (noShowtimeMessage) {
                noShowtimeMessage.style.display = selectedShowtimeDiv.querySelectorAll('.showtime-item').length === 0 ? 'block' : 'none';
            }
        };
    });
}



