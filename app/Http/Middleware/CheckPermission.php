<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

use Illuminate\Support\Facades\Auth;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next, $permission)
    {
        // return $next($request);
        if (Auth::check() && Auth::user()->can($permission)) {
            return $next($request);
        }

        // Nếu người dùng không có quyền, chuyển hướng hoặc trả về lỗi 403
        abort(403, 'Bạn không có quyền truy cập.');
    }
}
