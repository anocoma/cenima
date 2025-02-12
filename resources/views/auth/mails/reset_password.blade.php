<!DOCTYPE html>
<html>

<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #c4c4c4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .email-container {
            max-width: 600px;
            margin: auto;
            background-color: #ffffff;
            border: 1px solid #e4e4e4;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .email-header {
            background-color: rgb(64, 81, 137);
            color: #ffffff;
            padding: 20px;
            text-align: center;
            font-size: 24px;
            font-weight: bold;
        }

        .email-body {
            padding: 20px;
            color: #333333;
            line-height: 1.6;
        }

        .email-button-container {
            text-align: center;
            margin: 20px 0;
        }

        .email-button {
            display: inline-block;
            font-size: 13px;
            color: #ffffff;
            text-decoration: none;
            background-color: rgb(64, 81, 137);
            padding: 12px 20px;
            border-radius: 5px;
            /* font-weight: bold; */
            transition: background-color 0.3s ease;
        }

        .email-button:hover {
            background-color: rgb(53, 69, 121);
        }

        .email-footer {
            background-color: #f1f1f1;
            color: #777777;
            text-align: center;
            font-size: 14px;
            padding: 10px;
            border-top: 1px solid #e4e4e4;
        }
    </style>
</head>

<body>
    <div class="email-container">
        <div class="email-header">
            Poly Cinemas
        </div>
        <div class="email-body">
            <p>Xin chào!</p>
            <p>Bạn nhận được email này vì chúng tôi nhận được yêu cầu khôi phục mật khẩu cho tài khoản của bạn.</p>
            <div class="email-button-container">
                <a href="{{ $url }}" class="email-button" style="color: #ffffff">Khôi phục mật khẩu</a>
            </div>
            <p>Liên kết khôi phục mật khẩu sẽ hết hạn sau {{ $expire }} phút.</p>
            <p>Nếu bạn không yêu cầu khôi phục mật khẩu, vui lòng bỏ qua email này.</p>
            <p>Trân trọng,<br>Đội ngũ Poly Cinemas</p>
        </div>
        <div class="email-footer">
            &copy; {{ date('Y') }} Poly Cinemas. Tất cả các quyền được bảo lưu.
        </div>
    </div>
</body>

</html>
