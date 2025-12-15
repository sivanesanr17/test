<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Bus extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'bus_type',
        'origin',
        'destination',
        'departure_time',
        'arrival_time',
        'price_per_seat',
        'total_seats',
    ];

    /**
     * Get all bookings made for this bus.
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Get all seats that belong to this bus.
     */
    public function seats(): HasMany
    {
        return $this->hasMany(Seat::class);
    }

    /**
     * Calculate remaining seats for a given travel date.
     */
    public function remainingSeats(string $travelDate): int
    {
        $bookedSeats = $this->bookings()
            ->where('date_of_booking', $travelDate)
            ->sum('number_of_passengers');

        return $this->total_seats - $bookedSeats;
    }
}
