@extends('layouts.app')

@section('title', 'ห้อง' . $rooms->first()?->type_label ?? '')

@section('content')
<div class="container my-5">
    <div class="row mb-4">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">หน้าแรก</a></li>
                    <li class="breadcrumb-item active">{{ $rooms->first()?->type_label ?? 'ห้องพัก' }}</li>
                </ol>
            </nav>
            <h2 class="mb-4">ห้อง{{ $rooms->first()?->type_label ?? 'พัก' }} ที่ว่างอยู่</h2>
        </div>
    </div>

    @if($rooms->isEmpty())
        <div class="alert alert-warning text-center">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            ขออภัย ไม่มีห้องประเภทนี้ที่ว่างในช่วงเวลาที่เลือก
        </div>
        <div class="text-center">
            <a href="{{ route('home') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-2"></i>กลับหน้าแรก
            </a>
        </div>
    @else
        <div class="row">
            @foreach($rooms as $room)
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card room-card h-100">
                        @php
                            $sampleImage = match($room->type) {
                                'air_single' => 'air1',
                                'air_double' => 'air2',
                                'fan_single' => 'fan1',
                                'fan_double' => 'fan2',
                                default => null,
                            };

                            $roomImage = $room->image_path ?? $room->image ?? null;

                            $roomImageUrl = null;
                            if ($roomImage && \Illuminate\Support\Facades\Storage::disk('public')->exists($roomImage)) {
                                $roomImageUrl = asset('storage/' . $roomImage);
                            }

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

                        @if($roomImageUrl)
                            <img src="{{ $roomImageUrl }}" class="card-img-top" alt="{{ $room->name_room }}" style="height: 200px; object-fit: cover;">
                        @elseif($sampleImageUrl)
                            <img src="{{ $sampleImageUrl }}" class="card-img-top" alt="{{ $room->type_label }}" style="height: 200px; object-fit: cover;">
                        @else
                            <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                                <i class="bi bi-image display-1 text-muted"></i>
                            </div>
                        @endif
                        
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">ห้อง {{ $room->name_room }}</h5>
                            <p class="card-text">{{ $room->description }}</p>
                            <div class="mt-auto">
                                <p class="text-primary fw-bold mb-2">{{ number_format($room->price) }} บาท/คืน</p>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('rooms.show', $room) }}" class="btn btn-outline-primary flex-fill">
                                        <i class="bi bi-eye me-1"></i>ดูรายละเอียด
                                    </a>
                                    <a href="{{ route('booking.create', $room) }}@if(request('check_in') && request('check_out'))?check_in={{ request('check_in') }}&check_out={{ request('check_out') }}@endif" 
                                       class="btn btn-primary-custom flex-fill">
                                        <i class="bi bi-calendar-check me-1"></i>จองเลย
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
