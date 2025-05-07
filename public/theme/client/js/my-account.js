$(document).ready(function () {
    $("#uploadBtn").click(function () {
        $("#file-upload").click();
    });

    $("#file-upload").change(function () {
        const reader = new FileReader();
        reader.onload = function (e) {
            $("#img_thumbnail").html('<img src="' + e.target.result + '" class="img-fluid" alt="Uploaded Image">');
        };
        reader.readAsDataURL(this.files[0]);
    });

    $("#changePasswordBtn").click(function (e) {
        e.preventDefault();
        $("#overlay, #changePasswordForm").show();
    });

    $("#closeChangePassword").click(function () {
        $("#overlay, #changePasswordForm").hide();
    });
});
    $(document).ready(function () {
        $('.my-account-tab').click(function () {
            // Xóa class 'my-account-active' khỏi tất cả các tab
            $('.my-account-tab').removeClass('my-account-active');
            
            // Thêm class 'my-account-active' vào tab được nhấn
            $(this).addClass('my-account-active');
        });
    });