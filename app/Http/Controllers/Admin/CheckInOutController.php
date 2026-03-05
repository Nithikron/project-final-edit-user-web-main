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
        // จอง: แสดงห้องว่างเท่านั้น
        $availableRooms = Rooms::where('status', Rooms::STATUS_AVAILABLE)->get();
        
        // เช็คอิน: แสดงห้องว่างและห้องที่ถูกจองเข้ามา โดยรวมข้อมูลการจอง
        $bookableRooms = Rooms::whereIn('status', [Rooms::STATUS_AVAILABLE, Rooms::STATUS_RESERVED])
            ->with(['bookings' => function($query) {
                $query->where('type', 'reserve')->latest('id')->limit(1);
            }])
            ->get();
        
        // เช็คเอาท์: แสดงห้องที่มีสถานะอยู่ โดยรวมข้อมูลการเช็คอิน
        $occupiedRooms = Rooms::where('status', Rooms::STATUS_OCCUPIED)
            ->with(['bookings' => function($query) {
                $query->where('type', 'checkin')->latest('id')->limit(1);
            }])
            ->get();
        
        $bookings = Booking::orderBy('id', 'desc')->get();

        return view('admin.check-in-out', compact('availableRooms', 'bookableRooms', 'occupiedRooms', 'bookings'));
    }

    public function showCheckout()
    {
        $occupiedRooms = Rooms::where('status', Rooms::STATUS_OCCUPIED)
            ->with(['bookings' => function($query) {
                $query->where('type', 'checkin')->latest('id')->limit(1);
            }])
            ->get();

        return view('admin.check-out', compact('occupiedRooms'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'tenant_name' => 'required',
            'phone' => 'nullable|string|max:20',
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
            'customer_name' => $request->tenant_name,
            'customer_phone' => $request->phone ?? '',
            'customer_email' => '',
            'check_in_date' => now()->toDateString(),
            'check_out_date' => now()->addDay()->toDateString(),
            'total_price' => Rooms::find($request->room_id)->price,
            'status' => 'confirmed',
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

    // update any overlapping online bookings to completed
    Booking::where('room_id', $request->room_id)
        ->where('status', 'confirmed')
        ->whereNull('type')
        ->where('check_in_date', '<=', now()->toDateString())
        ->where('check_out_date', '>=', now()->toDateString())
        ->update(['status' => 'cancelled']);

    // ✅ อัปเดตเป็น checkout
    $booking->update([
        'type'  => 'checkout',
        'status' => 'cancelled', // update status to cancelled
        'notes' => 'เช็คเอาท์แล้ว',
    ]);

    // ✅ เปลี่ยนสถานะห้องกลับเป็นว่าง
    Rooms::where('id', $request->room_id)
        ->update(['status' => Rooms::STATUS_AVAILABLE]);

    return back()->with('success', 'เช็คเอาท์เรียบร้อยแล้ว 🎉');
}
}