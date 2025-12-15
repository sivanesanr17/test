<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bus;
use App\Http\Requests\StoreBusRequest;
use App\Http\Requests\UpdateBusRequest;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Services\SeatGeneratorService;
use Illuminate\Support\Facades\DB;

class BusController extends Controller
{
    /**
     * Display a paginated list of all buses.
     */
    public function index(): View
    {
        $buses = Bus::paginate(10);
        return view('buses.index', compact('buses'));
    }

    /**
     * Search buses by origin, destination, and calculate remaining seats.
     */
    public function search(Request $request): View
    {
        $travelDate = $request->date;

        $buses = Bus::where('origin', $request->origin)
            ->where('destination', $request->destination)
            ->paginate(10)
            ->appends($request->only(['origin', 'destination', 'date']));

        $buses->getCollection()->transform(function ($bus) use ($travelDate) {
            $bus->remaining_seats = $bus->remainingSeats($travelDate);
            return $bus;
        });

        return view('buses.search', compact('buses', 'travelDate'));
    }

    /**
     * Show the info of a single bus.
     */
    public function show(Bus $bus): View
    {
        return view('buses.show', compact('bus'));
    }

    /**
     * Show the form to create a new bus.
     */
    public function create(): View
    {
        return view('buses.create');
    }

    /**
     * Store a new bus in the database.
     */
    public function store(StoreBusRequest $request, SeatGeneratorService $seatService): RedirectResponse
    {
        try {
            DB::beginTransaction();

            $bus = Bus::create($request->validated());
            $seatService->generateSeats($bus);

            DB::commit();

            return redirect()->route('buses.index')
                ->with('success', 'Bus created successfully with seats.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors([
                'error' => 'Failed to store bus. Please try again.'
            ]);
        }
    }

    /**
     * Show the form to edit an existing bus.
     */
    public function edit(Bus $bus): View
    {
        return view('buses.edit', compact('bus'));
    }

    /**
     * Update the bus info in the database.
     */
    public function update(UpdateBusRequest $request, Bus $bus, SeatGeneratorService $seatService): RedirectResponse
    {
        $oldSeats = $bus->total_seats;
        $newSeats = $request->total_seats;

        try {
            DB::beginTransaction();

            $bus->update($request->validated());

            if ($newSeats > $oldSeats) {
                $seatService->generateAdditionalSeats($bus, $oldSeats, $newSeats);
            }

            if ($newSeats < $oldSeats) {
                $seatService->removeExtraSeats($bus, $newSeats);
            }
            DB::commit();

            return redirect()->route('buses.index')
                ->with('success', 'Bus updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors([
                'error' => 'Failed to update bus. Please try again.'
            ]);
        }
    }

    /**
     * Delete a bus from the database.
     */
    public function destroy(Bus $bus): RedirectResponse
    {
        try {
            DB::beginTransaction();

            $bus->delete();

            DB::commit();

            return redirect()->route('buses.index')
                ->with('success', 'Bus deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->withErrors([
                'error' => 'Failed to delete bus. Please try again.'
            ]);
        }
    }
}
