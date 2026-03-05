<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_id',
        'user_id',
        'tenant_name',
        'type',
        'description',
        'amount',
        'date',
        'status',
        'customer_name',
        'customer_email',
        'customer_phone',
        'room_name',
        'room_type',
        'check_in_date',
        'check_out_date',
        'payment_qr',
        'payment_confirmed_at',
        'booking_id',
        'remark',
        'payment_method',
        'payment_status',
        'payment_date',
        'slip_image',
        'notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'date' => 'date',
        'check_in_date' => 'date',
        'check_out_date' => 'date',
        'payment_date' => 'datetime',
        'payment_confirmed_at' => 'datetime',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}
