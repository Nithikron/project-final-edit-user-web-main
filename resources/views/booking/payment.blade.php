@extends('layouts.app')

@section('title', 'ชำระเงิน')

@section('content')
<div class="container my-5">
    <div class="row mb-4">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">หน้าแรก</a></li>
                    <li class="breadcrumb-item active">ชำระเงิน</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0"><i class="bi bi-credit-card me-2"></i>ชำระเงิน</h5>
                </div>
                <div class="card-body">
                    @php
                        $stayNights = $calculatedNights ?? $booking->nights;
                        $qty = $roomQuantity ?? 1;
                        $pricePerNight = $nightlyPrice ?? (float) ($booking->room->price ?? 0);
                        $grandTotal = $stayNights * $pricePerNight * $qty;
                    @endphp

                    <div class="alert alert-info">
                        <i class="bi bi-info-circle me-2"></i>
                        กรุณาชำระเงินภายใน 24 ชั่วโมง หลังจากนั้นการจองจะถูกยกเลิกโดยอัตโนมัติ
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <h6>ข้อมูลการโอนเงิน</h6>
                            <div class="bg-light p-3 rounded">
                                <p class="mb-2"><strong>ธนาคาร:</strong> ธนาคารกรุงไทย</p>
                                <p class="mb-2"><strong>ชื่อบัญชี:</strong> หอพักของเรา</p>
                                <p class="mb-2"><strong>เลขที่บัญชี:</strong> 123-4-56789-0</p>
                                <p class="mb-0"><strong>จำนวนเงิน:</strong> 
                                    <span class="text-danger fw-bold">{{ number_format($grandTotal) }} บาท</span>
                                </p>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <h6>QR Code สำหรับชำระเงิน</h6>
                            <div class="text-center bg-light p-3 rounded">
                                <div class="mb-3">
                                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data={{ urlencode('amount=' . $grandTotal . '&name=หอพัก') }}" 
                                         alt="QR Code" class="img-fluid">
                                </div>
                                <small class="text-muted">สแกน QR Code เพื่อชำระเงิน</small>
                            </div>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <form action="{{ route('booking.upload', $booking) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="payment_qr" class="form-label">อัปโหลดหลักฐานการชำระเงิน <span class="text-danger">*</span></label>
                            <input type="file" class="form-control @error('payment_qr') is-invalid @enderror" 
                                   id="payment_qr" name="payment_qr" accept="image/*" required>
                            <div class="form-text">ไฟล์ที่อนุญาต: JPG, JPEG, PNG, GIF (ขนาดสูงสุด 2MB)</div>
                            @error('payment_qr')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            <strong>ข้อควรทราบ:</strong>
                            <ul class="mb-0 mt-2">
                                <li>กรุณาตรวจสอบความถูกต้องของจำนวนเงินก่อนชำระ</li>
                                <li>หลังจากอัปโหลดหลักฐาน ระบบจะตรวจสอบและยืนยันการจองภายใน 1-2 ชั่วโมง</li>
                                <li>หากมีปัญหาใดๆ กรุณาติดต่อเราทันที</li>
                            </ul>
                        </div>
                        
                        <div class="d-grid">
                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="bi bi-upload me-2"></i>อัปโหลดหลักฐานการชำระเงิน
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h6 class="mb-0"><i class="bi bi-receipt me-2"></i>สรุปการจอง</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6>รหัสการจอง: #{{ str_pad($booking->id, 6, '0', STR_PAD_LEFT) }}</h6>
                        <p class="text-muted small mb-1">สถานะ: <span class="badge bg-warning">{{ $booking->status_label }}</span></p>
                    </div>
                    
                    <hr>
                    
                    <div class="mb-3">
                        <h6>ข้อมูลผู้จอง</h6>
                        <p class="mb-1"><strong>ชื่อ:</strong> {{ $booking->customer_name }}</p>
                        <p class="mb-1"><strong>เบอร์โทร:</strong> {{ $booking->customer_phone }}</p>
                        <p class="mb-0"><strong>อีเมล:</strong> {{ $booking->customer_email }}</p>
                    </div>
                    
                    <hr>
                    
                    <div class="mb-3">
                        <h6>ข้อมูลห้องพัก</h6>
                        <p class="mb-1"><strong>ห้อง:</strong> {{ $booking->room->room_number }} - {{ $booking->room->type_label }}</p>
                        <p class="mb-1"><strong>เช็คอิน:</strong> {{ $booking->check_in_date->format('d/m/Y') }}</p>
                        <p class="mb-1"><strong>เช็คเอาท์:</strong> {{ $booking->check_out_date->format('d/m/Y') }}</p>
                        <p class="mb-0"><strong>จำนวนคืน:</strong> {{ $stayNights }} คืน × {{ number_format($pricePerNight) }} บาท/คืน × {{ $qty }} ห้อง</p>
                    </div>
                    
                    <hr>
                    
                    <div class="d-flex justify-content-between fw-bold">
                        <span>รวมทั้งหมด:</span>
                        <span class="text-primary fs-5">{{ number_format($grandTotal) }} บาท</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
