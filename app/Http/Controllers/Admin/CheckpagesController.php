<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Rooms;

class CheckpagesController extends Controller
{
    public function index()
    {
        $bookings = Booking::orderBy('id', 'desc')->paginate(10);
        
        // สำหรับจองห้องพัก - แสดงแค่ห้องที่ว่าง
        $availableRooms = Rooms::where('status', Rooms::STATUS_AVAILABLE)->get();
        
        // สำหรับเช็คอิน - แสดงห้องที่ว่างหรือถูกจอง
        $bookableRooms = Rooms::whereIn('status', [Rooms::STATUS_AVAILABLE, Rooms::STATUS_RESERVED])->get();
        
        // สำหรับเช็คเอาท์ - เฉพาะห้องที่มีผู้พักอยู่
        $occupiedRooms = Rooms::where('status', Rooms::STATUS_OCCUPIED)->get();
        
        return view('admin.check-in-out', compact('bookings', 'availableRooms', 'bookableRooms', 'occupiedRooms'));
    }
}