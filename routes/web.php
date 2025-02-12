<?php

use App\Http\Controllers\Admin\CinemaController;
use App\Http\Controllers\Auth\LoginFacebookController;
use App\Http\Controllers\Client\CheckoutController;
use App\Http\Controllers\Client\ChooseSeatController;
use App\Http\Controllers\Client\HomeController;
use App\Mail\TicketInvoiceMail;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Client\MovieDetailController;
use App\Http\Controllers\Client\ContactController;
use App\Http\Controllers\Client\ShowtimeController;
use App\Http\Controllers\Client\UserController;
use App\Http\Controllers\Client\PaymentController;
use App\Http\Controllers\Client\PostController;
use App\Http\Controllers\Client\MoMoPaymentController;
use App\Http\Controllers\Client\MovieController;
use App\Http\Controllers\Client\TicketController;
use App\Http\Controllers\Client\TicketPriceController;
use App\Models\Room;
use App\Models\Seat;
use App\Models\Showtime;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/



// Route::get('/', function () {
//     return view('client.home');
// })->name('home');

Route::get('/',                                  [HomeController::class, 'home'])->name('home');
Route::get('policy',                             [HomeController::class, 'policy'])->name('policy');
Route::get('load-more-movie-showing',            [HomeController::class, 'loadMoreMovieShowing'])->name('load-more-movie-showing');
Route::get('load-more-movie-upcoming',            [HomeController::class, 'loadMoreMovieUpcoming'])->name('load-more-movie-upcoming');
Route::get('load-more-movie-special',            [HomeController::class, 'loadMoreMovieSpecial'])->name('load-more-movie-special');
// Route gửi lại email xác thực
Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();

    return back()->with('message', 'Email xác thực đã được gửi.');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

Route::prefix('movies')
    ->as('movies.')
    ->group(function () {
        Route::get('/', [MovieController::class, 'index'])->name('index');
        Route::get('{slug}', [MovieDetailController::class, 'show'])->name('movie-detail');
        Route::get('{id}/comments', [MovieDetailController::class, 'getComments'])->name('comments');
        Route::post('{slug}/add-review', [MovieDetailController::class, 'addReview'])->name('addReview');
    });


// lịch chiếu theo rạp
Route::get('showtimes', [ShowtimeController::class, 'show'])->name('showtimes');

Route::get('choose-seat/{slug}', [ChooseSeatController::class, 'show'])->middleware('verified')->name('choose-seat');
Route::post('save-information/{id}', [ChooseSeatController::class, 'saveInformation'])->name('save-information');

// Route giữ ghế cho người dùng
Route::post('/update-seat', [ChooseSeatController::class, 'updateSeat'])->name('update-seat');
// Route::post('/hold-seats', [ChooseSeatController::class, 'holdSeats'])->name('hold-seats');
// Route::post('/release-seats', [ChooseSeatController::class, 'releaseSeats'])->name('release-seats');

Route::get('checkout/{slug}', [CheckoutController::class, 'checkout'])->middleware('verified')->name('checkout');
Route::post('checkout/apply-voucher', [CheckoutController::class, 'applyVoucher'])->name('applyVoucher')->middleware('auth');
route::delete('checkout/cancel-voucher', [CheckoutController::class, 'cancelVoucher'])->name('cancelVoucher');
Route::post('checkout/get-my-voucher', [CheckoutController::class, 'getMyVoucher'])->name('get-my-voucher');

route::post('payment', [PaymentController::class, 'payment'])->name('payment');
route::post('payment-admin', [PaymentController::class, 'paymentAdmin'])->name('payment-admin');

// Cổng thanh toán
//1 VNPAY
Route::get('vnpay-payment', [PaymentController::class, 'vnPayPayment'])->name('vnpay.payment');
Route::get('vnpay-return', [PaymentController::class, 'returnVnpay'])->name('vnpay.return');

//2 MOMO
Route::get('momo-payment', [PaymentController::class, 'moMoPayment'])->name('momo.payment');
Route::get('momo-return', [PaymentController::class, 'returnPayment'])->name('momo.return');
Route::post('momo-notify', [PaymentController::class, 'notifyPayment'])->name('momo.notify');
//3 ZALOPAY
Route::post('zalopay-payment', [PaymentController::class, 'zaloPayPayment']);

// User - Thông tin tài khoản
Route::get('my-account/{page?}', [UserController::class, 'edit'])->middleware('auth')->name('my-account.edit');
Route::put('/my-account/update', [UserController::class, 'update'])->name('my-account.update');
Route::put('/change-password-ajax', [UserController::class, 'changePasswordAjax'])->name('my-account.changePasswordAjax');

//Hủy vé
Route::put('my-account/transaction/{ticket}/cancel', [TicketController::class, 'cancel'])->name('my-account.transaction.cancel');

// // User - Lịch sử mua hàng
Route::get('ticket-detail/{id}', [UserController::class, 'ticketDetail'])->name('ticketDetail');
Route::get('transactionHistory', [UserController::class, 'transactionHistory'])->name('transactionHistory');


Route::get('forgot-password', function () {
    return view('client.forgot-password');
})->name('forgot-password');


// Contact
Route::get('contact', function () {
    return view('client.contact');
})->name('contact');

Route::post('contact/store', [ContactController::class, 'store'])->name('contact.store');

Route::get('introduce', function () {
    return view('client.introduce');
})->name('introduce');

Auth::routes(['verify' => true]);
// LOGIN FACEBOOK
Route::controller(LoginFacebookController::class)->group(function () {
    Route::get('auth/facebook', 'redirectToFacebook')->name('auth.facebook');
    Route::get('auth/facebook/callback', 'handleFacebookCallback');
});
// LOGIN GOOGLE
Route::controller(\App\Http\Controllers\Auth\GoogleAuthController::class)->group(function () {
    Route::get('auth/google', 'redirectToGoogle')->name('auth.google');
    Route::get('auth/google/callback', 'callBackGoogle');
});

// Route::get('movies2', [HomeController::class, 'loadMoreMovies2']);

// Route::get('movies3', [HomeController::class, 'loadMoreMovies3']);
// Route::get('movies1', [HomeController::class, 'loadMoreMovies1']);
// Route::get('movie/{id}/showtimes', [HomeController::class, 'getShowtimes']);

Route::get('movies/load-more', action: [HomeController::class, 'loadMore'])->name('movies.loadMore');



Route::post('change-cinema', [CinemaController::class, 'changeCinema'])->name('change-cinema');

//Trang tin tức
Route::get('posts', [PostController::class, 'index'])->name('posts');
Route::get('/posts/{slug}', [PostController::class, 'show'])->name('posts.show');

//Viết tạm route chuyển trang admin checkout ở đây
Route::get('checkoutAdmin', function () {
    return view('admin.book-tickets.checkout');
})->name('checkoutAdmin');


// Giá vé theo chi nhánh
Route::get('ticket-price', [TicketPriceController::class, 'index'])->name('ticket-price');


// để tạm để test
// Route::get('admin/assign-manager-showtimes', function () {
//     dd(Auth::user()->getAllPermissions()->pluck('slug'));
// });

Route::get('admin/assign-admin', function () {
    $user = User::find('1');
    // $user->assignRole('System Admin');
    // dd($user->name);


    $adminRole = Role::findByName('System Admin', 'web');
    $adminRole->givePermissionTo(Permission::where('guard_name', 'web')->get());

    // $adminRole->syncPermissions($permissions);

    if ($user && $user->hasRole('System Admin')) {
        return 'Người dùng này đã có vai trò System Admin';
    } else {
        return 'Người dùng này không có vai trò System Admin';
    }
});





Route::get('public', function () {
    return view('public');
})->name('public');

Route::get('huhu', function () {
    $ticket = Ticket::find(20); // Lấy ticket có ID là 1
    if (!$ticket) {
        return 'Ticket not found';
    }

    Mail::to($ticket->user->email)->send(new TicketInvoiceMail($ticket));

    return 'Email sent successfully';
});
Route::get('hihi/{id}', function () {
    $ticket = Ticket::find(20); // Lấy ticket có ID là 1
    return view('welcome', compact('ticket'));
});
