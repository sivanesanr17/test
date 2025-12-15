@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>{{ __('User Details') }}</span>
                    <div>
                        @can('users.edit')
                            <a href="{{ route('users.edit', $user) }}" class="btn btn-warning btn-sm">
                                Edit
                            </a>
                        @endcan
                        <a href="{{ route('users.index') }}" class="btn btn-secondary btn-sm">
                            Back
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <div class="mb-3">
                        <strong>ID:</strong> {{ $user->id }}
                    </div>

                    <div class="mb-3">
                        <strong>First Name:</strong> {{ $user->first_name }}
                    </div>

                    <div class="mb-3">
                        <strong>Last Name:</strong> {{ $user->last_name }}
                    </div>

                    <div class="mb-3">
                        <strong>Email:</strong> {{ $user->email }}
                    </div>

                    <div class="mb-3">
                        <strong>Contact Number:</strong>
                        {{ $user->contact_number ?? '—' }}
                    </div>

                    <div class="mb-3">
                        <strong>Role:</strong>
                        @if($user->roles->isNotEmpty())
                            @foreach($user->roles as $role)
                                <span class="badge bg-primary">{{ $role->name }}</span>
                            @endforeach
                        @else
                            <span class="text-muted">No role assigned</span>
                        @endif
                    </div>

                    <div class="mb-3">
                        <strong>Created At:</strong>
                        {{ $user->created_at->format('d M Y, h:i A') }}
                    </div>

                    <div class="mb-3">
                        <strong>Updated At:</strong>
                        {{ $user->updated_at->format('d M Y, h:i A') }}
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
