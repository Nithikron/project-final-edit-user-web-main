<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payments extends Model
{
    protected $fillable = [
        'room_id',
        'tenant_id',
        'tenant_name',
        'type',
        'description',
        'amount',
        'date',
        'status',
        'updated_at',
        'created_at',
        'remark',
        'booking_id',
    ];

    protected $casts = [
        'date' => 'date',
        'amount' => 'float',
    ];

    public function room()
    {
        return $this->belongsTo(Rooms::class);
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}