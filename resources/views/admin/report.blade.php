@extends('admin.layouts.app')

@section('title', 'รายงาน')

@section('content')
    <div id="content-reports" class="tab-content">

        {{-- Summary Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg p-6 text-white">
                <h3 class="text-sm text-purple-100 mb-2">รายได้ทั้งหมด</h3>
                <p class="text-3xl font-bold">
                    {{ number_format($total, 2) }} บาท
                </p>
                <p class="text-xs text-purple-100 mt-2">(ห้องพัก + การชำระเงิน)</p>
            </div>

            <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white">
                <h3 class="text-sm text-blue-100 mb-2">รายได้จากห้องพัก</h3>
                <p class="text-3xl font-bold">
                    {{ number_format($roomRevenue, 2) }} บาท
                </p>
                <p class="text-xs text-blue-100 mt-2">(เช็คเอาท์: {{ number_format($checkoutRevenue, 2) }} | พักอยู่: {{ number_format($checkinRevenue, 2) }})</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div class="bg-green-50 rounded-xl shadow-lg p-6 border border-green-200">
                <h3 class="text-sm text-gray-500 mb-2">ยอดที่ชำระแล้ว</h3>
                <p class="text-2xl font-bold text-green-600">
                    {{ number_format($paid, 2) }} บาท
                </p>
            </div>

            <div class="bg-yellow-50 rounded-xl shadow-lg p-6 border border-yellow-200">
                <h3 class="text-sm text-gray-500 mb-2">การชำระเงินทั้งหมด</h3>
                <p class="text-2xl font-bold text-yellow-600">
                    {{ number_format($paymentTotal, 2) }} บาท
                </p>
            </div>
        </div>

        {{-- Room Revenue Breakdown --}}
        <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
            <h3 class="text-xl font-bold mb-4 text-gray-800">รายละเอียดรายได้จากห้องพัก</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Checked Out Revenue -->
                <div class="border border-gray-200 rounded-lg p-4">
                    <h4 class="font-semibold text-gray-700 mb-2">รายได้จากผู้เข้าพักที่เคลียร์ห้องแล้ว</h4>
                    <p class="text-2xl font-bold text-green-600">{{ number_format($checkoutRevenue, 2) }} บาท</p>
                    <p class="text-xs text-gray-500 mt-2">เงินจากผู้เข้าพักที่เช็คเอาท์แล้ว</p>
                </div>
                
                <!-- Current Occupancy Revenue -->
                <div class="border border-gray-200 rounded-lg p-4">
                    <h4 class="font-semibold text-gray-700 mb-2">รายได้จากผู้เข้าพักในขณะนี้</h4>
                    <p class="text-2xl font-bold text-blue-600">{{ number_format($checkinRevenue, 2) }} บาท</p>
                    <p class="text-xs text-gray-500 mt-2">เงินจากห้องที่มีผู้พักอยู่</p>
                </div>
            </div>
        </div>

        {{-- Table --}}
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-xl font-bold mb-4 text-gray-800">ประวัติการชำระเงิน</h3>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">#</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ชื่อผู้เข้าพัก</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ชื่อห้อง</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">จำนวนเงินห้องพัก</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">สถานะ</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">วันเดือนปีและเวลา</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($items as $index => $item)
                            <tr>
                                <td class="px-6 py-4">{{ $index + 1 }}</td>
                                <td class="px-6 py-4">
                                    {{ $item->booking?->customer_name ?? '-' }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $item->room?->name_room ?? '-' }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ number_format($item->booking?->total_price ?? $item->amount, 2) }} บาท
                                </td>
                                <td class="px-6 py-4">
                                    @if (strtolower($item->status) === 'paid')
                                        <span
                                            class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-xs font-medium">
                                            ชำระแล้ว
                                        </span>
                                    @else
                                        <span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-xs font-medium">
                                            ค้างชำระ
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    {{ $item->created_at->format('d/m/Y H:i') ?? '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-6 text-center text-gray-500">
                                    ยังไม่มีข้อมูลการชำระเงิน
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
@endsection
