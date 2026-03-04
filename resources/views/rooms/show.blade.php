@extends('layouts.app')

@section('title', 'รายละเอียดห้อง ' . $room->name_room)

@section('content')
@php
    $isRoomAvailable = $room->status === 'available';
@endphp
<div class="container my-5">
    <div class="row mb-4">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">หน้าแรก</a></li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('rooms.type', $room->type) }}">{{ $room->type_label }}</a>
                    </li>
                    <li class="breadcrumb-item active">ห้อง {{ $room->name_room }}</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                @php
                    $sampleImage = match($room->type) {
                        'air_single' => 'air1',
                        'air_double' => 'air2',
                        'fan_single' => 'fan1',
                        'fan_double' => 'fan2',
                        default => null,
                    };

                    $roomImage = $room->image_path ?? $room->image ?? null;
                    $sampleImageUrl = null;

                    if ($sampleImage) {
                        foreach (['png', 'jpg', 'jpeg', 'webp'] as $ext) {
                            $candidate = "images/{$sampleImage}.{$ext}";
                            if (file_exists(public_path($candidate))) {
                                $sampleImageUrl = asset($candidate);
                                break;
                            }
                        }
                    }
                @endphp

                @if($roomImage)
                    <img src="{{ asset('storage/' . $roomImage) }}" class="card-img-top" alt="{{ $room->name_room }}" style="height: 400px; object-fit: cover;">
                @elseif($sampleImageUrl)
                    <img src="{{ $sampleImageUrl }}" class="card-img-top" alt="{{ $room->type_label }}" style="height: 400px; object-fit: cover;">
                @else
                    <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 400px;">
                        <div class="text-center">
                            <i class="bi bi-image display-1 text-muted"></i>
                            <p class="text-muted mt-3">ยังไม่มีรูปภาพห้อง</p>
                        </div>
                    </div>
                @endif
                
                <div class="card-body">
                    <h3 class="card-title mb-3">ห้อง {{ $room->name_room }} - {{ $room->type_label }}</h3>
                    <p class="card-text">{{ $room->description }}</p>
                    
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <h5>สิ่งอำนวยความสะดวก</h5>
                            <ul class="list-unstyled">
                                @if(str_contains($room->type, 'air'))
                                    <li><i class="bi bi-wind text-primary me-2"></i>เครื่องปรับอากาศ</li>
                                @else
                                    <li><i class="bi bi-fan text-success me-2"></i>พัดลม</li>
                                @endif
                                
                                @if(str_contains($room->type, 'single'))
                                    <li><i class="bi bi-bed text-info me-2"></i>เตียงขนาดเดี่ยว</li>
                                @else
                                    <li><i class="bi bi-bed-fill text-info me-2"></i>เตียงขนาดคู่</li>
                                @endif
                                
                                <li><i class="bi bi-droplet text-warning me-2"></i>น้ำอุ่น</li>
                                <li><i class="bi bi-wifi text-success me-2"></i>WiFi ฟรี</li>
                                <li><i class="bi bi-tv text-dark me-2"></i>ทีวี</li>
                                <li><i class="bi bi-shield-check text-primary me-2"></i>ตู้นิรภัย</li>
                            </ul>
                        </div>
                        
                        <div class="col-md-6">
                            <h5>ข้อมูลเพิ่มเติม</h5>
                            <ul class="list-unstyled">
                                <li><strong>หมายเลขห้อง:</strong> {{ $room->name_room }}</li>
                                <li><strong>ประเภท:</strong> {{ $room->type_label }}</li>
                                <li><strong>ราคา:</strong> <span class="text-primary fw-bold">{{ number_format($room->price) }} บาท/คืน</span></li>
                                <li><strong>สถานะ:</strong> 
                                    @if($isRoomAvailable)
                                        <span class="badge bg-success">ว่าง</span>
                                    @else
                                        <span class="badge bg-danger">ไม่ว่าง</span>
                                    @endif
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">จองห้องนี้</h5>
                    <p class="text-primary fw-bold fs-4 mb-3">{{ number_format($room->price) }} บาท/คืน</p>
                    
                    @if($isRoomAvailable)
                        <a href="{{ route('booking.create', $room) }}" class="btn btn-primary-custom w-100 btn-lg">
                            <i class="bi bi-calendar-check me-2"></i>จองห้องนี้
                        </a>
                    @else
                        <button class="btn btn-secondary w-100 btn-lg" disabled>
                            <i class="bi bi-x-circle me-2"></i>ห้องไม่ว่าง
                        </button>
                    @endif
                    
                    <hr>
                    
                    <div class="text-center">
                        <small class="text-muted">
                            <i class="bi bi-info-circle me-1"></i>
                            การจองจะเสร็จสมบูรณ์หลังการชำระเงิน
                        </small>
                    </div>
                </div>
            </div>
            
            <div class="card mt-3">
                <div class="card-body">
                    <h6 class="card-title">ติดต่อเรา</h6>
                    <p class="mb-2"><i class="bi bi-telephone me-2"></i>02-123-4567</p>
                    <p class="mb-2"><i class="bi bi-envelope me-2"></i>info@hotel.com</p>
                    <p class="mb-0"><i class="bi bi-geo-alt me-2"></i>กรุงเทพมหานคร</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
