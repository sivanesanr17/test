<?php

namespace App\Services;

use App\Models\Bus;

class SeatGeneratorService
{
    /**
     * Generate all seats for a newly created bus.
     *
     * Seats are generated using alphabetical row labels (A, B, C...)
     * with seat numbers 1–9 per row (e.g., A1–A9, B1–B9, etc.).
     * 
     */
    public function generateSeats(Bus $bus): void
    {
        $totalSeats = $bus->total_seats;

        $rowLetter = 'A';
        $seatNumber = 1;

        for ($i = 1; $i <= $totalSeats; $i++) {

            $seat_label = $rowLetter . $seatNumber;

            $bus->seats()->create([
                'seat_number' => $seat_label,
            ]);

            $seatNumber++;

            if ($seatNumber > 9) {
                $seatNumber = 1;
                $rowLetter = chr(ord($rowLetter) + 1);
            }
        }
    }

    /**
     * Generate additional seats when bus capacity increases.
     *
     * This method continues seat labeling based on the last
     * existing seat. Example: if seats exist up to C7,
     * new seats will start from C8.
     *
     */
    public function generateAdditionalSeats(Bus $bus, $oldCount, $newCount): void
    {
        $rowLetter = 'A';
        $seatNumber = 1;

        $existingSeats = $bus->seats()->count();

        for ($i = 1; $i <= $existingSeats; $i++) {
            $seatNumber++;
            if ($seatNumber > 9) {
                $seatNumber = 1;
                $rowLetter = chr(ord($rowLetter) + 1);
            }
        }

        for ($i = $existingSeats + 1; $i <= $newCount; $i++) {

            $seatLabel = $rowLetter . $seatNumber;

            $bus->seats()->create([
                'seat_number' => $seatLabel,
            ]);

            $seatNumber++;
            if ($seatNumber > 9) {
                $seatNumber = 1;
                $rowLetter = chr(ord($rowLetter) + 1);
            }
        }
    }

    /**
     * Remove extra seats when bus capacity is reduced.
     *
     * Seats are removed in reverse creation order (last seats first).
     * Booked seats are preserved and will not be deleted.
     *
     */
    public function removeExtraSeats(Bus $bus, int $newSeatCount): void
    {
        $seatsToRemove = $bus->seats()
            ->orderBy('id', 'desc')
            ->skip($newSeatCount)  
            ->take(PHP_INT_MAX)     
            ->get();

        foreach ($seatsToRemove as $seat) {
            if ($seat->bookings()->exists()) {
                continue;
            }

            $seat->delete();
        }
    }
}
