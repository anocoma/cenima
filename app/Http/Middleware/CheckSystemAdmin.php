<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckSystemAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user(); // Lấy user hiện tại

        // if ($user && $user->hasRole('System Admin')) {
        if ($user && $user->name == "System Admin") {
            return $next($request);
        }

        // Nếu không phải, trả về thông báo lỗi 403
        abort(403, 'Bạn không có quyền truy cập!');
    }
}
