<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    // แสดงหน้า login
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // ดำเนินการ login
    public function login(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'password' => 'required|string',
        ]);

        // ค้นหาผู้ใช้ด้วย name
        $user = User::where('name', $request->name)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return back()->withErrors([
                'name' => 'ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง',
            ])->withInput($request->except('password'));
        }

        // เข้าสู่ระบบ
        Session::put('user_id', $user->id);
        Session::put('user_name', $user->name);
        Session::put('user_role', $user->role);

        // ตรวจสอบว่าเป็น admin หรือไม่
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard')->with('success', 'เข้าสู่ระบบสำเร็จ! ยินดีต้อนรับผู้ดูแลระบบ');
        }

        return redirect()->route('home')->with('success', 'เข้าสู่ระบบสำเร็จ! ยินดีต้อนรับ '.$user->name);
    }

    // แสดงหน้า register
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    // ดำเนินการ register
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'name' => 'required|string|max:50|unique:users,name',
            'email' => 'required|string|email|max:255|unique:users,email',
            'phone' => 'nullable|string|max:10',
            'password' => 'required|string|min:6|confirmed',
            'citizen_id' => 'nullable|string|max:13|unique:users,citizen_id',
        ]);

        // Debug: แสดงข้อมูลที่ได้รับ
        \Log::info('Register data:', [
            'name' => $request->name,
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password ? 'has_password' : 'no_password',
        ]);

        // สร้างผู้ใช้ใหม่
        $user = User::create([
            'name' => $request->name,
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'citizen_id' => $request->citizen_id,
            'role' => 'user', // ค่าเริ่มต้นคือ user
        ]);

        // เข้าสู่ระบบอัตโนมัติหลังสมัครเสร็จ
        Session::put('user_id', $user->id);
        Session::put('user_name', $user->name);
        Session::put('user_role', $user->role);

        // ตรวจสอบว่ามีการจองห้องที่ต้องการหรือไม่
        if (Session::has('intended_room_id')) {
            $roomId = Session::get('intended_room_id');
            $checkIn = Session::get('intended_check_in');
            $checkOut = Session::get('intended_check_out');
            
            // ล้าง session
            Session::forget(['intended_room_id', 'intended_check_in', 'intended_check_out']);
            
            // redirect ไปหน้าจองห้อง
            return redirect()->route('booking.create', $roomId)
                ->with('success', 'สมัครสมาชิกสำเร็จ! กรุณากรอกข้อมูลการจองห้อง');
        }

        return redirect()->route('home')->with('success', 'สมัครสมาชิกสำเร็จ! ยินดีต้อนรับ '.$user->name);
    }

    // ออกจากระบบ
    public function logout()
    {
        Session::flush();

        return redirect()->route('login.form')->with('success', 'ออกจากระบบสำเร็จ');
    }

    // ตรวจสอบสถานะการ login
    public static function isLoggedIn()
    {
        return Session::has('user_id');
    }

    // ตรวจสอบว่าเป็น admin หรือไม่
    public static function isAdmin()
    {
        return Session::get('user_role') === 'admin';
    }

    // ดึงข้อมูลผู้ใช้ที่ login อยู่
    public static function getCurrentUser()
    {
        if (self::isLoggedIn()) {
            return User::find(Session::get('user_id'));
        }

        return null;
    }
}
