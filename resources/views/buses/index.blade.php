@extends('layouts.app')

@section('content')
<div class="container">
    <h2>All Buses</h2>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <a href="{{ route('buses.create') }}" class="btn btn-primary mb-3">Add New Bus</a>

    @if($buses->count())
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Bus Type</th>
                <th>Origin</th>
                <th>Destination</th>
                <th>Departure</th>
                <th>Arrival</th>
                <th>Price</th>
                <th>Total Seats</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($buses as $bus)
            <tr>
                <td>{{ $bus->bus_type }}</td>
                <td>{{ $bus->origin }}</td>
                <td>{{ $bus->destination }}</td>
                <td>{{ $bus->departure_time }}</td>
                <td>{{ $bus->arrival_time }}</td>
                <td>{{ $bus->price_per_seat }}</td>
                <td>{{ $bus->total_seats }}</td>
                <td>
                    <a href="{{ route('buses.edit', $bus->id) }}" class="btn btn-sm btn-warning">Edit</a>
                    <form action="{{ route('buses.destroy', $bus->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger" onclick="return confirm('Delete this bus?')">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{ $buses->links() }}
    @else
    <p>No buses available.</p>
    @endif
</div>
@endsection
