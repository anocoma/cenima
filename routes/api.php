<?php

use App\Http\Controllers\API\APIController;
use App\Http\Controllers\API\BookTicketController;
use App\Http\Controllers\API\MembershipController;
use App\Http\Controllers\API\MovieController;
use App\Http\Controllers\API\RoomController;
use App\Http\Controllers\API\SeatController;
use App\Http\Controllers\API\SeatTemplateController;
use App\Http\Controllers\API\UpdateActiveController;
use App\Models\Membership;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
// use App\Http\Controllers\Admin\BranchController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
// // Định nghĩa các route chuẩn cho CRUD của Branch
// Route::apiResource('branches', BranchController::class);
Route::get('cinemas/{branchId}',           [APIController::class, 'getCinemas']);
Route::get('rooms/{movieId}',              [APIController::class, 'getRooms']);
Route::get('movieVersions/{movieId}',      [APIController::class, 'getMovieVersion']);
Route::get('getMovieDuration/{movieId}',   [APIController::class, 'getMovieDuration']);
Route::get('typeRooms/{typeRoomId}',       [APIController::class, 'getTypeRooms']);
Route::post('showtimes/deleteSelected', [APIController::class, 'deleteSelected'])->name('showtimes.deleteSelected');
Route::post('showtimes/onStatusSelected', [APIController::class, 'onStatusSelected'])->name('showtimes.onStatusSelected');
Route::post('showtimes/offStatusSelected', [APIController::class, 'offStatusSelected'])->name('showtimes.offStatusSelected');


Route::middleware('web')->get('movie/{movie}/showtimes', [MovieController::class, 'getShowtimes'])->name('showtime-by-movie');

Route::middleware('web')->resource('rooms', RoomController::class);

Route::post('rooms/update-active',      [RoomController::class, 'updateActive'])->name('rooms.update-active');
Route::post('movies/update-active',     [MovieController::class, 'updateActive'])->name('movies.update-active');
Route::post('movies/update-hot',        [MovieController::class, 'updateHot'])->name('movies.update-hot');
Route::post('branches/change-active',   [UpdateActiveController::class, 'branch'])->name('branches.change-active');
Route::post('cinemas/change-active',    [UpdateActiveController::class, 'cinema'])->name('cinemas.change-active');
Route::post('food/change-active',       [UpdateActiveController::class, 'food'])->name('food.change-active');
Route::post('combos/change-active',     [UpdateActiveController::class, 'combo'])->name('combos.change-active');
Route::post('slideshows/change-active', [UpdateActiveController::class, 'slideshow'])->name('slideshows.change-active');
Route::post('posts/change-active',      [UpdateActiveController::class, 'post'])->name('posts.change-active');
Route::post('showtimes/change-active',  [UpdateActiveController::class, 'showtime'])->name('showtimes.change-active');
Route::post('vouchers/change-active',   [UpdateActiveController::class, 'voucher'])->name('vouchers.change-active');


Route::prefix('seat-templates')
    ->as('seat-templates.')
    ->middleware('web')
    ->group(function () {
        Route::post('store',                        [SeatTemplateController::class, 'store']);
        Route::put('{seatTemplate}',                [SeatTemplateController::class, 'update']);
        Route::post('change-active', [SeatTemplateController::class, 'changeActive'])->name('change-active');
    });


Route::middleware('web')->group(function () {
    Route::post('get-membership',       [APIController::class, 'getMembership'])->name('get-membership');
    Route::delete('cancel-membership',       [APIController::class, 'cancelMembership'])->name('cancel-membership');
    Route::post('membership/apply-point', [MembershipController::class, 'applyPoint'])->name('apply-point');
    Route::post('membership/cancel-point',       [MembershipController::class, 'cancelPoint'])->name('cancel-point');
});






Route::get('getShowtimesByRoom', [APIController::class, 'getShowtimesByRoom']);

Route::post('vouchers', [\App\Http\Controllers\Admin\VoucherController::class, 'store'])->name('vouchers.store');



Route::middleware(['web'])->group(function () {
    Route::post('toggle-seat/{showtime}',    [BookTicketController::class, 'toggleSeat'])->name('toggle-seat');
    Route::get('get-selected-seat/{showtime}',          [BookTicketController::class, 'getSelectedSeat'])->name('get-selected-seat');
    Route::post('payment-now/{showtime}',         [BookTicketController::class, 'payment'])->name('payment-now');
    Route::post('clear-session/{showtime}', [BookTicketController::class, 'clearSession'])->name('clear-session');
});
