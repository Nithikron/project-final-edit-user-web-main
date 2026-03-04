@extends('layouts.app')

@section('title', 'ใบเสร็จการจอง')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header bg-success text-white text-center">
                    <h5 class="mb-0"><i class="bi bi-check-circle me-2"></i>การจองสำเร็จแล้ว</h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <i class="bi bi-check-circle-fill text-success display-1"></i>
                        <h4 class="mt-3">ขอบคุณสำหรับการจองห้องพัก</h4>
                        <p class="text-muted">การจองของคุณได้รับการยืนยันแล้ว กรุณาแสดงใบเสร็จนี้เมื่อเช็คอิน</p>
                    </div>
                    
                    <div class="border rounded p-4 bg-light">
                        <div class="text-center mb-4">
                            <h5>ใบเสร็จการจองห้องพัก</h5>
                            <p class="text-muted">เลขที่ใบเสร็จ: #{{ str_pad($booking->id, 6, '0', STR_PAD_LEFT) }}</p>
                            <p class="text-muted">วันที่ออกใบเสร็จ: {{ $booking->payment_confirmed_at?->format('d/m/Y H:i') ?? $booking->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                        
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <h6>ข้อมูลผู้จอง</h6>
                                <table class="table table-sm table-borderless">
                                    <tr>
                                        <td><strong>ชื่อ-นามสกุล:</strong></td>
                                        <td>{{ $booking->customer_name }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>เบอร์โทรศัพท์:</strong></td>
                                        <td>{{ $booking->customer_phone }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>อีเมล:</strong></td>
                                        <td>{{ $booking->customer_email }}</td>
                                    </tr>
                                </table>
                            </div>
                            
                            <div class="col-md-6">
                                <h6>ข้อมูลการจอง</h6>
                                <table class="table table-sm table-borderless">
                                    <tr>
                                        <td><strong>ห้องพัก:</strong></td>
                                        <td>{{ $booking->room->name_room ?? $booking->room->room_number ?? '-' }} - {{ $booking->room->type_label }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>เช็คอิน:</strong></td>
                                        <td>{{ $booking->check_in_date->format('d/m/Y') }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>เช็คเอาท์:</strong></td>
                                        <td>{{ $booking->check_out_date->format('d/m/Y') }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>จำนวนคืน:</strong></td>
                                        <td>{{ $booking->nights }} คืน</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <h6>รายละเอียดห้องพัก</h6>
                            <p class="mb-1"><strong>ประเภทห้อง:</strong> {{ $booking->room->type_label }}</p>
                            <p class="mb-1"><strong>หมายเลขห้อง:</strong> {{ $booking->room->name_room ?? $booking->room->room_number ?? '-' }}</p>
                            <p class="mb-1"><strong>รายละเอียด:</strong> {{ $booking->room->description ?? 'ห้องพักพร้อมสิ่งอำนวยความสะดวกพื้นฐานครบถ้วน' }}</p>
                            <p class="mb-0"><strong>สิ่งอำนวยความสะดวก:</strong> 
                                @if(str_contains($booking->room->type, 'air'))
                                    เครื่องปรับอากาศ
                                @else
                                    พัดลม
                                @endif
                                @if(str_contains($booking->room->type, 'single'))
                                    , เตียงขนาดเดี่ยว
                                @else
                                    , เตียงขนาดคู่
                                @endif
                                , น้ำอุ่น, WiFi ฟรี, ทีวี, ตู้นิรภัย
                            </p>
                        </div>
                        
                        <div class="mb-4">
                            @if($booking->payment_qr)
                                <h6>หลักฐานการชำระเงิน</h6>
                                <div class="text-center">
                                    <img src="{{ asset('storage/' . $booking->payment_qr) }}" 
                                         alt="หลักฐานการชำระเงิน" 
                                         class="img-fluid border rounded" 
                                         style="max-width: 300px;">
                                </div>
                            @endif
                        </div>
                        
                        <div class="border-top pt-3">
                            <div class="row">
                                <div class="col-md-6">
                                    <p class="mb-0"><strong>วันที่ชำระเงิน:</strong> {{ $booking->payment_confirmed_at?->format('d/m/Y H:i') ?? $booking->created_at->format('d/m/Y H:i') }}</p>
                                    <p class="mb-0"><strong>สถานะ:</strong> 
                                        <span class="badge bg-success">{{ $booking->status_label }}</span>
                                    </p>
                                </div>
                                <div class="col-md-6 text-end">
                                    <h5>รวมทั้งหมด: <span class="text-primary">{{ number_format($booking->total_price) }} บาท</span></h5>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="alert alert-info mt-4">
                        <h6><i class="bi bi-info-circle me-2"></i>ข้อมูลสำคัญ</h6>
                        <ul class="mb-0">
                            <li>กรุณามาถึงหอพักก่อนเวลาเช็คอินอย่างน้อย 30 นาที</li>
                            <li>เวลาเช็คอิน: 14:00 น. เป็นต้นไป</li>
                            <li>เวลาเช็คเอาท์: 12:00 น.</li>
                            <li>กรุณาแสดงใบเสร็จนี้และบัตรประชาชนเมื่อเช็คอิน</li>
                            <li>หากต้องการยกเลิกการจอง กรุณาแจ้งล่วงหน้าอย่างน้อย 24 ชั่วโมง</li>
                        </ul>
                    </div>
                    
                    <div class="text-center mt-4">
                        <button onclick="window.print()" class="btn btn-outline-primary me-2">
                            <i class="bi bi-printer me-2"></i>พิมพ์ใบเสร็จ
                        </button>
                        <a href="{{ route('home') }}" class="btn btn-primary">
                            <i class="bi bi-house me-2"></i>กลับหน้าแรก
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
// Auto-print functionality (optional)
window.addEventListener('load', function() {
    // Uncomment the line below if you want to auto-print the receipt
    // window.print();
});
</script>
@endsection
