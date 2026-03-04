@extends('layouts.app')



@section('title', 'ปฏิทินห้องว่าง')



@section('content')

<div class="container my-5">

    <div class="row mb-4">

        <div class="col-12">

            <nav aria-label="breadcrumb">

                <ol class="breadcrumb">

                    <li class="breadcrumb-item"><a href="{{ route('home') }}">หน้าแรก</a></li>

                    <li class="breadcrumb-item active">ปฏิทินห้องว่าง</li>

                </ol>

            </nav>

            <h2 class="mb-4"><i class="bi bi-calendar3 me-2"></i>ปฏิทินห้องว่าง</h2>

        </div>

    </div>



    <!-- ตัวกรอง -->

    <div class="card mb-4">

        <div class="card-body">

            <form method="GET" action="{{ route('calendar.index') }}" class="row g-3">

                <div class="col-md-4">

                    <label for="monthYearPicker" class="form-label">เลือกเดือนและปี</label>

                    <div class="input-group">

                        <input type="text" id="monthYearPicker" name="monthYear" class="form-control" 

                               value="{{ $calendar['monthName'] }} {{ $calendar['year'] + 543 }}" readonly>

                        <button type="button" class="btn btn-outline-secondary" id="clearDate">

                            <i class="bi bi-x"></i>

                        </button>

                        <button type="button" class="btn btn-outline-secondary" id="todayDate">

                            <i class="bi bi-calendar-event"></i>

                        </button>

                    </div>

                </div>

                <div class="col-md-4">

                    <label for="type" class="form-label">ประเภทห้อง</label>

                    <select name="type" id="type" class="form-select">

                        <option value="all" {{ $roomType === 'all' ? 'selected' : '' }}>ทุกประเภท</option>

                        <option value="air_single" {{ $roomType === 'air_single' ? 'selected' : '' }}>แอร์เตียงเดี่ยว</option>

                        <option value="air_double" {{ $roomType === 'air_double' ? 'selected' : '' }}>แอร์เตียงคู่</option>

                        <option value="fan_single" {{ $roomType === 'fan_single' ? 'selected' : '' }}>พัดลมเตียงเดี่ยว</option>

                        <option value="fan_double" {{ $roomType === 'fan_double' ? 'selected' : '' }}>พัดลมเตียงคู่</option>

                    </select>

                </div>

                <div class="col-md-4 d-flex align-items-end">

                    <button type="submit" class="btn btn-primary">

                        <i class="bi bi-search me-1"></i>ค้นหา

                    </button>

                </div>

            </form>

        </div>

    </div>



    <!-- ปฏิทินตารางปกติ -->

    <div class="card">

        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">

            <h5 class="mb-0">

                <i class="bi bi-calendar-month me-2"></i>

                {{ $calendar['monthName'] }} {{ $calendar['year'] + 543 }}

            </h5>

            <div class="btn-group" role="group">

                <button type="button" class="btn btn-outline-light btn-sm" id="prevMonth">

                    <i class="bi bi-chevron-left"></i>

                </button>

                <button type="button" class="btn btn-outline-light btn-sm" id="todayBtn">วันนี้</button>

                <button type="button" class="btn btn-outline-light btn-sm" id="nextMonth">

                    <i class="bi bi-chevron-right"></i>

                </button>

            </div>

        </div>

        <div class="card-body p-0">

            <table class="table table-bordered table-calendar mb-0">

                <thead class="table-light">

                    <tr>

                        <th class="text-center calendar-header-sun">อา.</th>

                        <th class="text-center calendar-header">จ.</th>

                        <th class="text-center calendar-header">อ.</th>

                        <th class="text-center calendar-header">พ.</th>

                        <th class="text-center calendar-header">พฤ.</th>

                        <th class="text-center calendar-header">ศ.</th>

                        <th class="text-center calendar-header-sat">ส.</th>

                    </tr>

                </thead>

                <tbody>

                    @foreach ($calendar['weeks'] as $week)

                        <tr>

                            @foreach ($week as $day)

                                @if ($day['empty'])

                                    <td class="calendar-cell empty"></td>

                                @else

                                    @php

                                        $isAvailable = $day['available'];

                                        $isToday = $day['today'];

                                        $isWeekend = $day['weekend'];

                                    @endphp

                                    <td class="calendar-cell {{ $isAvailable ? 'available' : 'has-booking' }} {{ $isToday ? 'today' : '' }} {{ $isWeekend ? 'weekend' : '' }}">

                                        <div class="calendar-day-content">

                                            <div class="calendar-day-number">{{ $day['day'] }}</div>

                                            @if (!$isAvailable)

                                                <div class="booking-status">

                                                    <i class="bi bi-x-circle-fill"></i>

                                                    <small>จองแล้ว</small>

                                                </div>

                                            @endif

                                        </div>

                                    </td>

                                @endif

                            @endforeach

                        </tr>

                    @endforeach

                </tbody>

            </table>

        </div>

    </div>



    <!-- สถิติ -->

    <div class="row mt-4">

        <div class="col-md-6">

            <div class="card">

                <div class="card-body">

                    <h6 class="card-title">สถานะห้องเดือนนี้</h6>

                    <div class="d-flex justify-content-between">

                        <span>วันที่ว่าง:</span>

                        <span class="badge bg-success" id="available-count">-</span>

                    </div>

                    <div class="d-flex justify-content-between">

                        <span>วันที่จองแล้ว:</span>

                        <span class="badge bg-danger" id="booked-count">-</span>

                    </div>

                </div>

            </div>

        </div>

        <div class="col-md-6">

            <div class="card">

                <div class="card-body">

                    <h6 class="card-title">คำอธิบาย</h6>

                    <div class="d-flex align-items-center mb-2">

                        <div class="calendar-cell-preview available me-2"></div>

                        <small>วันว่าง (สามารถจองได้)</small>

                    </div>

                    <div class="d-flex align-items-center">

                        <div class="calendar-cell-preview has-booking me-2"></div>

                        <small>วันที่จองแล้ว</small>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection



@section('styles')

<style>

.table-calendar {

    margin: 0;

}



.table-calendar th {

    border: 1px solid #dee2e6;

    padding: 12px 8px;

    font-weight: bold;

    font-size: 0.9rem;

    background-color: #f8f9fa;

}



.calendar-header-sun,

.calendar-header-sat {

    background-color: #e9ecef !important;

    color: #6c757d;

}



.calendar-cell {

    width: 14.28%;

    height: 80px;

    vertical-align: top;

    border: 1px solid #dee2e6;

    padding: 4px;

    cursor: pointer;

    transition: all 0.3s ease;

    position: relative;

}



.calendar-cell.empty {

    background-color: #f8f9fa;

    cursor: default;

}



.calendar-cell.available {

    background-color: white;

}



.calendar-cell.available:hover {

    background-color: #e8f5e8;

    transform: scale(1.02);

}



.calendar-cell.has-booking {

    background-color: #f8d7da;

    color: #721c24;

}



.calendar-cell.has-booking:hover {

    background-color: #f5c6cb;

}



.calendar-cell.today {

    background-color: #e3f2fd !important;

    box-shadow: inset 0 0 0 2px #2196f3;

    z-index: 1;

}



.calendar-day-content {

    height: 100%;

    display: flex;

    flex-direction: column;

}



.calendar-day-number {

    font-weight: bold;

    font-size: 1rem;

    margin-bottom: 4px;

}



.booking-status {

    display: flex;

    flex-direction: column;

    align-items: center;

    justify-content: center;

    flex-grow: 1;

    font-size: 0.7rem;

    text-align: center;

}



.booking-status i {

    color: #dc3545;

    font-size: 1rem;

    margin-bottom: 2px;

}



.calendar-cell-preview {

    width: 40px;

    height: 30px;

    border: 1px solid #dee2e6;

    border-radius: 4px;

    display: inline-block;

    vertical-align: middle;

}



.calendar-cell-preview.available {

    background-color: white;

}



.calendar-cell-preview.has-booking {

    background-color: #f8d7da;

}



@media (max-width: 768px) {

    .calendar-cell {

        height: 60px;

        padding: 2px;

    }

    

    .calendar-day-number {

        font-size: 0.9rem;

    }

    

    .booking-status {

        font-size: 0.6rem;

    }

    

    .booking-status i {

        font-size: 0.8rem;

    }

    

    .table-calendar th {

        padding: 8px 4px;

        font-size: 0.8rem;

    }

}



@media (max-width: 576px) {

    .calendar-cell {

        height: 50px;

        padding: 1px;

    }

    

    .calendar-day-number {

        font-size: 0.8rem;

    }

    

    .booking-status {

        font-size: 0.5rem;

    }

    

    .booking-status i {

        font-size: 0.6rem;

    }

    

    .table-calendar th {

        padding: 6px 2px;

        font-size: 0.7rem;

    }

}

</style>

@endsection



@section('scripts')

<script>

document.addEventListener('DOMContentLoaded', function() {

    // สร้าง Flatpickr Date Picker แบบ Month/Year เท่านั้น

    const picker = flatpickr("#monthYearPicker", {

        locale: "th",

        plugins: [

            new monthSelectPlugin({

                shorthand: true,

                dateFormat: "F Y",

                altFormat: "F Y",

                theme: "material_blue"

            })

        ],

        defaultDate: new Date({{ $year }}, {{ $month - 1 }}, 1),

        onChange: function(selectedDates, dateStr, instance) {

            if (selectedDates.length > 0) {

                const date = selectedDates[0];

                const month = date.getMonth() + 1;

                const year = date.getFullYear();

                

                // สร้าง URL ใหม่และ redirect

                const url = new URL(window.location);

                url.searchParams.set('month', month);

                url.searchParams.set('year', year);

                url.searchParams.delete('monthYear');

                

                // เพิ่มค่า type กลับไป

                const type = document.getElementById('type').value;

                if (type && type !== 'all') {

                    url.searchParams.set('type', type);

                }

                

                window.location.href = url.toString();

            }

        }

    });

    

    // ปุ่ม Clear

    document.getElementById('clearDate').addEventListener('click', function() {

        picker.clear();

    });

    

    // ปุ่ม Today

    document.getElementById('todayDate').addEventListener('click', function() {

        const today = new Date();

        picker.setDate(today);

        

        // Redirect ไปเดือนปัจจุบัน

        const url = new URL(window.location);

        url.searchParams.set('month', today.getMonth() + 1);

        url.searchParams.set('year', today.getFullYear());

        url.searchParams.delete('monthYear');

        

        // เพิ่มค่า type กลับไป

        const type = document.getElementById('type').value;

        if (type && type !== 'all') {

            url.searchParams.set('type', type);

        }

        

        window.location.href = url.toString();

    });

    

    // ปุ่มเปลี่ยนเดือน

    document.getElementById('prevMonth').addEventListener('click', function() {

        changeMonth(-1);

    });

    

    document.getElementById('nextMonth').addEventListener('click', function() {

        changeMonth(1);

    });

    

    document.getElementById('todayBtn').addEventListener('click', function() {

        const today = new Date();

        const url = new URL(window.location);

        url.searchParams.set('month', today.getMonth() + 1);

        url.searchParams.set('year', today.getFullYear());

        

        // เพิ่มค่า type กลับไป

        const type = document.getElementById('type').value;

        if (type && type !== 'all') {

            url.searchParams.set('type', type);

        }

        

        window.location.href = url.toString();

    });

    

    function changeMonth(direction) {

        const currentMonth = {{ $month }};

        const currentYear = {{ $year }};

        

        let newMonth = currentMonth + direction;

        let newYear = currentYear;

        

        if (newMonth < 1) {

            newMonth = 12;

            newYear--;

        } else if (newMonth > 12) {

            newMonth = 1;

            newYear++;

        }

        

        const url = new URL(window.location);

        url.searchParams.set('month', newMonth);

        url.searchParams.set('year', newYear);

        window.location.href = url.toString();

    }

    

    // ทำให้วันนี้โดดเด่น

    const today = new Date().toISOString().split('T')[0];

    const todayElement = document.querySelector(`[data-date="${today}"]`);

    if (todayElement) {

        todayElement.classList.add('today');

    }

    

    // คลิกที่วันที่ว่างเพื่อไปหน้าจอง

    document.querySelectorAll('.calendar-cell.available').forEach(element => {

        element.addEventListener('click', function() {

            const date = this.dataset.date;

            window.location.href = `{{ route('home') }}?date=${date}`;

        });

    });

    

    // คลิกที่วันที่จองแล้วเพื่อแสดงข้อมูล

    document.querySelectorAll('.calendar-cell.has-booking').forEach(element => {

        element.addEventListener('click', function() {

            const date = this.dataset.date;

            alert(`วันที่ ${date} มีการจองแล้ว`);

        });

    });

    

    // อัปเดตสถิติเดือนนี้

    updateMonthStats();

    

    // Auto-refresh ทุก 30 วินาทีสำหรับ real-time

    setInterval(updateMonthStats, 30000);

});



function updateMonthStats() {

    const available = document.querySelectorAll('.calendar-cell.available').length;

    const booked = document.querySelectorAll('.calendar-cell.has-booking').length;

    

    document.getElementById('available-count').textContent = available;

    document.getElementById('booked-count').textContent = booked;

}

</script>

@endsection

