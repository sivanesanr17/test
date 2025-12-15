@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Add New Bus</h2>

        <form action="{{ route('buses.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label>Bus Type</label>
                <input type="text" name="bus_type" class="form-control" value="{{ old('bus_type') }}">
                @error('bus_type')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="mb-3">
                <label>Origin</label>
                <input type="text" name="origin" class="form-control" value="{{ old('origin') }}">
                @error('origin')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="mb-3">
                <label>Destination</label>
                <input type="text" name="destination" class="form-control" value="{{ old('destination') }}">
                @error('destination')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="mb-3">
                <label>Departure Time</label>
                <input type="time" name="departure_time" class="form-control" value="{{ old('departure_time') }}">
                @error('departure_time')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="mb-3">
                <label>Arrival Time</label>
                <input type="time" name="arrival_time" class="form-control" value="{{ old('arrival_time') }}">
                @error('arrival_time')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="mb-3">
                <label>Price per Seat</label>
                <input type="number" name="price_per_seat" class="form-control" value="{{ old('price_per_seat') }}">
                @error('price_per_seat')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="mb-3">
                <label>Total Seats</label>
                <input type="number" name="total_seats" class="form-control" value="{{ old('total_seats') }}">
                @error('total_seats')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <button type="submit" class="btn btn-success">Add Bus</button>
        </form>
    </div>
@endsection
