<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payments;
use App\Models\Rooms;

class DashboardController extends Controller
{
    public function index()
    {

        // จำนวนห้อง
        $available = Rooms::where('status', 'available')->count(); // ว่าง
        $occupied = Rooms::where('status', 'occupied')->count(); // ไม่ว่าง
        $reserved = Rooms::where('status', 'reserved')->count(); // จอง

        // รายได้เดือนปัจจุบัน (ไม่ใช้ Carbon)
        $monthlyIncome = Payments::whereMonth('updated_at', now()->month)
            ->whereYear('updated_at', now()->year)
            ->sum('amount');

        // ห้องทั้งหมด
        $rooms = Rooms::all();

        return view('admin.dashboard', compact(
            'available',
            'occupied',
            'reserved',
            'monthlyIncome',
            'rooms',
        ));

    }
}