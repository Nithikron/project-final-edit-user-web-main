    @extends('admin.layouts.app')

    @section('title', 'จัดการผู้เข้าพัก')



    @section('content')
        <!-- Tenants Tab -->

        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-800">จัดการผู้เข้าพัก</h2>
                <button onclick="showAddTenantModal()"
                    class="bg-gradient-to-r from-purple-600 to-blue-600 text-white px-6 py-3 rounded-lg hover:shadow-lg transition-all">
                    ➕ เพิ่มผู้เข้าพัก
                </button>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full" id="tenants-table">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ชื่อ-นามสกุล</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">เบอร์โทร</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ห้อง</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">วันเข้าพัก</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">สถานะ</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200" id="tenants-tbody">
                        @if (isset($tenants))
                            @forelse($tenants as $tenant)
                                <tr>
                                    <td class="px-6 py-2">{{ $tenant->tenant_name }}</td>
                                    <td class="px-6 py-2">
                                        {{-- แสดงเบอร์โทรจาก customer_phone หรือ user phone หรือ matched user phone --}}
                                        {{ $tenant->customer_phone ?: ($tenant->user?->phone ?: ($tenant->matched_user?->phone ?? '-')) }}
                                    </td>
                                    <td class="px-6 py-2">
                                        ห้อง {{ $tenant->room?->name_room ?? $tenant->room_id }}
                                    </td>
                                    <td class="px-6 py-2">
                                        {{ $tenant->date ? \Carbon\Carbon::parse($tenant->date)->format('d/m/Y H:i') : '-' }}
                                    </td>
                                    <td class="px-6 py-2">
                                        @if ($tenant->type === 'checkin')
                                            <span class="text-green-600 font-bold">อยู่</span>
                                        @elseif($tenant->type === 'checkout')
                                            <span class="text-red-600 font-bold">ออกแล้ว</span>
                                        @else
                                            <span class="text-gray-500">{{ $tenant->type }}</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-2">
                                        <!-- ปุ่มจัดการ (เช่น แก้ไข / เช็คเอาท์) -->
                                        <a href="#" class="text-blue-600 hover:underline edit-button"
                                            data-id="{{ $tenant->id }}" data-tenant_name="{{ $tenant->tenant_name }}"
                                            data-phone="{{ $tenant->customer_phone ?: ($tenant->user?->phone ?: ($tenant->matched_user?->phone ?? '')) }}" data-room_id="{{ $tenant->room_id }}"
                                            data-room_name="{{ $tenant->room?->name_room }}"
                                            data-date="{{ $tenant->date }}" data-type="{{ $tenant->type }}">แก้ไข</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-6 text-gray-400">
                                        ไม่มีผู้เข้าพักในขณะนี้
                                    </td>
                                </tr>
                            @endforelse
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Modal for editing tenant -->
        <div id="editTenantModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden">
            <div class="bg-white p-6 rounded-lg w-full max-w-md">
                <h3 class="text-xl font-bold mb-4">แก้ไขผู้เข้าพัก</h3>
                <form id="editTenantForm" method="POST" action="">
                    @csrf
                    @method('PUT')

                    <input type="hidden" name="id" id="tenant-id">

                    <div class="mb-4">
                        <label class="block text-gray-700">ชื่อ-นามสกุล</label>
                        <input type="text" name="tenant_name" id="tenant-name" class="w-full border rounded-lg px-4 py-2"
                            required>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700">เบอร์โทร</label>
                        <input type="text" name="phone" id="tenant-phone" class="w-full border rounded-lg px-4 py-2">
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700">ห้อง</label>
                        <select name="room_id" id="tenant-room" class="w-full border rounded-lg px-4 py-2" required>
                            @foreach ($rooms as $room)
                                <option value="{{ $room->id }}">ห้อง {{ $room->id }} - {{ $room->name_room }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700">วันที่เข้าพัก</label>
                        <input type="datetime-local" name="date" id="tenant-date"
                            class="w-full border rounded-lg px-4 py-2" required>
                    </div>

                    <div class="mt-4 flex justify-end">
                        <button type="button" onclick="hideEditTenantModal()" class="mr-2 px-4 py-2">ยกเลิก</button>
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">บันทึก</button>
                    </div>
                </form>
            </div>
        </div>

        <script>
            function showEditTenantModal(tenant) {
                console.log('showEditTenantModal', tenant);
                document.getElementById('tenant-id').value = tenant.id;
                document.getElementById('tenant-name').value = tenant.tenant_name || '';
                document.getElementById('tenant-phone').value = tenant.phone || '';
                document.getElementById('tenant-room').value = tenant.room_id;
                // format date for datetime-local
                if (tenant.date) {
                    try {
                        const dt = new Date(tenant.date);
                        if (!isNaN(dt)) {
                            const iso = dt.toISOString().slice(0, 16);
                            document.getElementById('tenant-date').value = iso;
                        }
                    } catch (e) {
                        console.warn('unable to parse date', tenant.date, e);
                    }
                }

                // set form action
                // ensure selected room is present in dropdown; if not, add it
                const select = document.getElementById('tenant-room');
                if (tenant.room_id) {
                    let option = select.querySelector('option[value="' + tenant.room_id + '"]');
                    if (!option) {
                        const name = tenant.room_name ? 'ห้อง ' + tenant.room_id + ' - ' + tenant.room_name : 'ห้อง ' + tenant
                            .room_id;
                        option = document.createElement('option');
                        option.value = tenant.room_id;
                        option.textContent = name;
                        select.appendChild(option);
                    }
                    select.value = tenant.room_id;
                }

                document.getElementById('editTenantForm').action = '/admin/booking/' + tenant.id;

                document.getElementById('editTenantModal').classList.remove('hidden');
            }

            function hideEditTenantModal() {
                document.getElementById('editTenantModal').classList.add('hidden');
            }

            // temporary stub so clicking the add button doesn't throw an error
            function showAddTenantModal() {
                alert('ฟังก์ชันเพิ่มผู้เข้าพักยังไม่ถูกสร้าง');
            }

            // attach listeners to edit links (elements already present because script is at end)
            document.querySelectorAll('.edit-button').forEach(el => {
                el.addEventListener('click', function(e) {
                    e.preventDefault();
                    const tenant = {
                        id: this.getAttribute('data-id'),
                        tenant_name: this.getAttribute('data-tenant_name'),
                        phone: this.getAttribute('data-phone'),
                        room_id: this.getAttribute('data-room_id'),
                        room_name: this.getAttribute('data-room_name'),
                        date: this.getAttribute('data-date'),
                        type: this.getAttribute('data-type'),
                    };
                    showEditTenantModal(tenant);
                });
            });
        </script>
    @endsection
