<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\Room;
use App\Models\Booking;

class CalendarService
{
    public function generate($month, $year, $roomId = null)
    {
        $firstDay = Carbon::create($year, $month, 1);
        $startDayOfWeek = $firstDay->dayOfWeekIso;
        $daysInMonth = $firstDay->daysInMonth;
        
        $calendar = [];
        $weeks = [];
        
        // ดึงข้อมูลห้อง
        $rooms = $roomId ? Room::where('id', $roomId)->get() : Room::all();
        
        // ดึงข้อมูลการจองทั้งหมดในเดือนนี้
        $bookings = Booking::whereIn('room_id', $rooms->pluck('id'))
            ->whereMonth('check_in_date', $month)
            ->whereYear('check_in_date', $year)
            ->orWhereMonth('check_out_date', $month)
            ->whereYear('check_out_date', $year)
            ->get();
        
        // สร้างข้อมูลปฏิทิน
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
            $isWeekend = $date->isWeekend();
            
            $dayData = [
                'empty' => false,
                'day' => $day,
                'date' => $date,
                'today' => $isToday,
                'weekend' => $isWeekend,
                'available' => true,
                'rooms' => []
            ];
            
            // ตรวจสอบว่าห้องว่างหรือไม่
            foreach ($rooms as $room) {
                $isAvailable = $this->isRoomAvailable($room, $date, $bookings);
                $dayData['rooms'][$room->id] = [
                    'room' => $room,
                    'available' => $isAvailable,
                    'status' => $isAvailable ? 'available' : 'booked'
                ];
                
                // ถ้ามีห้องใดใดหนึ่งไม่ว่าง ให้วันนั้น
                if (!$isAvailable) {
                    $dayData['available'] = false;
                }
            }
            
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
    
    /**
     * ตรวจสอบว่าห้องว่างในวันที่ที่กำหนด
     */
    private function isRoomAvailable($room, $date, $bookings)
    {
        foreach ($bookings as $booking) {
            $checkIn = Carbon::parse($booking->check_in_date);
            $checkOut = Carbon::parse($booking->check_out_date);
            
            // ตรวจสอบว่าวันที่ตรงกับการจอง
            if ($date->between($checkIn, $checkOut) || 
                $date->isSameDay($checkIn) || 
                $date->isSameDay($checkOut)) {
                return false;
            }
        }
        
        return true;
    }
    
    /**
     * สร้างข้อมูลปฏิทินสำหรับห้องเดี่ยว์ (สำหรับหน้า calendar)
     */
    public function generateForCalendarPage($month, $year, $roomType = 'all', $roomId = null)
    {
        return $this->generate($month, $year, $roomId);
    }
    
    /**
     * สร้างข้อมูลปฏิทินสำหรับห้องเดี่ยว์ (สำหรับหน้าจอง)
     */
    public function generateForBookingPage($month, $year, $roomId)
    {
        return $this->generate($month, $year, $roomId);
    }
}
