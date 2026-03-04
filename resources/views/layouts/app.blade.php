<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'ออมรีสอด 01')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/material_blue.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/flatpickr/dist/plugins/monthSelect/style.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Sarabun', sans-serif;
            background-color: #f8f9fa;
        }
        .hero-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 80px 0;
        }
        .hero-overlay {
            background: rgba(0, 0, 0, 0.5);
            padding: 40px;
            border-radius: 10px;
            backdrop-filter: blur(5px);
        }
        .hero-overlay-animated {
            background: rgba(0, 0, 0, 0.4);
            padding: 40px;
            border-radius: 10px;
            backdrop-filter: blur(5px);
            animation: fadeIn 1s ease-in-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes slideIn {
            from { transform: translateX(-100%); }
            to { transform: translateX(0); }
        }
        @keyframes slideUp {
            from { transform: translateY(100%); }
            to { transform: translateY(0); }
        }
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }
        .hero-section[style]::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(rgba(0,0,0,0.3), rgba(0,0,0,0.5));
            z-index: 1;
            animation: slideIn 1s ease-out;
        }
        .hero-overlay-animated {
            background: rgba(0, 0, 0, 0.4);
            padding: 40px;
            border-radius: 10px;
            backdrop-filter: blur(5px);
            animation: fadeIn 1.5s ease-in-out, pulse 3s infinite;
        }
        .room-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border: none;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .room-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }
        .btn-primary-custom {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
            transition: all 0.3s ease;
        }
        .btn-primary-custom:hover {
            background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
        }
        .btn-light {
            transition: all 0.3s ease;
        }
        .btn-light:hover {
            background-color: rgba(255, 255, 255, 0.9);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
        }
        .room-selector {
            display: flex;
            flex-direction: column;
            gap: 15px;
            max-width: 600px;
            margin: 0 auto;
        }
        .room-option {
            display: flex;
            align-items: center;
            background: rgba(255, 255, 255, 0.95);
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 15px;
            padding: 20px;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        .room-option:hover {
            background: rgba(255, 255, 255, 1);
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            border-color: rgba(255, 255, 255, 0.8);
        }
        .room-option.active {
            background: rgba(255, 255, 255, 1);
            border-color: #667eea;
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
        }
        .room-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 20px;
            font-size: 24px;
            color: #667eea;
            background: rgba(255, 255, 255, 0.8);
        }
        .room-info {
            flex: 1;
            text-align: left;
        }
        .room-info h4 {
            margin: 0 0 5px 0;
            color: #333;
            font-weight: 600;
        }
        .room-info p {
            margin: 0 0 5px 0;
            color: #666;
            font-size: 14px;
        }
        .room-info .price {
            font-weight: bold;
            color: #667eea;
            font-size: 16px;
        }
        .room-arrow {
            margin-left: 15px;
            color: #667eea;
            font-size: 20px;
            transition: transform 0.3s ease;
        }
        .room-option:hover .room-arrow {
            transform: translateY(5px);
        }
        .room-option.active .room-arrow {
            transform: translateY(5px);
            color: #764ba2;
        }
        @media (max-width: 768px) {
            .room-selector {
                max-width: 100%;
            }
            .room-option {
                padding: 15px;
            }
            .room-icon {
                width: 50px;
                height: 50px;
                font-size: 20px;
                margin-right: 15px;
            }
            .room-info h4 {
                font-size: 16px;
            }
            .room-info p {
                font-size: 12px;
            }
            .room-info .price {
                font-size: 14px;
            }
            .room-arrow {
                margin-left: 10px;
                font-size: 18px;
            }
        }
        .footer {
            background-color: #343a40;
            color: white;
            padding: 40px 0;
            margin-top: 80px;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <i class="bi bi-house-door-fill"></i>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    @if(request()->routeIs('home'))
                        <li class="nav-item me-2 d-flex align-items-center">
                            <a href="{{ route('booking.history') }}" class="btn btn-sm btn-outline-light">ค้นหาประวัติการจอง</a>
                        </li>
                    @endif
                    @if(!session()->has('user_id'))
                        <li class="nav-item me-2 d-flex align-items-center">
                            <a class="btn btn-sm btn-outline-light" href="{{ route('login.form') }}">
                                <i class="bi bi-box-arrow-in-right me-1"></i>เข้าสู่ระบบ
                            </a>
                        </li>
                        <li class="nav-item me-2 d-flex align-items-center">
                            <a class="btn btn-sm btn-outline-light" href="{{ route('register.form') }}">
                                <i class="bi bi-person-plus me-1"></i>สมัครสมาชิก
                            </a>
                        </li>
                    @else
                        <li class="nav-item dropdown me-2 d-flex align-items-center">
                            <a class="btn btn-sm btn-outline-light dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle me-1"></i>{{ session('user_name') }}
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="bi bi-box-arrow-right me-1"></i>ออกจากระบบ
                                </a></li>
                            </ul>
                        </li>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    @endif
                </ul>
            </div>
        </div>
    </nav>

    <main>
        @yield('content')
    </main>

    <footer class="footer">
        <div class="container text-center">
            <p>&copy; {{ date('Y') }} ระบบจองหอพัก กลุ่มออมรีสอด</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/th.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/plugins/monthSelect/index.js"></script>
    @vite(['resources/js/app.js'])
    @yield('scripts')
</body>
</html>
