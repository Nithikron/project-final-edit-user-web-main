<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Room extends Model
{
    use HasFactory;

    protected $table = 'rooms';

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
        // primary match is the literal type (e.g. 'air_single')
        return $query->where(function ($q) use ($type) {
            $q->where('type', $type);

            // if the incoming type looks like the code we use on the
            // public site, also look for legacy entries where we stored
            // just the thai word and used the facility field for air/fan.
            if (str_contains($type, '_')) {
                [$fac, $t] = explode('_', $type, 2);
                $thaiType = $t === 'single' ? 'เดี่ยว' : 'คู่';
                $thaiFacility = $fac === 'air' ? 'แอร์' : 'พัดลม';

                $q->orWhere(function ($q2) use ($thaiType, $thaiFacility) {
                    $q2->where('type', $thaiType)
                       ->whereJsonContains('facility', $thaiFacility);
                });
            }
        });
    }

    public function isAvailableForDates($checkIn, $checkOut): bool
    {
        return !$this->bookings()
            ->whereIn('status', ['pending', 'confirmed'])
            ->where(function ($q) use ($checkIn, $checkOut) {
                // ตรวจสอบว่า booking overlaps กับ check-in/check-out dates
                // booking อยู่ถ้า: check_in_date < checkout request AND check_out_date > checkin request
                $q->where('check_in_date', '<', $checkOut)
                  ->where('check_out_date', '>', $checkIn);
            })
            ->exists();
    }
}
