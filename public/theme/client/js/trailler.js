// var modalTrailer = document.getElementById("trailerModal-trailer");
// var openModalBtn = document.getElementById("openModalBtn-trailer");
// var closeModalTrailer = document.getElementsByClassName("close-trailer")[0];
// var iframeTrailer = modalTrailer.querySelector("iframe");  // Lấy iframe trong modal
// var originalSrc = iframeTrailer.src;  // Lưu lại src gốc của iframe

// // Open modalTrailer
// openModalBtn.onclick = function () {
//     iframeTrailer.src = originalSrc;  // Gán lại src khi mở modal
//     modalTrailer.style.display = "block";
//     document.body.classList.add('no-scroll');
// }

// // Close modalTrailer
// closeModalTrailer.onclick = function () {
//     modalTrailer.style.display = "none";
//     iframeTrailer.src = "";  // Xóa src khi đóng modal để dừng video và tránh mờ
//     document.body.classList.remove('no-scroll');
// }

// // Close modalTrailer when clicking outside of the modalTrailer content
// window.onclick = function (event) {
//     document.body.classList.remove('no-scroll');
//     if (event.target == modalTrailer) {
//         modalTrailer.style.display = "none";
//         iframeTrailer.src = "";  // Xóa src khi đóng modal
//     }
// }



const modal = document.getElementById("trailerModal-trailer");
const closeModalBtn = document.querySelector(".close-trailer");
const trailerVideo = document.getElementById("trailerVideo");
const trailerTitle = document.getElementById("trailerTitle");

// Hàm mở trailer với URL và tên phim
function openTrailer(trailerUrl, movieName) {
    modal.style.display = "flex";
    trailerVideo.src = trailerUrl + "?autoplay=1"; // Gán URL với autoplay=1
    trailerTitle.textContent = `TRAILER - ${movieName}`; // Cập nhật tiêu đề với tên phim
    document.body.classList.add("no-scroll");
}

// Lắng nghe sự kiện click cho tất cả các nút có class "open-trailer-btn"
document.querySelectorAll(".open-trailer-btn").forEach(button => {
    button.addEventListener("click", function() {
        const trailerUrl = this.getAttribute("data-trailer-url");
        const movieName = this.getAttribute("data-movie-name");
        openTrailer(trailerUrl, movieName);
    });
});

// Đóng modal và xóa URL để dừng video
closeModalBtn.onclick = function() {
    modal.style.display = "none";
    trailerVideo.src = "";  // Xóa URL để dừng video
    document.body.classList.remove("no-scroll");
}

// Đóng modal khi click ra ngoài modal
window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
        trailerVideo.src = "";  // Xóa URL để dừng video
        document.body.classList.remove("no-scroll");
    }
}
