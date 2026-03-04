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
                <h3 class="mb-0"><i class="bi bi-clock-history me-2"></i>ผลการค้นหาประวัติการจอง</h3>
                <a href="{{ route('home') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left me-1"></i>กลับหน้าแรก
                </a>
            </div>

            <div class="card mb-4">
                <div class="card-body">
                    <form action="{{ route('booking.history') }}" method="GET" class="row g-2 align-items-center">
                        <div class="col-md-9">
                            <label for="keyword" class="form-label mb-1">ค้นหาด้วย username หรือชื่อ-นามสกุล</label>
                            <input
                                type="text"
                                class="form-control"
                                id="keyword"
                                name="keyword"
                                value="{{ $keyword }}"
                                required
                            >
                        </div>
                        <div class="col-md-3 pt-md-4">
                            <button type="submit" class="btn btn-primary w-100">ค้นหา</button>
                        </div>
                    </form>
                </div>
            </div>

            @if(!$hasKeyword)
                <div class="alert alert-info">
                    กรุณากรอก <strong>username</strong> หรือ <strong>ชื่อ-นามสกุล</strong> เพื่อค้นหาประวัติการจอง
                </div>
            @elseif($bookings->isEmpty())
                <div class="alert alert-warning">
                    ไม่พบประวัติการจองสำหรับคำค้นหา <strong>{{ $keyword }}</strong>
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
                                                <input type="hidden" name="keyword" value="{{ $keyword }}">
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
