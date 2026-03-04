@extends('admin.layouts.app')

@section('title', 'เช็กอิน')

@section('content')

    <div class="bg-white p-8 rounded-xl shadow">

        <h1 class="text-3xl font-bold mb-6">➕ เช็คอิน</h1>

        {{-- Error --}}
        @if ($errors->any())
            <div class="bg-red-100 text-red-700 p-4 rounded mb-4">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Success --}}
        @if (session('success'))
            <div class="bg-green-100 text-green-800 p-4 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <form action="/admin/check-in-store" method="POST">
            @csrf

            <select name="room_id" class="w-full border rounded-lg px-4 py-2" required>
                <option value="">-- เลือกห้องพัก --</option>

                @foreach ($rooms as $room)
                    <option value="{{ $room->id }}">
                        ห้อง {{ $room->name_room }}
                        ({{ $room->facility }} | {{ $room->price }} บาท/วัน)
                    </option>
                @endforeach
            </select>

            <div class="mb-6">
                <label class="block font-medium mb-1">ชื่อผู้เข้าพัก</label>
                <input type="text" name="tenant_name" class="w-full border rounded-lg px-4 py-2" required>
            </div>

            <div class="mb-6">
                <label class="block font-medium mb-1">หมายเหตุ</label>
                <textarea name="note" class="w-full border rounded-lg px-4 py-2"></textarea>
            </div>

            <div class="flex gap-4">
                <button type="submit" class="flex-1 bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700">
                    เช็คอิน
                </button>

                <a href="/check-in-out" class="flex-1 bg-gray-300 text-center py-2 rounded-lg hover:bg-gray-400">
                    ยกเลิก
                </a>
            </div>

        

    </div>

@endsection
