@extends('layouts.app')

@section('title', 'จองห้อง ' . $room->room_number)

@section('content')
<div class="container my-5">
    <div class="row mb-4">
        <div class="col-12">
            <!-- Breadcrumb navigation -->
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">หน้าแรก</a></li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('rooms.type', $room->type) }}">{{ $room->type_label }}</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('rooms.show', $room) }}">ห้อง {{ $room->room_number }}</a>
                    </li>
                    <li class="breadcrumb-item active">จองห้อง</li>
                </ol>
            </nav>
            <h2 class="mb-4"><i class="bi bi-person-plus me-2"></i>จองห้อง {{ $room->room_number }}</h2>
        </div>
    </div>

    <div class="row">
        <!-- ฟอร์มการจองห้อง -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-person-plus me-2"></i>ข้อมูลการจอง</h5>
                </div>
                <div class="card-body">
                    <!-- ฟอร์มแบบ POST ธรรมดา -->
                    <form action="{{ route('booking.store') }}" method="POST">
                        @csrf
                        
                        <!-- ซ่อน ID ของห้องที่กำลังจอง -->
                        <input type="hidden" name="room_id" value="{{ $room->id }}">
                        
                        <!-- ข้อมูลส่วนตัวลูกค้า -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="customer_name" class="form-label">ชื่อ-นามสกุล <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('customer_name') is-invalid @enderror" 
                                       id="customer_name" name="customer_name" value="{{ old('customer_name') }}" required>
                                @error('customer_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="customer_phone" class="form-label">เบอร์โทรศัพท์ <span class="text-danger">*</span></label>
                                <input type="tel" class="form-control @error('customer_phone') is-invalid @enderror" 
                                       id="customer_phone" name="customer_phone" value="{{ old('customer_phone') }}" required>
                                @error('customer_phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <!-- อีเมลลูกค้า -->
                        <div class="mb-3">
                            <label for="customer_email" class="form-label">อีเมล <span class="text-danger">*</span></label>
                            <input type="email" class="form-control @error('customer_email') is-invalid @enderror" 
                                   id="customer_email" name="customer_email" value="{{ old('customer_email') }}" required>
                            @error('customer_email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <!-- วันที่เช็คอินและเช็คเอาท์ -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="check_in_date" class="form-label">วันที่เช็คอิน <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('check_in_date') is-invalid @enderror" 
                                       id="check_in_date" name="check_in_date" value="{{ old('check_in_date') }}" 
                                       min="{{ date('Y-m-d') }}" required>
                                @error('check_in_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="check_out_date" class="form-label">วันที่เช็คเอาท์ <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('check_out_date') is-invalid @enderror" 
                                       id="check_out_date" name="check_out_date" value="{{ old('check_out_date') }}" 
                                       min="{{ date('Y-m-d', strtotime('+1 day')) }}" required>
                                @error('check_out_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <!-- ปฏิทินแสดงวันว่าง (Server-side) -->
                        <div class="row">
                            <div class="col-12">
                                <div class="card mt-3">
                                    <div class="card-header bg-info text-white">
                                        <h6 class="mb-0">
                                            <i class="bi bi-calendar-check me-2"></i>ปฏิทินห้องว่าง - ห้อง {{ $room->room_number }} ({{ $room->type_label }})
                                        </h6>
                                    </div>
                                    <div class="card-body p-0">
                                        <!-- ปุ่มสลับเดือน -->
                                        <div class="calendar-navigation p-3 border-bottom">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <a href="?month={{ $prevMonth }}&year={{ $prevYear }}" class="btn btn-sm btn-outline-primary">
                                                    <i class="bi bi-chevron-left"></i>
                                                </a>
                                                <h6 class="mb-0">{{ $monthName }} {{ $year + 543 }}</h6>
                                                <a href="?month={{ $nextMonth }}&year={{ $nextYear }}" class="btn btn-sm btn-outline-primary">
                                                    <i class="bi bi-chevron-right"></i>
                                                </a>
                                            </div>
                                        </div>
                                        
                                        <!-- ตารางปฏิทิน -->
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
                                        
                                        <!-- คำอธิบายสีปฏิทิน -->
                                        <div class="p-3 border-top">
                                            <div class="row text-center">
                                                <div class="col-md-4">
                                                    <div class="d-flex align-items-center justify-content-center mb-2">
                                                        <div class="calendar-cell-preview available me-2"></div>
                                                        <small>วันว่าง (สามารถจองได้)</small>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="d-flex align-items-center justify-content-center mb-2">
                                                        <div class="calendar-cell-preview has-booking me-2"></div>
                                                        <small>วันที่จองแล้ว</small>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="d-flex align-items-center justify-content-center mb-2">
                                                        <div class="calendar-cell-preview today me-2"></div>
                                                        <small>วันนี้</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- ปุ่มส่งฟอร์มการจอง -->
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary-custom btn-lg">
                                <i class="bi bi-calendar-check me-2"></i>ดำเนินการจอง
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- ส่วนสรุปข้อมูลการจอง -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0"><i class="bi bi-info-circle me-2"></i>สรุปการจอง</h6>
                </div>
                <div class="card-body">
                    <!-- ข้อมูลห้องที่เลือก -->
                    <div class="mb-3">
                        <h6>ห้อง {{ $room->room_number }} - {{ $room->type_label }}</h6>
                        <p class="text-muted small mb-1">{{ $room->description }}</p>
                    </div>
                    
                    <hr>
                    
                    <!-- ราคาห้อง -->
                    <div class="d-flex justify-content-between mb-2">
                        <span>ราคาต่อคืน:</span>
                        <span>{{ number_format($room->price_per_night) }} บาท</span>
                    </div>
                    
                    <!-- จำนวนคืน -->
                    <div class="d-flex justify-content-between mb-2">
                        <span>จำนวนคืน:</span>
                        <span>คำนวณอัตโนมัติ</span>
                    </div>
                    
                    <hr>
                    
                    <!-- ราคารวม -->
                    <div class="d-flex justify-content-between fw-bold">
                        <span>รวมทั้งหมด:</span>
                        <span class="text-primary">คำนวณอัตโนมัติ</span>
                    </div>
                </div>
            </div>
            
            <!-- ข้อความแนะนำ -->
            <div class="alert alert-info mt-3">
                <i class="bi bi-info-circle me-2"></i>
                <small>หลังกดปุ่ม "ดำเนินการจอง" ระบบจะนำทางไปยังหน้าชำระเงิน</small>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // ฟังก์ชันคำนวณราคาแบบง่าย (ไม่ใช้ API)
    function calculatePrice() {
        const checkInInput = document.getElementById('check_in_date');
        const checkOutInput = document.getElementById('check_out_date');
        
        if (checkInInput.value && checkOutInput.value) {
            const checkIn = new Date(checkInInput.value);
            const checkOut = new Date(checkOutInput.value);
            
            if (checkOut > checkIn) {
                const nights = Math.ceil((checkOut - checkIn) / (1000 * 60 * 60 * 24));
                const totalPrice = nights * {{ $room->price_per_night }};
                
                // แสดงผล (ถ้ามี element)
                const nightsElement = document.querySelector('.nights-display');
                const priceElement = document.querySelector('.price-display');
                
                if (nightsElement) nightsElement.textContent = nights + ' คืน';
                if (priceElement) priceElement.textContent = number_format(totalPrice) + ' บาท';
            }
        }
    }
    
    // Event listeners สำหรับวันที่
    const checkInInput = document.getElementById('check_in_date');
    const checkOutInput = document.getElementById('check_out_date');
    
    if (checkInInput) {
        checkInInput.addEventListener('change', function() {
            const checkIn = new Date(this.value);
            const minCheckOut = new Date(checkIn);
            minCheckOut.setDate(minCheckOut.getDate() + 1);
            
            checkOutInput.min = minCheckOut.toISOString().split('T')[0];
            
            if (new Date(checkOutInput.value) <= checkIn) {
                checkOutInput.value = minCheckOut.toISOString().split('T')[0];
            }
            
            calculatePrice();
        });
    }
    
    if (checkOutInput) {
        checkOutInput.addEventListener('change', calculatePrice);
    }
});

// ฟังก์ชันจัดรูปแบบตัวเลข
function number_format(num) {
    return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}
</script>

<style>
/* Calendar Styles */
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

.calendar-cell.weekend {
    background-color: #f8f9fa;
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

.calendar-cell-preview.today {
    background-color: #e3f2fd;
    border: 2px solid #2196f3;
}

.calendar-navigation {
    background-color: #f8f9fa;
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
