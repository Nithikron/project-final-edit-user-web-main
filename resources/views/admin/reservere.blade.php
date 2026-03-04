@extends('admin.layouts.app')

@section('title', 'จองหอพัก')

@section('content')

<div class="bg-white p-8 rounded-xl shadow">

    <h1 class="text-3xl font-bold mb-6 text-green-600">➖ จองหอพัก</h1>

@if (session('success'))
    <div class="bg-green-100 text-green-800 p-4 rounded mb-4">
        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div class="bg-red-100 text-red-700 p-4 rounded mb-4">
        {{ session('error') }}
    </div>
@endif


    <form action="/admin/reservere-store" method="POST">
        @csrf

        <div class="mb-6">
            <label class="block font-medium mb-1">เลือกห้อง</label>
            <select name="room_id"
                class="w-full border rounded-lg px-4 py-2"
                required>

                <option value="">-- เลือกห้องพัก --</option>

                @foreach ($rooms as $room)
                    <option value="{{ $room->id }}">
                        ห้อง {{ $room->name_room }}
                    </option>
                @endforeach

            </select>
        </div>

        <div class="mb-6">
            <label class="block font-medium mb-1">ชื่อผู้เข้าพัก</label>
            <input type="text"
                   name="tenant_name"
                   class="w-full border rounded-lg px-4 py-2"
                   required>
        </div>

        <div class="flex gap-4">
                <button type="submit" class="flex-1 bg-green-600 text-white py-2 rounded-lg hover:bg-green-700">
                    จองหอพัก
                </button>

                <a href="/check-in-out" class="flex-1 bg-gray-300 text-center py-2 rounded-lg hover:bg-gray-400">
                    ยกเลิก
                </a>
            </div>
    </form>

</div>

@endsection