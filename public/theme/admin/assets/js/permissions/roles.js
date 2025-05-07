

document.addEventListener('DOMContentLoaded', function () {
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
});