@extends('admin.layouts.app')

@section('content')
    <div class="container mx-auto px-6 pb-12">

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">

            <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-green-500">
                <p class="text-gray-600 text-sm">ห้องว่าง</p>
                <p class="text-3xl font-bold text-green-600">{{ $available }}</p>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-red-500">
                <p class="text-gray-600 text-sm">ห้องไม่ว่าง</p>
                <p class="text-3xl font-bold text-red-600">{{ $occupied }}</p>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-yellow-500">
                <p class="text-gray-600 text-sm">ห้องจอง</p>
                <p class="text-3xl font-bold text-yellow-600">{{ $reserved }}</p>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-purple-500">
                <p class="text-gray-600 text-sm">รายได้เดือนนี้</p>
                <p class="text-3xl font-bold text-purple-600">
                    {{ number_format($monthlyIncome) }} ฿
                </p>
            </div>

        </div>

        <div class="bg-white rounded-xl shadow-lg p-6">
            <h2 class="text-2xl font-bold mb-6">สถานะห้องพักทั้งหมด</h2>

            <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                @foreach ($rooms as $room)
                    <div
                        class="
                    {{ $room->status == 'available'
                        ? 'bg-green-500'
                        : ($room->status == 'occupied'
                            ? 'bg-red-500'
                            : 'bg-yellow-500') }}
                    text-white rounded-lg p-4 text-center">

                        <div class="text-2xl font-bold">ห้อง {{ $room->name_room }}</div>
                        <div class="text-sm mt-1">
                            {{ $room->status == 'available' ? 'ว่าง' : ($room->status == 'occupied' ? 'ไม่ว่าง' : 'จอง') }}
                        </div>
                        <div class="text-xs mt-1">ห้อง : {{ $room->type}} {{ $room->facility}}</div>
                    </div>
                @endforeach
            </div>
        </div>

    </div>
@endsection
