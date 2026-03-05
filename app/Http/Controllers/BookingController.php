<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Payment;
use App\Models\Room;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\Services\CalendarService;

class BookingController extends Controller
{
    protected $calendarService;
    
    public function __construct(CalendarService $calendarService)
    {
        $this->calendarService = $calendarService;
    }
    
    public function create(Request $request, Room $room)
    {
        // ตรวจสอบว่าผู้ใช้ล็อคอินหรือไม่
        if (!auth()->check() && !session('user_id')) {
            // เก็บข้อมูลห้องที่ต้องการจองไว้ใน session
            session(['intended_room_id' => $room->id]);
            session(['intended_check_in' => $request->check_in]);
            session(['intended_check_out' => $request->check_out]);
            
            return redirect()->route('register.form')->with('info', 'กรุณาสมัครสมาชิกก่อนทำการจองห้อง');
        }

        $currentUser = auth()->user() ?? User::find(session('user_id'));

        $month = $request->month ?? now()->month;
        $year  = $request->year ?? now()->year;

        $startOfMonth = Carbon::create($year, $month)->startOfMonth();
        $endOfMonth   = Carbon::create($year, $month)->endOfMonth();

        $bookings = Booking::where('room_id', $room->id)
            ->where(function ($q) use ($startOfMonth, $endOfMonth) {
                $q->whereBetween('check_in_date', [$startOfMonth, $endOfMonth])
                  ->orWhereBetween('check_out_date', [$startOfMonth, $endOfMonth]);
            })
            ->get();

        $daysInMonth = $startOfMonth->daysInMonth;
        $firstDay = $startOfMonth->dayOfWeek; // 0=Sunday

        $calendar = [];

        for ($i = 1; $i <= $daysInMonth; $i++) {
            $date = Carbon::create($year, $month, $i);

            $isBooked = $bookings->contains(function ($booking) use ($date) {
                return $date->between(
                    Carbon::parse($booking->check_in_date),
                    Carbon::parse($booking->check_out_date)->subDay()
                );
            });

            $calendar[] = [
                'date' => $date->format('Y-m-d'),
                'day' => $i,
                'booked' => $isBooked,
            ];
        }

        return view('booking.create', compact(
            'room',
            'calendar',
            'month',
            'year',
            'firstDay',
            'daysInMonth',
            'currentUser'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'customer_email' => 'required|email|max:255',
            'check_in_date' => 'required|date|after_or_equal:today',
            'check_out_date' => 'required|date|after:check_in_date',
        ]);

        $room = Room::findOrFail($request->room_id);
        $loggedInUser = auth()->user() ?? User::find(session('user_id'));
        $customerName = $loggedInUser?->name ?: ($loggedInUser?->username ?: $request->customer_name);
        $customerPhone = $loggedInUser?->phone ?: $request->customer_phone;
        $customerEmail = $loggedInUser?->email ?: $request->customer_email;

        $nights = \Carbon\Carbon::parse($request->check_in_date)
            ->diffInDays(\Carbon\Carbon::parse($request->check_out_date));
        $totalPrice = $room->price * $nights;

        $booking = Booking::create([
            'user_id' => $loggedInUser?->id ?? 1, // ใช้ user_id จาก auth/session หรือ 1 ถ้าไม่มี
            'room_id' => $request->room_id,
            'customer_name' => $customerName,
            'customer_phone' => $customerPhone,
            'customer_email' => $customerEmail,
            'check_in_date' => $request->check_in_date,
            'check_out_date' => $request->check_out_date,
            'total_price' => $totalPrice,
            'status' => 'pending'
        ]);

        // อัปเดตสถานะห้องเป็น 'reserved' เมื่อมีการจอง
        \App\Models\Rooms::where('id', $request->room_id)
            ->update(['status' => 'reserved']);

        return redirect()->route('booking.payment', $booking);
    }

    public function payment(Booking $booking)
    {
        $booking->loadMissing('room');

        $nights = Carbon::parse($booking->check_in_date)
            ->diffInDays(Carbon::parse($booking->check_out_date));

        $roomQuantity = (int) ($booking->room_quantity ?? 1);
        $nightlyPrice = (float) ($booking->room->price ?? 0);

        $calculatedTotal = $nightlyPrice * $nights * $roomQuantity;

        if ((float) $booking->total_price !== (float) $calculatedTotal) {
            $booking->update(['total_price' => $calculatedTotal]);
            $booking->refresh();
        }

        return view('booking.payment', [
            'booking' => $booking,
            'calculatedNights' => $nights,
            'roomQuantity' => $roomQuantity,
            'nightlyPrice' => $nightlyPrice,
            'calculatedTotal' => $calculatedTotal,
        ]);
    }

    public function uploadPayment(Request $request, Booking $booking)
    {
        $request->validate([
            'payment_qr' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($request->hasFile('payment_qr')) {
            $path = $request->file('payment_qr')->store('payments', 'public');
            $booking->update([
                'payment_qr' => $path,
                'payment_confirmed_at' => now(),
                'status' => 'confirmed'
            ]);

            // อัปเดตสถานะห้องเป็น 'reserved' (ยืนยันการจอง)
            \App\Models\Rooms::where('id', $booking->room_id)
                ->update(['status' => 'reserved']);
            
            // สร้างข้อมูลการชำระเงินไปยัง admin/payments
            $this->createPaymentRecord($booking);
        }

        return redirect()->route('booking.receipt', $booking);
    }
    
    /**
     * สร้างข้อมูลการชำระเงินสำหรับแส่งไป admin/payments
     */
    private function createPaymentRecord(Booking $booking)
    {
        // สร้างข้อมูลการชำระเงินในตาราง payments
        \App\Models\Payment::create([
            'room_id' => $booking->room_id,
            'user_id' => $booking->user_id,
            'tenant_name' => $booking->customer_name,
            'type' => 'other',
            'description' => 'ค่าห้องพัก - จองออนไลน์',
            'amount' => $booking->total_price,
            'date' => now()->toDateString(),
            'status' => 'paid',
            'booking_id' => $booking->id,
            'customer_name' => $booking->customer_name,
            'customer_email' => $booking->customer_email,
            'customer_phone' => $booking->customer_phone,
            'room_name' => $booking->room->name_room,
            'room_type' => $booking->room->type,
            'check_in_date' => $booking->check_in_date,
            'check_out_date' => $booking->check_out_date,
            'payment_qr' => $booking->payment_qr,
            'payment_confirmed_at' => $booking->payment_confirmed_at,
            'remark' => 'ชำระเงินผ่าน QR Code',
            'payment_method' => 'bank_transfer',
            'payment_status' => 'paid',
            'payment_date' => now(),
            'slip_image' => $booking->payment_qr,
            'notes' => 'ชำระเงินผ่าน QR Code'
        ]);
    }

    public function receipt(Booking $booking)
    {
        $booking->load('room');
        return view('booking.receipt', compact('booking'));
    }

    public function history(Request $request)
    {
        $currentUser = auth()->user() ?? User::find(session('user_id'));
        
        if (!$currentUser) {
            return redirect()->route('login.form')->with('error', 'กรุณาเข้าสู่ระบบเพื่อดูประวัติการจอง');
        }

        $bookings = Booking::with(['room', 'user'])
            ->where('user_id', $currentUser->id)
            ->orderByDesc('created_at')
            ->get();

        return view('booking.history', [
            'bookings' => $bookings,
            'currentUser' => $currentUser,
        ]);
    }

    public function destroyFromHistory(Request $request, Booking $booking)
    {
        $keyword = trim((string) $request->input('keyword', ''));
        $sessionUserId = session('user_id');
        $allowed = false;

        if ($sessionUserId) {
            $allowed = (int) $booking->user_id === (int) $sessionUserId;
        } elseif ($keyword !== '') {
            $allowed = strcasecmp((string) $booking->customer_name, $keyword) === 0;
        }

        if (!$allowed) {
            return redirect()->route('booking.history', ['keyword' => $keyword])
                ->with('error', 'ไม่สามารถยกเลิกการจองรายการนี้ได้');
        }

        DB::transaction(function () use ($booking) {
            Payment::where('booking_id', $booking->id)->delete();
            $booking->delete();
        });

        return redirect()->route('booking.history', ['keyword' => $keyword])
            ->with('success', 'ยกเลิกการจองเรียบร้อยแล้ว');
    }
}
