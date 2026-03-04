@extends('admin.layouts.app')

@section('title', 'บันทึกการชำระเงิน')

@section('content')
    <!-- Global Messages -->
    @if (session('success'))
        <div class="mb-6 bg-green-100 text-green-800 p-4 rounded-lg shadow">
            <strong>✓ สำเร็จ:</strong> {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="mb-6 bg-red-100 text-red-700 p-4 rounded-lg shadow">
            <strong>✗ ผิดพลาด:</strong> {{ session('error') }}
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-lg p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">บันทึกการชำระเงิน</h2>
            <button onclick="switchAddPaymentTab()"
                class="bg-gradient-to-r from-purple-600 to-blue-600 text-white px-6 py-3 rounded-lg hover:shadow-lg transition-all">
                ➕ บันทึกชำระเงิน
            </button>
        </div>

        <!-- Add Payment Form (Hidden by default) -->
        <div id="addPaymentForm" class="mb-6 p-6 bg-purple-50 rounded-lg hidden">
            <h3 class="text-xl font-bold text-purple-600 mb-4">เพิ่มบันทึกการชำระเงิน</h3>

            <form action="{{ route('admin.payment.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @csrf

                <div>
                    <label class="block font-medium mb-2">เลือกการจอง</label>
                    <select name="booking_id" class="w-full border rounded-lg px-4 py-2" required>
                        <option value="">-- เลือกการจอง --</option>
                        @foreach ($bookings ?? [] as $booking)
                            <option value="{{ $booking->id }}">
                                ห้อง {{ $booking->room_id }} - {{ $booking->tenant_name ?? $booking->customer_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block font-medium mb-2">จำนวนเงิน</label>
                    <input type="number" name="amount" step="0.01" class="w-full border rounded-lg px-4 py-2" required>
                </div>

                <div>
                    <label class="block font-medium mb-2">รายการ</label>
                    <input type="text" name="remark" class="w-full border rounded-lg px-4 py-2"
                        placeholder="เช่น ค่าห้องพัก, ค่าบริการ, อื่นๆ">
                </div>

                <div>
                    <label class="block font-medium mb-2">สถานะ</label>
                    <select name="status" class="w-full border rounded-lg px-4 py-2" required>
                        <option value="pending">ค้างชำระ</option>
                        <option value="paid">ชำระแล้ว</option>
                    </select>
                </div>

                <div class="md:col-span-2 flex gap-4">
                    <button type="submit" class="flex-1 bg-purple-600 text-white py-2 rounded-lg hover:bg-purple-700">
                        บันทึกการชำระเงิน
                    </button>
                    <button type="button" onclick="switchAddPaymentTab()"
                        class="flex-1 bg-gray-300 text-gray-700 py-2 rounded-lg hover:bg-gray-400">
                        ยกเลิก
                    </button>
                </div>
            </form>
        </div>

        <!-- Payments Table -->
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">วันที่</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ห้อง</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ผู้เข้าพัก</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">รายการ</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">จำนวนเงิน</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">สถานะ</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">จัดการ</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($payments as $payment)
                        <tr>
                            <td class="px-6 py-2">
                                {{ $payment->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-6 py-2">
                                @if ($payment->booking && $payment->booking->room_id)
                                    ห้อง {{ $payment->booking->room_id }}
                                @else
                                    -
                                @endif
                            </td>
                            <td class="px-6 py-2">
                                {{ $payment->booking->tenant_name ?? ($payment->booking->customer_name ?? '-') }}
                            </td>
                            <td class="px-6 py-2">
                                {{ $payment->remark ?? '-' }}
                            </td>
                            <td class="px-6 py-2">
                                {{ number_format($payment->amount, 2) }} บาท
                            </td>
                            <td class="px-6 py-2">
                                @if (strtolower($payment->status) === 'paid')
                                    <span
                                        class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-xs font-medium">ชำระแล้ว</span>
                                @else
                                    <span
                                        class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-xs font-medium">ค้างชำระ</span>
                                @endif
                            </td>
                            <td class="px-6 py-2">
                                <div class="flex gap-2">
                                    <button
                                        onclick="openEditModal({{ $payment->id }}, {{ $payment->booking_id }}, {{ $payment->amount }}, '{{ $payment->status }}', '{{ $payment->remark ?? '' }}')"
                                        class="text-blue-600 hover:underline text-sm">แก้ไข</button>
                                    <form action="{{ route('admin.payment.destroy', $payment->id) }}" method="POST"
                                        style="display: inline;" onsubmit="return confirm('คุณแน่ใจหรือไม่ว่าต้องการลบ?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:underline text-sm">ลบ</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-6 text-gray-400">
                                ยังไม่มีบันทึกการชำระเงิน
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Edit Payment Modal -->
    <div id="editPaymentModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
            <h3 class="text-xl font-bold text-blue-600 mb-4">แก้ไขบันทึกการชำระเงิน</h3>

            <form id="editPaymentForm" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label class="block font-medium mb-2">เลือกการจอง</label>
                    <select name="booking_id" id="editBookingId" class="w-full border rounded-lg px-4 py-2" required>
                        <option value="">-- เลือกการจอง --</option>
                        @foreach ($bookings ?? [] as $booking)
                            <option value="{{ $booking->id }}">
                                ห้อง {{ $booking->room_id }} - {{ $booking->tenant_name ?? $booking->customer_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block font-medium mb-2">จำนวนเงิน</label>
                    <input type="number" name="amount" id="editAmount" step="0.01"
                        class="w-full border rounded-lg px-4 py-2" required>
                </div>

                <div>
                    <label class="block font-medium mb-2">รายการ</label>
                    <input type="text" name="remark" id="editRemark" class="w-full border rounded-lg px-4 py-2">
                </div>

                <div>
                    <label class="block font-medium mb-2">สถานะ</label>
                    <select name="status" id="editStatus" class="w-full border rounded-lg px-4 py-2" required>
                        <option value="pending">ค้างชำระ</option>
                        <option value="paid">ชำระแล้ว</option>
                    </select>
                </div>

                <div class="flex gap-4">
                    <button type="submit" class="flex-1 bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700">
                        บันทึก
                    </button>
                    <button type="button" onclick="closeEditModal()"
                        class="flex-1 bg-gray-300 text-gray-700 py-2 rounded-lg hover:bg-gray-400">
                        ยกเลิก
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function switchAddPaymentTab() {
            const form = document.getElementById('addPaymentForm');
            form.classList.toggle('hidden');
        }

        function openEditModal(paymentId, bookingId, amount, status, remark) {
            document.getElementById('editBookingId').value = bookingId;
            document.getElementById('editAmount').value = amount;
            document.getElementById('editStatus').value = status;
            document.getElementById('editRemark').value = remark;
            document.getElementById('editPaymentForm').action = `/admin/payment/${paymentId}`;
            document.getElementById('editPaymentModal').classList.remove('hidden');
        }

        function closeEditModal() {
            document.getElementById('editPaymentModal').classList.add('hidden');
        }
    </script>
@endsection
