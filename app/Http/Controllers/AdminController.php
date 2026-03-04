<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function uploadImage(Request $request, Room $room)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($request->hasFile('image')) {
            // ลบรูปเก่าถ้ามี
            if ($room->image) {
                $oldPath = storage_path('app/public/' . $room->image);
                if (file_exists($oldPath)) {
                    unlink($oldPath);
                }
            }

            // อัปโหลดรูปใหม่
            $path = $request->file('image')->store('rooms', 'public');
            $room->update(['image' => $path]);
        }

        return back()->with('success', 'อัปโหลดรูปภาพสำเร็จแล้ว');
    }
}
