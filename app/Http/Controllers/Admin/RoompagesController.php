<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Rooms;
use Illuminate\Http\Request;

class RoompagesController extends Controller
{
    public function index()
    {
        $rooms = Rooms::orderBy('name_room')->paginate(6);

        return view('admin.roompages', compact('rooms'));
    }

    public function create() {
        return view('admin.roompages-create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name_room' => 'required|string',
            'type' => 'required|string',
            'facility' => 'required|string',
            'price' => 'required|numeric',
        ]);

        Rooms::create([
            'name_room' => $request->name_room,
            'type' => $request->type,
            'facility' => [$request->facility],
            'price' => $request->price,
            'status' => 'available',
        ]);

        return redirect()->back()->with('success', 'เพิ่มห้องพักเรียบร้อย');
    }

    public function edit($id)
    {
        $room = Rooms::findOrFail($id);

        return view('admin.roompage-edit', compact('room'));
    }

    public function update(Request $request, $id)
    {
        $room = Rooms::findOrFail($id);

        $request->validate([
            'name_room' => 'required|string',
            'type' => 'required|string',
            'facility' => 'required|string',
            'price' => 'required|numeric',
        ]);

        $room->update([
            'name_room' => $request->name_room,
            'type' => $request->type,
            'facility' => [$request->facility],
            'price' => $request->price,
        ]);

        return redirect()->back()->with('success', 'แก้ไขห้องพักเรียบร้อย');
    }

    public function destroy($id)
    {
        Rooms::findOrFail($id)->delete();

        return redirect()->back()->with('success', 'ลบห้องพักเรียบร้อย');
    }
}