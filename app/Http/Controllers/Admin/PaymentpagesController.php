<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payments;
use App\Models\Booking;

class PaymentpagesController extends Controller
{
    public function index(){
        // load payments with related booking and room data
        $payments = Payments::with('booking')
                        ->orderBy('created_at', 'desc')
                        ->get();
        
        // load all bookings for dropdown
        $bookings = Booking::orderBy('id', 'desc')->get();

        return view("admin.payment", compact('payments', 'bookings'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            'amount' => 'required|numeric|min:0.01',
            'status' => 'required|in:pending,paid',
            'remark' => 'nullable|string',
        ]);

        Payments::create([
            'booking_id' => $request->booking_id,
            'amount' => $request->amount,
            'status' => $request->status,
            'remark' => $request->remark,
        ]);

        return back()->with('success', 'บันทึกการชำระเงินเรียบร้อยแล้ว');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            'amount' => 'required|numeric|min:0.01',
            'status' => 'required|in:pending,paid',
            'remark' => 'nullable|string',
        ]);

        $payment = Payments::findOrFail($id);
        $payment->update([
            'booking_id' => $request->booking_id,
            'amount' => $request->amount,
            'status' => $request->status,
            'remark' => $request->remark,
        ]);

        return back()->with('success', 'บันทึกการชำระเงินได้รับการอัปเดตแล้ว');
    }

    public function destroy($id)
    {
        $payment = Payments::findOrFail($id);
        $payment->delete();

        return back()->with('success', 'ลบบันทึกการชำระเงินเรียบร้อยแล้ว');
    }
}