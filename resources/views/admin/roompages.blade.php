@extends('admin.layouts.app')

@section('title', 'จัดการห้องพัก')

@section('content')
    <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">จัดการห้องพัก</h2>
            <a href="/create-roompages"
                class="bg-gradient-to-r from-purple-600 to-blue-600 text-white px-6 py-3 rounded-lg hover:shadow-lg transition-all">
                ➕ เพิ่มห้องพัก
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

            @foreach ($rooms as $room)
                <div
                    class="border-2 rounded-xl p-6 {{ $room->status === 'available' ? 'border-green-200' : 'border-gray-200' }}">

                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h3 class="text-2xl font-bold text-gray-800">
                                ห้อง {{ $room->name_room }}
                            </h3>
                            <p class="text-gray-600">{{ $room->type }}</p>
                        </div>

                        <span
                            class="{{ $room->status === 'available'
                                ? 'bg-green-100 text-green-800'
                                : ($room->status === 'occupied'
                                    ? 'bg-red-100 text-red-800'
                                    : 'bg-yellow-100 text-yellow-800') }} px-3 py-1 rounded-full text-sm font-medium">
                            {{ $room->status === 'available' ? 'ว่าง' : ($room->status === 'occupied' ? 'ไม่ว่าง' : 'จอง') }}
                        </span>
                    </div>

                    <div class="text-2xl font-bold text-purple-600 mb-4">
                        {{ number_format($room->price) }} ฿/วัน
                    </div>

                    <div class="flex gap-2">
                        <a href="/edit-roompages/{{ $room->id }}"
                            class="flex-1 bg-blue-500 text-white px-4 py-2 rounded-lg text-center hover:bg-blue-600">
                            แก้ไข
                        </a>

                        <form action="/delete-roompages/{{ $room->id }}" method="POST" class="flex-1"
                            onsubmit="return confirm('คุณต้องการลบห้องพักนี้หรือไม่?')">
                            @csrf
                            @method('DELETE')
                            <button class="w-full bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600">
                                ลบ
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach

        </div>
        <div class="mt-10">
            {{ $rooms->links() }}
        </div>
    </div>
@endsection
