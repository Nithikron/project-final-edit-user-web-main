<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'name_room',
        'type',
        'facility',
        'price',
        'status'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'facility' => 'array',
        'status' => 'string'
    ];

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function getTypeLabelAttribute(): string
    {
        return match($this->type) {
            'air_single' => 'แอร์เตียงเดี่ยว',
            'air_double' => 'แอร์เตียงคู่',
            'fan_single' => 'พัดลมเตียงเดี่ยว',
            'fan_double' => 'พัดลมเตียงคู่',
            'เดี่ยว' => 'ห้องเดี่ยว',
            'คู่' => 'ห้องคู่',
            default => $this->type
        };
    }

    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function isAvailableForDates($checkIn, $checkOut): bool
    {
        return !$this->bookings()
            ->whereIn('status', ['pending', 'confirmed'])
            ->where(function ($q) use ($checkIn, $checkOut) {
                $q->whereBetween('check_in_date', [$checkIn, $checkOut])
                  ->orWhereBetween('check_out_date', [$checkIn, $checkOut])
                  ->orWhere(function ($q) use ($checkIn, $checkOut) {
                      $q->where('check_in_date', '<=', $checkIn)
                        ->where('check_out_date', '>=', $checkOut);
                  });
            })
            ->exists();
    }
}
