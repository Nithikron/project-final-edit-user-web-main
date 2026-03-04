<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rooms extends Model
{
    protected $table = 'rooms';

    protected $fillable = [
        'name_room',
        'type',
        'facility',
        'price',
        'status',
    ];

    // status constants (แนะนำมาก)
    const STATUS_AVAILABLE = 'available';

    const STATUS_OCCUPIED = 'occupied';

    const STATUS_RESERVED = 'reserved';

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}