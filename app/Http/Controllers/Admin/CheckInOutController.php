<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Rooms;
use Illuminate\Http\Request;

// Rooms::all();
// → ดึงข้อมูลห้องทั้งหมดจากฐานข้อมูล
// Booking::orderBy(...)->get();
// → ดึงประวัติการเช็คอินทั้งหมด
// return view(...)
// → ส่งข้อมูลไปหน้า blade

class CheckInOutController extends Controller
{
    public function index()
    {
        $rooms = Rooms::all();
        $bookings = Booking::orderBy('id', 'desc')->get();

        return view('admin.check-in-out', compact('rooms', 'bookings'));
    }

    public function showCheckout()
    {
        $rooms = Rooms::where('status', Rooms::STATUS_OCCUPIED)->get();

        return view('admin.check-out', compact('rooms'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'tenant_name' => 'required',
        ]);

        // ❌ กันเช็คอินซ้ำ
        $hasActive = Booking::where('room_id', $request->room_id)
            ->where('type', 'checkin')
            ->exists();

        if ($hasActive) {
            return back()->with('error', 'ห้องนี้มีผู้เข้าพักอยู่แล้ว');
        }

        Booking::create([
            'room_id' => $request->room_id,
            'tenant_name' => $request->tenant_name,
            'type' => 'checkin',
            'notes' => $request->notes ?? null,
            'date' => now(),
        ]);

        Rooms::where('id', $request->room_id)
            ->update(['status' => Rooms::STATUS_OCCUPIED]);

        return back()->with('success', 'เช็คอินสำเร็จแล้ว');
    }

    public function checkout(Request $request)
{
    $request->validate([
        'room_id' => 'required|exists:rooms,id',
        'tenant_name' => 'required|string|max:255',
    ]);

    // 🔎 เอาการเช็คอินล่าสุดของห้องนี้
    $booking = Booking::where('room_id', $request->room_id)
        ->where('type', 'checkin')
        ->latest('id')
        ->first();

    // ❌ ถ้าไม่มีเช็คอินค้างอยู่
    if (! $booking) {
        return back()->with('error', 'ห้องนี้ยังไม่มีผู้เข้าพัก ไม่สามารถเช็คเอาท์ได้');
    }

    // ❌ ถ้าชื่อไม่ตรง (ตัดช่องว่างหน้า-หลัง ป้องกันพิมพ์พลาดนิดหน่อย)
    if (trim($booking->tenant_name) !== trim($request->tenant_name)) {
        return back()->with('error', 'ชื่อผู้เข้าพักไม่ตรงกับข้อมูลที่เช็คอินไว้');
    }

    // ✅ อัปเดตเป็น checkout
    $booking->update([
        'type'  => 'checkout',
        'notes' => 'เช็คเอาท์แล้ว',
    ]);

    // ✅ เปลี่ยนสถานะห้องกลับเป็นว่าง
    Rooms::where('id', $request->room_id)
        ->update(['status' => Rooms::STATUS_AVAILABLE]);

    return back()->with('success', 'เช็คเอาท์เรียบร้อยแล้ว 🎉');
}
}