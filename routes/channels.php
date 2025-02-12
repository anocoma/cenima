<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

// Định nghĩa channel cho việc giữ ghế theo showtime
Broadcast::channel('showtime.{showtimeId}', function ($user, $showtimeId) {
    // Kiểm tra điều kiện để người dùng có quyền nghe channel này, ví dụ:
    return true;  // Mọi người dùng đều có thể nghe sự kiện giữ ghế.
});

Broadcast::channel('vouchers', function (){
   return true;
});



