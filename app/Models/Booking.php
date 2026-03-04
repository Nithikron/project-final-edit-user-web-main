<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_id',
        'user_id',
        // Customer fields
        'customer_name',
        'customer_phone',
        'customer_email',
        'check_in_date',
        'check_out_date',
        'total_price',
        'status',
        'payment_qr',
        'payment_confirmed_at',
        'notes',
        // Admin check-in/check-out/booking system fields
        'tenant_name',
        'type',
        'date',
    ];

    protected $casts = [
        'check_in_date' => 'date',
        'check_out_date' => 'date',
        'total_price' => 'decimal:2',
        'payment_confirmed_at' => 'datetime',
        'date' => 'datetime',
    ];

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'pending' => 'รอการชำระเงิน',
            'confirmed' => 'ยืนยันแล้ว',
            'cancelled' => 'ยกเลิกแล้ว',
            default => 'ไม่ระบุ'
        };
    }

    public function getNightsAttribute(): int
    {
        if ($this->check_in_date && $this->check_out_date) {
            return $this->check_in_date->diffInDays($this->check_out_date);
        }
        return 0;
    }
}
