<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use App\Models\Bus;

class SeatCapacityRule implements ValidationRule
{
    protected $bus;
    public function __construct(Bus $bus)
    {
        $this->bus = $bus;
    }

    /**
     * Run the validation rule.
     *
     * @param string $attribute
     * @param mixed $value
     * @param \Closure(string): void $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $bookedSeatCount = $this->bus->seats()
            ->whereHas('bookings')
            ->count();
        if ($value < $bookedSeatCount) {
            $fail("Cannot reduce seats below the number of booked seats ($bookedSeatCount).");
        }
    }
}
