@extends('layouts.app')

@section('title', 'สมัครสมาชิก')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-success text-white text-center">
                    <h4 class="mb-0">
                        <i class="bi bi-person-plus me-2"></i>สมัครสมาชิก
                    </h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('register.submit') }}" onsubmit="return validateForm()">
                        @csrf
                        
                        @if (session('info'))
                            <div class="alert alert-info alert-dismissible fade show" role="alert">
                                <i class="bi bi-info-circle me-2"></i>{{ session('info') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif
                        
                        <div class="row">
                            <!-- Name -->
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">ชื่อ-นามสกุล</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="bi bi-person"></i>
                                    </span>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name') }}" 
                                           placeholder="กรอกชื่อ-นามสกุล" required>
                                </div>
                                @error('name')
                                    <div class="invalid-feedback d-block">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            
                            <!-- name -->
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">ชื่อผู้ใช้</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="bi bi-person-badge"></i>
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
                        </div>
                        
                        <div class="row">
                            <!-- Email -->
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">อีเมล</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="bi bi-envelope"></i>
                                    </span>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                           id="email" name="email" value="{{ old('email') }}" 
                                           placeholder="กรอกอีเมล" required>
                                </div>
                                @error('email')
                                    <div class="invalid-feedback d-block">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            
                            <!-- Phone -->
                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">เบอร์โทรศัพท์</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="bi bi-telephone"></i>
                                    </span>
                                    <input type="tel" class="form-control @error('phone') is-invalid @enderror" 
                                           id="phone" name="phone" value="{{ old('phone') }}" 
                                           placeholder="กรอกเบอร์โทรศัพท์" maxlength="10">
                                </div>
                                @error('phone')
                                    <div class="invalid-feedback d-block">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="row">
                            <!-- Password -->
                            <div class="col-md-6 mb-3">
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
                            
                            <!-- Password Confirmation -->
                            <div class="col-md-6 mb-3">
                                <label for="password_confirmation" class="form-label">ยืนยันรหัสผ่าน</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="bi bi-lock-fill"></i>
                                    </span>
                                    <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" 
                                           id="password_confirmation" name="password_confirmation" 
                                           placeholder="กรอกรหัสผ่านอีกครั้ง" required>
                                </div>
                                @error('password_confirmation')
                                    <div class="invalid-feedback d-block">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        
                        <!-- Citizen ID - ซ่อนช่องนี้ -->
                        <input type="hidden" id="citizen_id" name="citizen_id" value="">
                        
                        <!-- Submit Button -->
                        <div class="d-grid">
                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="bi bi-person-plus me-2"></i>สมัครสมาชิก
                            </button>
                        </div>
                        
                        @if ($errors->any())
                            <div class="alert alert-danger mt-3">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </form>
                    
                    <hr>
                    
                    <div class="text-center">
                        <p class="mb-0">มีบัญชีอยู่แล้ว? 
                            <a href="{{ route('login.form') }}" class="text-success text-decoration-none">
                                เข้าสู่ระบบ
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function validateForm() {
    const name = document.getElementById('name').value;
    const password = document.getElementById('password').value;
    const passwordConfirm = document.getElementById('password_confirmation').value;
    
    if (!name.trim()) {
        alert('กรุณากรอกชื่อผู้ใช้');
        document.getElementById('name').focus();
        return false;
    }
    
    if (password.length < 6) {
        alert('รหัสผ่านต้องมีอย่างน้อย 6 ตัวอักษร');
        document.getElementById('password').focus();
        return false;
    }
    
    if (password !== passwordConfirm) {
        alert('รหัสผ่านและการยืนยันรหัสผ่านไม่ตรงกัน');
        document.getElementById('password_confirmation').focus();
        return false;
    }
    
    return true;
}
</script>
@endpush
