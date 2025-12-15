@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>{{ __('Role Details') }}</span>
                    <div>
                        <a href="{{ route('roles.edit', $role) }}" class="btn btn-warning btn-sm">Edit</a>
                        <a href="{{ route('roles.index') }}" class="btn btn-secondary btn-sm">Back</a>
                    </div>
                </div>

                <div class="card-body">
                    <div class="mb-3">
                        <strong>ID:</strong> {{ $role->id }}
                    </div>
                    <div class="mb-3">
                        <strong>Name:</strong> {{ $role->name }}
                    </div>
                    <div class="mb-3">
                        <strong>Permissions:</strong>
                        @if($role->permissions->count() > 0)
                            <ul class="list-group mt-2">
                                @foreach($role->permissions as $permission)
                                    <li class="list-group-item">{{ $permission->name }}</li>
                                @endforeach
                            </ul>
                        @else
                            <span class="text-muted">No permissions assigned</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

