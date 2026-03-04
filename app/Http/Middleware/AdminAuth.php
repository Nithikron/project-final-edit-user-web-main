<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AdminAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Debug: แสดงข้อมูล session
        \Log::info('AdminAuth middleware check', [
            'user_id' => Session::get('user_id'),
            'user_role' => Session::get('user_role'),
            'session_all' => Session::all()
        ]);

        // ตรวจสอบว่ามีการ login และเป็น admin หรือไม่
        if (!Session::has('user_id') || Session::get('user_role') !== 'admin') {
            \Log::warning('Access denied - not admin', [
                'user_id' => Session::get('user_id'),
                'user_role' => Session::get('user_role')
            ]);
            return redirect()->route('login.form')->with('error', 'คุณไม่มีสิทธิ์เข้าถึงหน้านี้');
        }

        \Log::info('Admin access granted');
        return $next($request);
    }
}
