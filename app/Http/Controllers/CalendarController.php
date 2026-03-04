<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Booking;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Services\CalendarService;

class CalendarController extends Controller
{
    protected $calendarService;
    
    public function __construct(CalendarService $calendarService)
    {
        $this->calendarService = $calendarService;
    }
    
    public function index(Request $request)
    {
        $month = $request->get('month', Carbon::now()->month);
        $year = $request->get('year', Carbon::now()->year);
        $roomType = $request->get('type', 'all');
        $roomId = $request->get('room_id');
        
        $rooms = Room::when($roomType !== 'all', function ($query) use ($roomType) {
                return $query->where('type', $roomType);
            })
            ->when($roomId, function ($query) use ($roomId) {
                return $query->where('id', $roomId);
            })
            ->orderBy('name_room')
            ->get();
        
        $calendar = $this->calendarService->generateForCalendarPage($month, $year, $roomType, $roomId);
        
        // Debug: ตรวจสอบข้อมูล
        if (empty($calendar) || !isset($calendar['weeks'])) {
            // สร้างข้อมูลปฏิทินเปล่าถ้าไม่มีข้อมูล
            $calendar = $this->calendarService->generateEmptyCalendar($month, $year);
        }
        
        // เพิ่มข้อมูลสำหรับ navigation
        $currentDate = Carbon::create($year, $month, 1);
        $prevDate = $currentDate->copy()->subMonth();
        $nextDate = $currentDate->copy()->addMonth();
        
        $monthNames = ['มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน', 
                       'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม'];
        
        // ส่งข้อมูลทั้งหมดไปยัง view
        return view('calendar.index', [
            'calendar' => $calendar,
            'month' => $month,
            'year' => $year,
            'roomType' => $roomType,
            'rooms' => $rooms,
            'roomId' => $roomId,
            'monthName' => $monthNames[$month - 1],
            'prevMonth' => $prevDate->month,
            'prevYear' => $prevDate->year,
            'nextMonth' => $nextDate->month,
            'nextYear' => $nextDate->year
        ]);
    }
    
    public function checkAvailability(Request $request)
    {
        $roomId = $request->input('room_id');
        $month = $request->input('month', date('n'));
        $year = $request->input('year', date('Y'));
        
        // ถ้ามี room_id ให้ดึงเฉพาะห้องนั้น
        if ($roomId) {
            $room = Room::findOrFail($roomId);
            $rooms = collect([$room]);
        } else {
            // ถ้าไม่มี room_id ให้ดึงตามประเภท
            $type = $request->input('type', 'all');
            if ($type === 'all') {
                $rooms = Room::all();
            } else {
                $rooms = Room::byType($type)->get();
            }
        }
        
        $firstDay = Carbon::create($year, $month, 1);
        $startDayOfWeek = $firstDay->dayOfWeek == 0 ? 7 : $firstDay->dayOfWeek;
        $daysInMonth = $firstDay->daysInMonth;
        
        $calendar = [];
        
        // ดึงข้อมูลการจองทั้งหมดในเดือนนั้น
        $bookings = Booking::whereMonth('check_in_date', $month)
            ->whereYear('check_in_date', $year)
            ->orWhereMonth('check_out_date', $month)
            ->whereYear('check_out_date', $year)
            ->get();
        
        // สร้างข้อมูลปฏิทิน
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $date = Carbon::create($year, $month, $day);
            $dayData = [
                'date' => $date,
                'day' => $day,
                'rooms' => []
            ];
            
            foreach ($rooms as $room) {
                $isAvailable = $room->isAvailableForDates($date, $date->copy()->addDay());
                $dayData['rooms'][$room->id] = [
                    'room' => $room,
                    'available' => $isAvailable,
                    'status' => $isAvailable ? 'available' : 'booked'
                ];
            }
            
            $calendar[$day] = $dayData;
        }
        
        $calendar = $this->calendarService->generate($month, $year, $roomId);
        
        return response()->json([
            'days' => $calendar,
            'firstDay' => $calendar['firstDay'],
            'daysInMonth' => $calendar['daysInMonth'],
            'monthName' => $firstDay->format('F'),
            'year' => $year
        ]);
    }
    
    public function generateEmptyCalendar($month, $year)
    {
        $firstDay = Carbon::create($year, $month, 1);
        $startDayOfWeek = $firstDay->dayOfWeekIso;
        $daysInMonth = $firstDay->daysInMonth;
        
        $calendar = [];
        $weeks = [];
        
        $dayCount = 1;
        $week = [];
        
        // เติมวันว่างก่อนวันที่ 1
        for ($i = 1; $i < $startDayOfWeek; $i++) {
            $week[] = ['empty' => true];
        }
        
        // สร้างวันที่ในเดือน
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $date = Carbon::create($year, $month, $day);
            $today = Carbon::now();
            $isToday = $date->isSameDay($today);
            
            $dayData = [
                'empty' => false,
                'day' => $day,
                'date' => $date,
                'today' => $isToday,
                'weekend' => $date->isWeekend(),
                'rooms' => []
            ];
            
            $week[] = $dayData;
            
            // ถ้าครบ 7 วันให้เริ่มสัปดาห์ใหม่
            if (count($week) == 7) {
                $weeks[] = $week;
                $week = [];
            }
        }
        
        // เติมวันที่เหลือในสัปดาห์สุดท้าย
        if (count($week) > 0) {
            while (count($week) < 7) {
                $week[] = ['empty' => true];
            }
            $weeks[] = $week;
        }
        
        return [
            'weeks' => $weeks,
            'monthName' => $firstDay->format('F'),
            'year' => $year,
            'month' => $month,
            'daysInMonth' => $daysInMonth,
            'firstDay' => $startDayOfWeek
        ];
    }
}
