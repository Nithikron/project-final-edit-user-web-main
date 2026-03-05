<?php

use App\Http\Controllers\Admin\BookingpagesController;
use App\Http\Controllers\Admin\CheckInOutController;
use App\Http\Controllers\Admin\CheckpagesController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PaymentpagesController;
use App\Http\Controllers\Admin\ReportpagesController;
use App\Http\Controllers\Admin\ReserveController;
use App\Http\Controllers\Admin\RoompagesController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\RoomController;
use Illuminate\Support\Facades\Route;

// Auth Routes
Route::prefix('auth')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login.form');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register.form');
    Route::post('/register', [AuthController::class, 'register'])->name('register.submit');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

// Frontend Routes (สำหรับลูกค้า)
Route::get('/', [RoomController::class, 'index'])->name('home');

Route::prefix('rooms')->group(function () {
    Route::get('/type/{type}', [RoomController::class, 'byType'])->name('rooms.type');
    Route::get('/{room}', [RoomController::class, 'show'])->name('rooms.show');
});

// Debug route - check room data
if (app()->environment('local')) {
    Route::get('/debug/rooms', function () {
        $rooms = \App\Models\Room::all();
        return response()->json([
            'total' => $rooms->count(),
            'rooms' => $rooms->map(fn($r) => [
                'id' => $r->id,
                'name_room' => $r->name_room,
                'type' => $r->type,
                'facility' => $r->facility,
                'status' => $r->status,
            ]),
            'air_single_test' => \App\Models\Room::where('type', 'air_single')->count(),
            'legacy_test' => \App\Models\Room::where('type', 'เดี่ยว')->whereJsonContains('facility', 'แอร์')->count(),
        ]);
    });

    Route::get('/debug/add-sample-room', function () {
        \App\Models\Room::create([
            'name_room' => 'A110',
            'type' => 'fan_double',
            'facility' => ['พัดลม'],
            'price' => 700,
            'status' => 'available',
        ]);
        return response()->json(['message' => 'Room A110 added successfully', 'type' => 'fan_double']);
    });
}

Route::prefix('booking')->group(function () {
    Route::get('/', function () {
        if (auth()->check() && auth()->user()->is_admin) {
            return redirect('/admin/booking');
        }
        return redirect('/booking/history');
    })->name('booking.index');
    Route::get('/create/{room}', [BookingController::class, 'create'])->name('booking.create');
    Route::post('/', [BookingController::class, 'store'])->name('booking.store');
    Route::get('/history', [BookingController::class, 'history'])->name('booking.history');
    Route::delete('/history/{booking}', [BookingController::class, 'destroyFromHistory'])->name('booking.destroy-from-history');
    Route::get('/payment/{booking}', [BookingController::class, 'payment'])->name('booking.payment');
    Route::post('/payment/{booking}', [BookingController::class, 'uploadPayment'])->name('booking.upload');
    Route::get('/receipt/{booking}', [BookingController::class, 'receipt'])->name('booking.receipt');
});

// Edit Roompages (สำหรับทดสอบเฉพาะ local)
if (app()->environment('local')) {
    Route::get('/edit-roompages/{id}', [RoompagesController::class, 'edit'])->name('edit-roompages');
    Route::delete('/delete-roompages/{id}', [RoompagesController::class, 'destroy'])->name('delete-roompages');
    Route::get('/create-roompages', [RoompagesController::class, 'create'])->name('create-roompages');
    Route::post('/store-roompages', [RoompagesController::class, 'store'])->name('store-roompages');
    Route::put('/update-roompages/{id}', [RoompagesController::class, 'update'])->name('update-roompages');
}

// Backend Routes (สำหรับแอดมิน)
Route::prefix('admin')->middleware('auth.admin')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('admin.dashboard');

    // Routes หลักที่ต้องการ
    Route::get('/rooms', [RoompagesController::class, 'index'])->name('admin.rooms');
    Route::get('/bookings', [CheckpagesController::class, 'index'])->name('admin.bookings');
    Route::get('/check-in', [CheckpagesController::class, 'index'])->name('admin.checkin');
    Route::get('/check-out', [CheckpagesController::class, 'index'])->name('admin.checkout');
    Route::get('/payments', [PaymentpagesController::class, 'index'])->name('admin.payments');
    Route::get('/reports', [ReportpagesController::class, 'index'])->name('admin.reports');

    // Main check-in/check-out/booking page
    Route::get('/check-in-out', [CheckpagesController::class, 'index'])->name('admin.check-in-out');

    // Routes เก่า (สำรองไว้ก่อน)
    Route::get('/roompages', [RoompagesController::class, 'index'])->name('admin.roompages');
    Route::get('/booking', [BookingpagesController::class, 'index'])->name('admin.booking');
    Route::put('/booking/{tenant}', [BookingpagesController::class, 'update'])->name('admin.booking.update');
    Route::get('/payment', [PaymentpagesController::class, 'index'])->name('admin.payment');
    Route::get('/report', [ReportpagesController::class, 'index'])->name('admin.report');

    Route::post('/upload/{room}', [AdminController::class, 'uploadImage'])->name('admin.upload');

    // Roompages
    Route::get('/create-roompages', [RoompagesController::class, 'create'])->name('admin.create-roompages');
    Route::post('/store-roompages', [RoompagesController::class, 'store'])->name('admin.store-roompages');
    Route::get('/edit-roompages/{id}', [RoompagesController::class, 'edit'])->name('admin.edit-roompages');
    Route::put('/update-roompages/{id}', [RoompagesController::class, 'update'])->name('admin.update-roompages');
    Route::delete('/delete-roompages/{id}', [RoompagesController::class, 'destroy'])->name('admin.delete-roompages');

    // CheckIn/CheckOut actions
    Route::post('/check-in-store', [CheckInOutController::class, 'store'])->name('admin.checkin.store');
    Route::post('/check-out-store', [CheckInOutController::class, 'checkout'])->name('admin.checkout.store');

    // Payment actions
    Route::post('/payment-store', [PaymentpagesController::class, 'store'])->name('admin.payment.store');
    Route::put('/payment/{id}', [PaymentpagesController::class, 'update'])->name('admin.payment.update');
    Route::delete('/payment/{id}', [PaymentpagesController::class, 'destroy'])->name('admin.payment.destroy');

    // Reservation (reserve controller)
    Route::get('/reserve', [ReserveController::class, 'index'])->name('admin.reserve');
    Route::post('/reserve-store', [ReserveController::class, 'store'])->name('admin.reserve.store');
});

// end of routes file - duplicates and non-prefixed routes removed to avoid conflicts
