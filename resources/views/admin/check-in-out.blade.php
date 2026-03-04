@extends('admin.layouts.app')

@section('title', 'จัดการห้องพัก')

@section('content')
    <div class="tab-content">
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h2 class="text-2xl font-bold mb-6 text-gray-800">จอง/เช็คอิน/เช็คเอาท์</h2>

            <!-- Tab Navigation -->
            <div class="flex gap-4 mb-6 border-b">
                <button onclick="switchTab('reserve')"
                    class="py-2 px-4 border-b-2 border-transparent hover:border-green-500 font-semibold text-green-600 tab-btn"
                    data-tab="reserve">
                    📅 จองห้องพัก
                </button>
                <button onclick="switchTab('checkin')"
                    class="py-2 px-4 border-b-2 border-transparent hover:border-blue-500 font-semibold text-blue-600 tab-btn"
                    data-tab="checkin">
                    ✅ เช็คอิน
                </button>
                <button onclick="switchTab('checkout')"
                    class="py-2 px-4 border-b-2 border-transparent hover:border-red-500 font-semibold text-red-600 tab-btn"
                    data-tab="checkout">
                    🚪 เช็คเอาท์
                </button>
            </div>

            <!-- Reserve Form Tab -->
            <div id="reserve" class="tab-content-panel mb-8 hidden">
                <div class="bg-green-50 p-6 rounded-lg">
                    <h3 class="text-xl font-bold text-green-600 mb-4">จองห้องพัก</h3>

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

                    <form action="{{ route('admin.reserve.store') }}" method="POST">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block font-medium mb-2">เลือกห้อง</label>
                                <select name="room_id" class="w-full border rounded-lg px-4 py-2" required>
                                    <option value="">-- เลือกห้องพัก --</option>
                                    @foreach ($availableRooms as $room)
                                        <option value="{{ $room->id }}">
                                            ห้อง {{ $room->name_room }} ({{ $room->price }} บาท/วัน)
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block font-medium mb-2">ชื่อผู้เข้าพัก</label>
                                <input type="text" name="tenant_name" class="w-full border rounded-lg px-4 py-2"
                                    required>
                            </div>
                        </div>
                        <button type="submit" class="mt-4 bg-green-600 text-white py-2 px-6 rounded-lg hover:bg-green-700">
                            จองห้องพัก
                        </button>
                    </form>
                </div>
            </div>

            <!-- Check-In Form Tab -->
            <div id="checkin" class="tab-content-panel mb-8 hidden">
                <div class="bg-blue-50 p-6 rounded-lg">
                    <h3 class="text-xl font-bold text-blue-600 mb-4">เช็คอิน</h3>

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

                    @if ($errors->any())
                        <div class="bg-red-100 text-red-700 p-4 rounded mb-4">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.checkin.store') }}" method="POST">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block font-medium mb-2">เลือกห้อง</label>
                                <select name="room_id" class="w-full border rounded-lg px-4 py-2" required>
                                    <option value="">-- เลือกห้องพัก --</option>
                                    @foreach ($bookableRooms as $room)
                                        <option value="{{ $room->id }}">
                                            ห้อง {{ $room->name_room }} ({{ $room->price }} บาท/วัน)
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block font-medium mb-2">ชื่อผู้เข้าพัก</label>
                                <input type="text" name="tenant_name" class="w-full border rounded-lg px-4 py-2"
                                    required>
                            </div>
                        </div>
                        <div class="mt-4">
                            <label class="block font-medium mb-2">หมายเหตุ</label>
                            <textarea name="notes" class="w-full border rounded-lg px-4 py-2"></textarea>
                        </div>
                        <button type="submit" class="mt-4 bg-blue-600 text-white py-2 px-6 rounded-lg hover:bg-blue-700">
                            เช็คอิน
                        </button>
                    </form>
                </div>
            </div>

            <!-- Check-Out Form Tab -->
            <div id="checkout" class="tab-content-panel mb-8 hidden">
                <div class="bg-red-50 p-6 rounded-lg">
                    <h3 class="text-xl font-bold text-red-600 mb-4">เช็คเอาท์</h3>

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

                    <form action="{{ route('admin.checkout.store') }}" method="POST">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block font-medium mb-2">เลือกห้อง (ห้องที่มีผู้พัก)</label>
                                <select name="room_id" class="w-full border rounded-lg px-4 py-2" required>
                                    <option value="">-- เลือกห้องพัก --</option>
                                    @foreach ($occupiedRooms as $room)
                                        <option value="{{ $room->id }}">
                                            ห้อง {{ $room->name_room }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block font-medium mb-2">ชื่อผู้เข้าพัก</label>
                                <input type="text" name="tenant_name" class="w-full border rounded-lg px-4 py-2"
                                    required>
                            </div>
                        </div>
                        <button type="submit" class="mt-4 bg-red-600 text-white py-2 px-6 rounded-lg hover:bg-red-700">
                            เช็คเอาท์
                        </button>
                    </form>
                </div>
            </div>

            <!-- History Table -->
            <div class="border-t pt-6">
                <h3 class="text-xl font-bold mb-4">ประวัติการจอง/เช็คอิน/เช็คเอาท์</h3>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">วันที่</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ประเภท</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ห้อง</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ผู้เข้าพัก</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">รายละเอียด</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($bookings as $b)
                                <tr>
                                    <td class="px-6 py-2">
                                        {{ $b->created_at->format('d/m/Y H:i') }}
                                    </td>

                                    <td class="px-6 py-2">
                                        @if ($b->type === 'reserve')
                                            <span class="text-blue-600 font-bold">จอง</span>
                                        @elseif ($b->type === 'checkin')
                                            <span class="text-green-600 font-bold">เช็คอิน</span>
                                        @elseif ($b->type === 'checkout')
                                            <span class="text-red-600 font-bold">เช็คเอาท์</span>
                                        @else
                                            <span class="text-gray-400">ไม่ทราบสถานะ</span>
                                        @endif
                                    </td>

                                    <td class="px-6 py-2">
                                        ห้อง {{ $b->room_id ?? '-' }}
                                    </td>

                                    <td class="px-6 py-2">
                                        {{ $b->tenant_name ?? '-' }}
                                    </td>

                                    <td class="px-6 py-2">
                                        {{ $b->notes ?? '-' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-6 text-gray-400">
                                        ยังไม่มีประวัติการจอง / เช็คอิน / เช็คเอาท์
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-6">
                {{ $bookings->links() }}
            </div>
        </div>
    </div>

    <script>
        function switchTab(tabName) {
            // Hide all tabs
            const panels = document.querySelectorAll('.tab-content-panel');
            panels.forEach(panel => {
                panel.classList.add('hidden');
            });

            // Show selected tab
            const selectedPanel = document.getElementById(tabName);
            if (selectedPanel) {
                selectedPanel.classList.remove('hidden');
            }

            // Update button styles
            const buttons = document.querySelectorAll('.tab-btn');
            buttons.forEach(btn => {
                if (btn.getAttribute('data-tab') === tabName) {
                    btn.classList.add('border-b-2');
                    btn.style.borderBottomColor = btn.classList.contains('text-green-600') ? '#16a34a' :
                        btn.classList.contains('text-blue-600') ? '#2563eb' : '#dc2626';
                } else {
                    btn.classList.remove('border-b-2');
                    btn.style.borderBottomColor = 'transparent';
                }
            });
        }

        // Initialize - show first tab
        document.addEventListener('DOMContentLoaded', function() {
            switchTab('reserve');
        });
    </script>
@endsection
