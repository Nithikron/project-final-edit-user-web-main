@extends('admin.layouts.app')

@section('title', 'รายงาน')

@section('content')
    <div id="content-reports" class="tab-content">

        {{-- Summary Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-sm text-gray-500 mb-2">รายได้ทั้งหมด</h3>
                <p class="text-3xl font-bold text-purple-600">
                    {{ number_format($total, 2) }} บาท
                </p>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-sm text-gray-500 mb-2">ยอดที่ชำระแล้ว</h3>
                <p class="text-3xl font-bold text-green-600">
                    {{ number_format($paid, 2) }} บาท
                </p>
            </div>

            {{-- <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-sm text-gray-500 mb-2">ยอดค้างชำระ</h3>
                <p class="text-3xl font-bold text-red-600">
                    {{ number_format($pending, 2) }} บาท
                </p>
            </div> --}}
        </div>

        {{-- Table --}}
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-xl font-bold mb-4 text-gray-800">ประวัติการชำระเงิน</h3>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">#</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Booking ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">จำนวนเงิน</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">สถานะ</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">วันที่</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($items as $index => $item)
                            <tr>
                                <td class="px-6 py-4">{{ $index + 1 }}</td>
                                <td class="px-6 py-4">{{ $item->booking_id ?? '-' }}</td>
                                <td class="px-6 py-4">
                                    {{ number_format($item->amount, 2) }} บาท
                                </td>
                                <td class="px-6 py-4">
                                    @if ($item->status === 'จ่าย')
                                        <span
                                            class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-xs font-medium">
                                            ชำระแล้ว
                                        </span>
                                    @else
                                        <span class="bg-red-100 text-red-800 px-3 py-1 rounded-full text-xs font-medium">
                                            ค้างชำระ
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    {{ $item->created_at->format('d/m/Y') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-6 text-center text-gray-500">
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
