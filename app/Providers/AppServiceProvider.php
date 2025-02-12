<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use Carbon\Carbon;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Đặt timezone mặc định cho Carbon
        Carbon::setLocale(config('app.timezone'));
        date_default_timezone_set(config('app.timezone'));
        Paginator::useBootstrapFive();
    }
}
