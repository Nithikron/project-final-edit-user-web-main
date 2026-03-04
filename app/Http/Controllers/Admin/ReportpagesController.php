<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payments;
use App\Models\Booking;
use Illuminate\Http\Request;

class ReportpagesController extends Controller
{
    
    public function index(){
        $paymentTotal = Payments::sum('amount');
        $paid    = Payments::where('status', 'จ่าย')->sum('amount');
        $pending = Payments::where('status', 'ไม่จ่าย')->sum('amount');
        $items   = Payments::orderBy('created_at', 'desc')->get();

        // รายได้จากห้องที่เช็คเอาท์แล้ว
        $checkoutRevenue = Booking::where('type', 'checkout')
            ->sum('total_price');
        
        // รายได้จากห้องที่ยังพักอยู่
        $checkinRevenue = Booking::where('type', 'checkin')
            ->sum('total_price');
        
        // รายได้ทั้งหมดจากห้องพัก (รวม checkout + checkin)
        $roomRevenue = $checkoutRevenue + $checkinRevenue;
        
        // รายได้ทั้งหมด (room revenue + payment revenue)
        $total = $paymentTotal + $roomRevenue;

        return view('admin.report', compact('total', 'paid', 'pending', 'items', 'roomRevenue', 'checkoutRevenue', 'checkinRevenue', 'paymentTotal'));
    }
    
}