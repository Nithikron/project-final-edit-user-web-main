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
            'type' => 'required|string|in:air_single,air_double,fan_single,fan_double',
            'price' => 'required|numeric',
        ]);

        // Map type to facility array
        $facilityMap = [
            'air_single' => ['แอร์'],
            'air_double' => ['แอร์'],
            'fan_single' => ['พัดลม'],
            'fan_double' => ['พัดลม'],
        ];

        Rooms::create([
            'name_room' => $request->name_room,
            'type' => $request->type,
            'facility' => $facilityMap[$request->type],
            'price' => $request->price,
            'status' => 'available',
        ]);

        // หลังเพิ่มแล้วให้ไปหน้ารายการห้องพักของแอดมิน
        return redirect()->route('admin.roompages')->with('success', 'เพิ่มห้องพักเรียบร้อย');
    }

    public function edit($id)
    {
        $room = Rooms::findOrFail($id);

        // decompose type back into machine-friendly parts for the form
        if (in_array($room->type, ['เดี่ยว', 'คู่'])) {
            // legacy entry – type stored in thai word, facility lives in
            // the facility json column
            $room->form_type = $room->type;
            $room->form_facility = $room->facility[0] ?? '';
        } else {
            [$type, $facility] = $this->unmapType($room->type);
            $room->form_type = $type;
            $room->form_facility = $facility;
        }

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

        $mappedType = $this->mapType($request->type, $request->facility);

        $room->update([
            'name_room' => $request->name_room,
            'type' => $mappedType,
            'facility' => [$request->facility],
            'price' => $request->price,
        ]);

        // หลังบันทึกให้กลับไปที่หน้าแอดมินรายการห้องพัก
        return redirect()->route('admin.roompages')->with('success', 'แก้ไขห้องพักเรียบร้อย');
    }

    public function destroy($id)
    {
        Rooms::findOrFail($id)->delete();

        return redirect()->back()->with('success', 'ลบห้องพักเรียบร้อย');
    }

    /**
     * Combine the two select values into the canonical type stored in the
     * rooms table. Uses the same keywords as the public-facing `Room`
     * model's `type_label` accessor.
     */
    private function mapType(string $type, string $facility): string
    {
        $t = $type === 'คู่' ? 'double' : 'single';
        $f = $facility === 'แอร์' ? 'air' : 'fan';

        return "{$f}_{$t}";
    }

    /**
     * Reverse of mapType - used when editing an existing room so we can
     * pre-populate the two dropdowns.
     */
    private function unmapType(string $stored): array
    {
        // default fallbacks in case stored value is already the thai words
        if ($stored === 'เดี่ยว' || $stored === 'คู่') {
            return [$stored, ''];
        }

        if (str_starts_with($stored, 'air_')) {
            $facility = 'แอร์';
        } else {
            $facility = 'พัดลม';
        }

        $t = str_ends_with($stored, '_double') ? 'คู่' : 'เดี่ยว';

        return [$t, $facility];
    }
}