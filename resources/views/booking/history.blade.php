@extends('layouts.app')

@section('title', 'ประวัติการจอง')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="mb-0"><i class="bi bi-clock-history me-2"></i>ประวัติการจองของ {{ $currentUser->name }}</h3>
                <a href="{{ route('home') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left me-1"></i>กลับหน้าแรก
                </a>
            </div>

            @if($bookings->isEmpty())
                <div class="alert alert-info text-center">
                    <i class="bi bi-info-circle display-4 text-info mb-3"></i>
                    <h4>ยังไม่เคยพัก</h4>
                    <p class="mb-0">คุณยังไม่เคยทำการจองหรือเข้าพักที่โรงแรมของเรา</p>
                    <a href="{{ route('home') }}" class="btn btn-primary mt-3">
                        <i class="bi bi-house-door me-1"></i>เลือกห้องพัก
                    </a>
                </div>
            @else
                <div class="alert alert-success">
                    พบประวัติการจองทั้งหมด <strong>{{ $bookings->count() }}</strong> รายการ
                </div>

                <div class="card">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover mb-0 align-middle">
                            <thead class="table-dark">
                                <tr>
                                    <th>เลขที่จอง</th>
                                    <th>ผู้จอง</th>
                                    <th>ห้อง</th>
                                    <th>เข้าพัก</th>
                                    <th>ยอดรวม</th>
                                    <th>สถานะ</th>
                                    <th>ใบเสร็จ</th>
                                    <th>จัดการ</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($bookings as $booking)
                                    <tr>
                                        <td>#{{ str_pad($booking->id, 6, '0', STR_PAD_LEFT) }}</td>
                                        <td>
                                            <div><strong>{{ $booking->customer_name }}</strong></div>
                                            <small class="text-muted">{{ $booking->customer_email }}</small>
                                        </td>
                                        <td>
                                            {{ $booking->room->name_room ?? $booking->room->room_number ?? '-' }}
                                            <div class="small text-muted">{{ $booking->room->type_label ?? '-' }}</div>
                                        </td>
                                        <td>
                                            {{ $booking->check_in_date->format('d/m/Y') }}
                                            <div class="small text-muted">ถึง {{ $booking->check_out_date->format('d/m/Y') }}</div>
                                        </td>
                                        <td>{{ number_format($booking->total_price) }} บาท</td>
                                        <td><span class="badge bg-primary">{{ $booking->status_label }}</span></td>
                                        <td>
                                            <a href="{{ route('booking.receipt', $booking) }}" class="btn btn-sm btn-outline-success">
                                                ดูใบเสร็จ
                                            </a>
                                        </td>
                                        <td>
                                            <form action="{{ route('booking.destroy-from-history', $booking) }}" method="POST" onsubmit="return confirm('ยืนยันการยกเลิกการจองนี้?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger">ยกเลิกการจอง</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
