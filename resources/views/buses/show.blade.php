@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Bus Details</h2>

    <table class="table table-bordered">
        <tr>
            <th>Bus Type</th>
            <td>{{ $bus->bus_type }}</td>
        </tr>
        <tr>
            <th>Origin</th>
            <td>{{ $bus->origin }}</td>
        </tr>
        <tr>
            <th>Destination</th>
            <td>{{ $bus->destination }}</td>
        </tr>
        <tr>
            <th>Departure</th>
            <td>{{ $bus->departure_time }}</td>
        </tr>
        <tr>
            <th>Arrival</th>
            <td>{{ $bus->arrival_time }}</td>
        </tr>
        <tr>
            <th>Price</th>
            <td>{{ $bus->price_per_seat }}</td>
        </tr>
        <tr>
            <th>Total Seats</th>
            <td>{{ $bus->total_seats }}</td>
        </tr>
    </table>

    <a href="{{ url()->previous() }}" class="btn btn-secondary">Back</a>
</div>
@endsection
