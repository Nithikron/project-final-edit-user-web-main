@extends('layouts.app')

@section('title', 'เข้าสู่ระบบ')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <!-- Success Message -->
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-check-circle-fill me-2"></i>
                        {{ session('success') }}
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            
            <div class="card shadow">
                <div class="card-header bg-primary text-white text-center">
                    <h4 class="mb-0">
                        <i class="bi bi-person-circle me-2"></i>เข้าสู่ระบบ
                    </h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('login.submit') }}">
                        @csrf
                        
                        <!-- name -->
                        <div class="mb-3">
                            <label for="name" class="form-label">ชื่อผู้ใช้</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-person"></i>
                                </span>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name') }}" 
                                       placeholder="กรอกชื่อผู้ใช้" required>
                            </div>
                            @error('name')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        
                        <!-- Password -->
                        <div class="mb-3">
                            <label for="password" class="form-label">รหัสผ่าน</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-lock"></i>
                                </span>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                       id="password" name="password" placeholder="กรอกรหัสผ่าน" required>
                            </div>
                            @error('password')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        
                        <!-- Remember Me -->
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="remember" name="remember">
                            <label class="form-check-label" for="remember">
                                จำฉันไว้
                            </label>
                        </div>
                        
                        <!-- Submit Button -->
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-box-arrow-in-right me-2"></i>เข้าสู่ระบบ
                            </button>
                        </div>
                        
                        @if ($errors->any())
                            <div class="alert alert-danger mt-3" role="alert">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                    <div>
                                        <strong>เกิดข้อผิดพลาด!</strong>
                                        <ul class="mb-0 mt-1">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </form>
                    
                    <hr>
                    
                    <div class="text-center">
                        <p class="mb-0">ยังไม่มีบัญชี? 
                            <a href="{{ route('register.form') }}" class="text-primary text-decoration-none">
                                สมัครสมาชิก
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
