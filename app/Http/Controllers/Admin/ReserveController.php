<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Rooms;
use App\Models\Booking;

class ReserveController extends Controller
{
    // 🔹 แสดงเฉพาะห้องว่าง
    public function index()
    {
        $rooms = Rooms::where('status', Rooms::STATUS_AVAILABLE)->get();
        return view('admin.reservere', compact('rooms'));
    }

    // 🔹 บันทึกการจอง + กันจองซ้ำ + เปลี่ยนสถานะห้อง
    public function store(Request $request)
    {
        $request->validate([
            'room_id'     => 'required|exists:rooms,id',
            'tenant_name' => 'required|string|max:255',
        ]);

        $room = Rooms::findOrFail($request->room_id);

        // ❌ กันจองซ้ำ (ถ้าห้องไม่ว่าง)
        if ($room->status !== Rooms::STATUS_AVAILABLE) {
            return back()->with('error', 'ห้องนี้ไม่ว่าง ไม่สามารถจองได้');
        }

        // ✅ บันทึกการจอง
        Booking::create([
            'room_id'     => $request->room_id,
            'tenant_name' => $request->tenant_name,
            'type'        => 'reserve',   // เพิ่มประเภทให้รู้ว่าเป็นการ "จอง"
            'date'        => now(),       // เก็บวันที่ไว้ดูย้อนหลัง
        ]);

        // ✅ เปลี่ยนสถานะห้องเป็น "ถูกจอง"
        $room->update([
            'status' => Rooms::STATUS_RESERVED,
        ]);

        return redirect()->back()->with('success', 'จองห้องเรียบร้อยแล้ว 🎉');
    }
}