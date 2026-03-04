@extends('layouts.app')

@section('title', 'เลือกประเภทห้องพัก')

@section('content')
<div class="hero-section hero-slider position-relative overflow-hidden">
    @for($i = 1; $i <= 10; $i++)
        <div class="hero-slide {{ $i === 1 ? 'active' : '' }}" style="background-image: url('{{ asset("images/bg{$i}.png") }}');"></div>
    @endfor

    <div class="container text-center position-relative" style="z-index: 2;">
        <div class="welcome-shadow-box d-inline-block">
            <h1 class="display-4 fw-bold mb-3">ยินดีต้อนรับสู่ออมรีสอด</h1>
            <p class="lead mb-0">เลือกประเภทห้องที่ต้องการจองได้เลย</p>
        </div>
    </div>
</div>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-3 mb-4">
            <div class="card room-card room-select-card h-100">
                <div class="card-body text-center">
                    <i class="bi bi-wind display-1 text-primary mb-3"></i>
                    <h5 class="card-title">แอร์เตียงเดี่ยว</h5>
                    <p class="card-text">ห้องพักปรับอากาศ พร้อมเตียงขนาดเดี่ยว</p>
                    <p class="text-primary fw-bold">500 บาท/คืน</p>
                    <a href="{{ route('rooms.type', 'air_single') }}" class="btn btn-primary-custom w-100">
                        เลือกห้องนี้
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3 mb-4">
            <div class="card room-card room-select-card h-100">
                <div class="card-body text-center">
                    <i class="bi bi-wind display-1 text-info mb-3"></i>
                    <h5 class="card-title">แอร์เตียงคู่</h5>
                    <p class="card-text">ห้องพักปรับอากาศ พร้อมเตียงขนาดคู่</p>
                    <p class="text-info fw-bold">900 บาท/คืน</p>
                    <a href="{{ route('rooms.type', 'air_double') }}" class="btn btn-primary-custom w-100">
                        เลือกห้องนี้
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3 mb-4">
            <div class="card room-card room-select-card h-100">
                <div class="card-body text-center">
                    <i class="bi bi-fan display-1 text-success mb-3"></i>
                    <h5 class="card-title">พัดลมเตียงเดี่ยว</h5>
                    <p class="card-text">ห้องพักพัดลม พร้อมเตียงขนาดเดี่ยว</p>
                    <p class="text-success fw-bold">400 บาท/คืน</p>
                    <a href="{{ route('rooms.type', 'fan_single') }}" class="btn btn-primary-custom w-100">
                        เลือกห้องนี้
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3 mb-4">
            <div class="card room-card room-select-card h-100">
                <div class="card-body text-center">
                    <i class="bi bi-fan display-1 text-warning mb-3"></i>
                    <h5 class="card-title">พัดลมเตียงคู่</h5>
                    <p class="card-text">ห้องพักพัดลม พร้อมเตียงขนาดคู่</p>
                    <p class="text-warning fw-bold">800 บาท/คืน</p>
                    <a href="{{ route('rooms.type', 'fan_double') }}" class="btn btn-primary-custom w-100">
                        เลือกห้องนี้
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<style>
    .hero-slider {
        min-height: 360px;
        display: flex;
        align-items: center;
    }

    .hero-slide {
        position: absolute;
        inset: 0;
        background-size: cover;
        background-position: center;
        opacity: 0;
        transition: opacity 0.8s ease;
    }

    .hero-slide.active {
        opacity: 1;
    }

    .welcome-shadow-box {
        background: rgba(0, 0, 0, 0.35);
        padding: 1.25rem 1.75rem;
        border-radius: 14px;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.25);
        backdrop-filter: blur(2px);
    }

    .room-select-card {
        border: 1px solid rgba(102, 126, 234, 0.25);
        box-shadow: 0 10px 24px rgba(102, 126, 234, 0.18);
    }

    .room-select-card:hover {
        box-shadow: 0 14px 30px rgba(102, 126, 234, 0.28);
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const slides = document.querySelectorAll('.hero-slider .hero-slide');
        if (!slides.length) {
            return;
        }

        let current = 0;
        setInterval(() => {
            slides[current].classList.remove('active');
            let next = current;
            while (next === current) {
                next = Math.floor(Math.random() * slides.length);
            }
            current = next;
            slides[current].classList.add('active');
        }, 6000);
    });
</script>
@endsection
