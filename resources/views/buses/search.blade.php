@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Available Buses for {{ $travelDate }}</h2>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Bus Type</th>
                <th>Origin</th>
                <th>Destination</th>
                <th>Departure</th>
                <th>Arrival</th>
                <th>Price</th>
                <th>Remaining Seats</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($buses as $bus)
            <tr>
                <td>{{ $bus->bus_type }}</td>
                <td>{{ $bus->origin }}</td>
                <td>{{ $bus->destination }}</td>
                <td>{{ $bus->departure_time }}</td>
                <td>{{ $bus->arrival_time }}</td>
                <td>{{ $bus->price_per_seat }}</td>
                <td>{{ $bus->remaining_seats }}</td>
                <td>
                    @auth
                        <a href="{{ route('buses.show', $bus->id) }}" class="btn btn-sm btn-primary">Book</a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-sm btn-secondary">Login to Book</a>
                    @endauth
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8">No buses found for this route.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    {{ $buses->links() }}
</div>
@endsection
