@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            Assign Roles – {{ $user->first_name }} {{ $user->last_name }}
        </div>

        <div class="card-body">
            <form method="POST" action="{{ route('users.roles.update', $user) }}">
                @csrf
                @method('PUT')

                @foreach($roles as $role)
                    <div class="form-check">
                        <input class="form-check-input"
                               type="checkbox"
                               name="roles[]"
                               value="{{ $role->name }}"
                               {{ $user->hasRole($role->name) ? 'checked' : '' }}>
                        <label class="form-check-label">
                            {{ $role->name }}
                        </label>
                    </div>
                @endforeach

                <button class="btn btn-primary mt-3">Save Roles</button>
                <a href="{{ route('users.index') }}" class="btn btn-secondary mt-3">Cancel</a>
            </form>
        </div>
    </div>
</div>
@endsection
