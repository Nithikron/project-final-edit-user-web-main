<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Rooms;
use Illuminate\Http\Request;

class BookingpagesController extends Controller
{
    public function index(){
        // ดึงรายการเช็คอินล่าสุดเพื่อแสดงเป็น "ผู้เข้าพัก" 
        // include related room so we can display its name
        $tenants = Booking::where('type', 'checkin')
            ->with(['room','user'])
            ->orderBy('created_at', 'desc')
            ->get();

        // ดึงเฉพาะห้องที่สถานะว่างเท่านั้น
        $rooms = Rooms::where('status', Rooms::STATUS_AVAILABLE)->get();

        return view("admin.booking", compact('tenants','rooms'));
    }

    /**
     * อัปเดตระเบียนผู้เข้าพัก
     */
    public function update(Request $request, Booking $tenant)
    {
        $rules = [
            'tenant_name' => 'required|string|max:255',
            'room_id'     => 'required|exists:rooms,id',
            'date'        => 'required|date',
        ];

        // phone validation is still useful because we show the field but it belongs to user
        $rules['phone'] = 'nullable|string|max:20';

        $request->validate($rules);

        // update booking record (doesn't store phone)
        $oldName = $tenant->tenant_name;
        $tenant->update($request->only(['tenant_name','room_id','date']));

        // if phone provided, update matching user record (use old name in case the tenant name changed)
        if ($request->filled('phone')) {
            $user = \App\Models\User::where('name', $oldName)->first();
            if ($user) {
                $user->phone = $request->phone;
                $user->save();
            }
        }

        return back()->with('success', 'บันทึกข้อมูลผู้เข้าพักเรียบร้อยแล้ว');
    }

}