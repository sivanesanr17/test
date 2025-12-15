<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
class Seat extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'bus_id',
        'seat_number',
        'status',
    ];

     /**
     * Get the bus that this seat belongs to.
     */
    public function bus(): BelongsTo
    {
        return $this->belongsTo(Bus::class);
    }
    
    /**
     * Get the bookings associated with this seat.
     */
    public function bookings(): BelongsToMany
    {
        return $this->belongsToMany(Booking::class, 'booking_seat');
    }
}