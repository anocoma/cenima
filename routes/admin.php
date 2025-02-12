<?php

use App\Http\Controllers\Admin\AssignRolesController;
use App\Http\Controllers\Admin\BookTicketController;
use App\Http\Controllers\Admin\CinemaController;
use App\Http\Controllers\Admin\BranchController;
use App\Http\Controllers\Admin\ComboController;
use App\Http\Controllers\Admin\ContactController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FoodController;
use App\Http\Controllers\Admin\MovieController;
use App\Http\Controllers\Admin\MyAccountController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\RankController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\RoomController;
use App\Http\Controllers\Admin\SeatTemplateController;
use App\Http\Controllers\Admin\ShowtimeController;
use App\Http\Controllers\Admin\StatisticalController;

use App\Http\Controllers\Admin\SlideShowController;
use App\Http\Controllers\Admin\TicketController;
use App\Http\Controllers\Admin\TicketPriceController;
use App\Http\Controllers\Admin\TypeRoomController;
use App\Http\Controllers\Admin\SiteSettingController;

use App\Http\Controllers\Admin\TypeSeatController;

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\VoucherController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Route::get('/', function () {
//     return view('admin.dashboard');
// });
Route::get('/', [DashboardController::class, 'dashboard'])->name('/');
// Quản lý chi nhánh
Route::prefix('branches')
    ->as('branches.')
    ->group(function () {
        Route::get('/',                       [BranchController::class, 'index'])->name('index');
        Route::post('/',                      [BranchController::class, 'store'])->name('store');
        Route::get('{branch}/delete',       [BranchController::class, 'destroy'])->name('destroy');
        Route::put('{branch}/update',       [BranchController::class, 'update'])->name('update');
    });

// Cinema
Route::resource('cinemas', CinemaController::class);
// Payment
Route::resource('payments', PaymentController::class);

Route::resource('slideshows', SlideShowController::class);
Route::resource('vouchers', VoucherController::class);
Route::post('vouchers/update-discount', [VoucherController::class, 'updateDiscount'])
    ->name('vouchers.update-discount');
//resource ticket
Route::resource('tickets', TicketController::class);

//in ve
Route::get('tickets/{ticket}/print', [TicketController::class, 'print'])->name('tickets.print');
// Route::get('tickets/{ticket}/print-combo', [TicketController::class, 'printCombo'])->name('tickets.printCombo');
// //scan ve
Route::get('admin/tickets/scan', [TicketController::class, 'scan'])->name('tickets.scan');
Route::post('tickets/process-scan', [TicketController::class, 'processScan'])->name('tickets.processScan');
// //chuyen trang thai ve khi in
// //route::post('tickets/{ticket}/update-status', [TicketController::class, 'updateStatus'])->name('tickets.updateStatus');



// Quản lý Hóa đơn
Route::prefix('tickets')
    ->as('tickets.')
    ->group(function () {
        Route::get('/',                   [TicketController::class, 'index'])->name('index');
        Route::get('{ticket}',                   [TicketController::class, 'show'])->name('show');
        Route::post('{ticket}/confirm', [TicketController::class, 'confirm'])->name('confirm');
    });




Route::resource('contacts', ContactController::class);
Route::patch('contacts/{contact}/status', [ContactController::class, 'updateStatus'])->name('contacts.updateStatus');

Route::get('contacts/{contact}', [ContactController::class, 'show']);



// Quản lý phim
Route::prefix('movies')
    ->as('movies.')
    ->group(function () {
        Route::get('/',             [MovieController::class, 'index'])->name('index');
        Route::get('create',        [MovieController::class, 'create'])->name('create'); // Chuyển lên trước
        Route::post('store',        [MovieController::class, 'store'])->name('store');
        Route::get('{movie}',       [MovieController::class, 'show'])->name('show');
        Route::get('{movie}/edit',  [MovieController::class, 'edit'])->name('edit');
        Route::put('{movie}',       [MovieController::class, 'update'])->name('update');
        Route::delete('{movie}',    [MovieController::class, 'destroy'])->name('destroy');
        Route::post('selected-tab', [MovieController::class, 'selectedTab'])->name('selected-tab');
    });



// Route::resource('type-rooms', TypeRoomController::class);

// Quản lý phòng chiếu
Route::prefix('rooms')
    ->as('rooms.')
    ->group(function () {
        Route::get('/',                     [RoomController::class, 'index'])->name('index');
        Route::get('edit/{room}',           [RoomController::class, 'edit'])->name('edit');
        Route::put('{room}/update',         [RoomController::class, 'update'])->name('update');

        Route::get('{room}',                [RoomController::class, 'show'])->name('show');
        Route::get('{room}/destroy',        [RoomController::class, 'destroy'])->name('destroy');
        Route::get('{room}/destroy',        [RoomController::class, 'destroy'])->name('destroy');
        Route::post('selected-tab',         [RoomController::class, 'selectedTab'])->name('selected-tab');
    });

Route::prefix('seat-templates')
    ->as('seat-templates.')
    ->group(function () {
        Route::get('/',                                 [SeatTemplateController::class, 'index'])->name('index');
        Route::get('{seatTemplate}/edit',               [SeatTemplateController::class, 'edit'])->name('edit');
        Route::put('{seatTemplate}/seat-structure',     [SeatTemplateController::class, 'updateSeatStructure'])->name('update.seat-structure');
        Route::put('{seatTemplate}/info',               [SeatTemplateController::class, 'update'])->name('updateInfo')->name('update.info');
        Route::get('{seatTemplate}',                    [SeatTemplateController::class, 'destroy'])->name('destroy');
        Route::post('selected-tab',                     [SeatTemplateController::class, 'selectedTab'])->name('selected-tab');
    });
// Route::resource('rooms', RoomController::class);

Route::resource('posts', PostController::class);

Route::resource('showtimes', ShowtimeController::class);


Route::get('ticket-price', [TicketPriceController::class, 'index'])->name('ticket-price');
Route::post('ticket-update', [TicketPriceController::class, 'update'])->name('ticket-update');

// Route::post('admin/ticket-price/update', [TicketPriceController::class, 'update'])->name('admin.ticket-price.update');


// food
Route::resource('food', FoodController::class);
// Combo
Route::resource('combos', ComboController::class);
// TypeSeat
Route::resource('type_seats', TypeSeatController::class);
//user

Route::resource('users', UserController::class);



Route::put('users/reset-password/{user}', [UserController::class, 'resetPassword'])->name('users.password.reset');
//my-account
Route::get('my-account', [MyAccountController::class, 'show'])->name('my-account');
Route::get('my-account/edit', [MyAccountController::class, 'edit'])->name('my-account.edit');
Route::put('my-account/update', [MyAccountController::class, 'update'])->name('my-account.update');
Route::post('my-account/change-password', [MyAccountController::class, 'changePassword'])->name('my-account.change-password');


// Đặt vé
Route::prefix('book-tickets')
    ->as('book-tickets.')
    ->group(function () {
        Route::get('/',                      [BookTicketController::class, 'index'])->name('index');
        Route::get('{showtime}',             [BookTicketController::class, 'show'])->name('show');
        Route::post('{showtime}',            [BookTicketController::class, 'payment']);
        // Route::get('{seatTemplate}/edit',   [SeatTemplateController::class, 'edit'])->name('edit');
        // Route::put('{seatTemplate}/update',   [SeatTemplateController::class, 'update'])->name('update');
    });

Route::group(['middleware' => 'CheckSystemAdmin'], function () {
    Route::resource('permissions', PermissionController::class);
    Route::resource('roles', RoleController::class);
    Route::resource('assign-roles', AssignRolesController::class);
});

Route::prefix('ranks')
    ->as('ranks.')
    ->group(function () {
        Route::get('', [RankController::class, 'index'])->name('index');
        Route::post('', [RankController::class, 'store'])->name('store');
        Route::get('{rank}/delete', [RankController::class, 'destroy'])->name('destroy');
        Route::put('{rank}/update', [RankController::class, 'update'])->name('update');
    });


// Cấu hình Website
// 1. Quản lý
Route::get('site-settings', [SiteSettingController::class, 'index'])->name('site-settings.index');
Route::put('site-settings/update', [SiteSettingController::class, 'update'])->name('site-settings.update');

// 2. Đặt lại về mặc định
Route::post('site-settings/reset', [SiteSettingController::class, 'resetToDefault'])->name('site-settings.reset');

// thống kê
Route::get('/statistical-movies', [StatisticalController::class, 'statisticalMovies'])->name('statistical-movies');
Route::get('/statistical-tickets', [StatisticalController::class, 'statisticalTickets'])->name('statistical-tickets');
Route::get('/statistical-revenue', [StatisticalController::class, 'statisticalRevenue'])->name('statistical-revenue');
Route::get('/statistical-cinemas', [StatisticalController::class, 'statisticalCinemas'])->name('statistical-cinemas');
Route::get('/statistical-combos', [StatisticalController::class, 'statisticalCombos'])->name('statistical-combos');

Route::get('/', [DashboardController::class, 'dashboard'])->name('dashboard.fillter');

// Route::get('/statistical/revenue', [StatisticalController::class, 'revenue'])->name('statistical.revenue');
// Route::get('/statistical/ticketsRevenue', [StatisticalController::class, 'ticketsRevenue'])->name('statistical.ticketsRevenue');
Route::get('/statistical/cinemaRevenue', [StatisticalController::class, 'cinemaRevenue'])->name('statistical.cinemaRevenue');



