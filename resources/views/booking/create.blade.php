@extends('layouts.app')

@section('title', 'จองห้อง ' . $room->name_room)

@section('content')
<div class="container my-5">
    <div class="row mb-4">
        <div class="col-12">
            <!-- Breadcrumb navigation - แสดงเส้นทางการนำทาง -->
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">หน้าแรก</a></li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('rooms.type', $room->type) }}">{{ $room->type_label }}</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('rooms.show', $room) }}">ห้อง {{ $room->name_room }}</a>
                    </li>
                    <li class="breadcrumb-item active">จองห้อง</li>
                </ol>
            </nav>
            <h2 class="mb-4"><i class="bi bi-person-plus me-2"></i>จองห้อง {{ $room->name_room }}</h2>
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
                    <!-- ฟอร์มสำหรับกรอกข้อมูลการจอง -->
                    <form action="{{ route('booking.store', [], false) }}" method="POST">
                        @csrf
                        
                        <!-- ซ่อน ID ของห้องที่กำลังจอง -->
                        <input type="hidden" name="room_id" value="{{ $room->id }}">
                        
                        <!-- ข้อมูลส่วนตัวลูกค้า -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="customer_name" class="form-label">ชื่อ-นามสกุล <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('customer_name') is-invalid @enderror" 
                                       id="customer_name" name="customer_name" value="{{ old('customer_name', $currentUser?->name ?? $currentUser?->username) }}" required>
                                @error('customer_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="customer_phone" class="form-label">เบอร์โทรศัพท์ <span class="text-danger">*</span></label>
                                <input type="tel" class="form-control @error('customer_phone') is-invalid @enderror" 
                                       id="customer_phone" name="customer_phone" value="{{ old('customer_phone', $currentUser?->phone) }}" required>
                                @error('customer_phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <!-- อีเมลลูกค้า -->
                        <div class="mb-3">
                            <label for="customer_email" class="form-label">อีเมล <span class="text-danger">*</span></label>
                            <input type="email" class="form-control @error('customer_email') is-invalid @enderror" 
                                   id="customer_email" name="customer_email" value="{{ old('customer_email', $currentUser?->email) }}" required>
                            @error('customer_email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <!-- วันที่เช็คอินและเช็คเอาท์ -->
                        <div class="row position-relative">
                            <div class="col-md-6 mb-3">
                                <label for="check_in_date" class="form-label">วันที่เช็คอิน <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('check_in_date') is-invalid @enderror" 
                                       id="check_in_date" name="check_in_date" value="{{ old('check_in_date', $checkIn ?? '') }}" 
                                       min="{{ date('Y-m-d') }}" required>
                                @error('check_in_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="check_out_date" class="form-label">วันที่เช็คเอาท์ <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('check_out_date') is-invalid @enderror" 
                                       id="check_out_date" name="check_out_date" value="{{ old('check_out_date', $checkOut ?? '') }}" 
                                       min="{{ date('Y-m-d') }}" required>
                                @error('check_out_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
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
                        <h6>ห้อง {{ $room->name_room }} - {{ $room->type_label }}</h6>
                        <p class="text-muted small mb-1">{{ $room->description }}</p>
                    </div>
                    
                    <hr>
                    
                    <!-- ราคาห้อง -->
                    <div class="d-flex justify-content-between mb-2">
                        <span>ราคาต่อคืน:</span>
                        <span>{{ number_format($room->price) }} บาท</span>
                    </div>
                    
                    <!-- จำนวนคืน (คำนวณจาก JavaScript) -->
                    <div class="d-flex justify-content-between mb-2">
                        <span>จำนวนคืน:</span>
                        <span id="nights">-</span>
                    </div>
                    
                    <hr>
                    
                    <!-- ราคารวม -->
                    <div class="d-flex justify-content-between fw-bold">
                        <span>รวมทั้งหมด:</span>
                        <span class="text-primary" id="total_price">-</span>
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
document.addEventListener('DOMContentLoaded', function () {
    const checkInInput = document.getElementById('check_in_date');
    const checkOutInput = document.getElementById('check_out_date');
    const nightsSpan = document.getElementById('nights');
    const totalPriceSpan = document.getElementById('total_price');
    const roomPrice = {{ $room->price }};

    function number_format(num) {
        return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
    }

    function calculatePrice() {
        const checkIn = new Date(checkInInput.value);
        const checkOut = new Date(checkOutInput.value);

        if (checkInInput.value && checkOutInput.value && checkOut > checkIn) {
            const nights = Math.ceil((checkOut - checkIn) / (1000 * 60 * 60 * 24));
            const totalPrice = nights * roomPrice;
            nightsSpan.textContent = nights + ' คืน';
            totalPriceSpan.textContent = number_format(totalPrice) + ' บาท';
            return;
        }

        nightsSpan.textContent = '-';
        totalPriceSpan.textContent = '-';
    }

    checkInInput.addEventListener('change', function () {
        if (!this.value) {
            return;
        }

        const minCheckOut = new Date(this.value);
        minCheckOut.setDate(minCheckOut.getDate() + 1);
        const minCheckOutStr = minCheckOut.toISOString().split('T')[0];
        checkOutInput.min = minCheckOutStr;

        if (checkOutInput.value && checkOutInput.value <= this.value) {
            checkOutInput.value = '';
        }

        calculatePrice();
    });

    checkOutInput.addEventListener('change', calculatePrice);
    calculatePrice();
});
</script>
@endsection
