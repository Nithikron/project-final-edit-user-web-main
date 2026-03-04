<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title', 'ระบบจัดการหอพัก')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>

<body class="bg-gradient-to-br from-blue-50 to-purple-50 min-h-screen">

    <!-- Header -->
    <header class="bg-white shadow-lg mb-4">
        <div class="container mx-auto px-6 py-4 flex items-center justify-between">
            <h1 class="text-3xl font-bold bg-gradient-to-r from-purple-600 to-blue-600 bg-clip-text text-transparent">
                🏢 ระบบจัดการหอพัก
            </h1>
            <div class="text-sm text-gray-600" id="currentDate"></div>



            <div class="text-sm text-gray-600">
    {{ now()->locale('th')->translatedFormat('d F Y H:i') }}
</div>
            
            
        </div>
    </header>


    <nav class="bg-white shadow mb-6">
        <div class="container mx-auto px-6 py-3 flex gap-4">
            <a href="/admin" class="text-blue-600 hover:underline">Dashboard</a>
            {{-- <a class="nav-link {{ request()->is('/') ? 'active': '' }}" href="/">Dashboard</a> --}}
            <a href="/admin/roompages" class="text-gray-600 hover:underline">จัดการห้องพัก</a>
            <a href="/admin/booking" class="text-gray-600 hover:underline">จัดการผู้เข้าพัก</a>
            <a href="/admin/check-in-out" class="text-gray-600 hover:underline">จอง/เช็คอิน/เช็คเอาท์</a>
            <a href="/admin/payment" class="text-gray-600 hover:underline">บันทึกการชำระเงิน</a>
            <a href="/admin/report" class="text-gray-600 hover:underline">รายงาน</a>
        </div>
    </nav>

    @yield('content')

</body>

</html>

{{-- //<a class="nav-link {{ request()->is('/roompages') ? 'active': '' }}" href="/roompages">จัดการห้องพัก</a> --}}