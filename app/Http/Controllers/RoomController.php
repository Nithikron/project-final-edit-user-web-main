<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function index()
    {
        return view('rooms.index');
    }

    public function byType(Request $request, $type)
    {
        $checkIn = $request->query('check_in');
        $checkOut = $request->query('check_out');
        
        $query = Room::where('status', 'available')->byType($type);
        
        if (!$checkIn || !$checkOut) {
            $checkIn = Carbon::today()->toDateString();
            $checkOut = Carbon::tomorrow()->toDateString();
        }

        $rooms = $query->get();
        
        return view('rooms.type', compact('rooms', 'type', 'checkIn', 'checkOut'));
    }

    public function show(Room $room)
    {
        return view('rooms.show', compact('room'));
    }
}
