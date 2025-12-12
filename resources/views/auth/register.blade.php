@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Register') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <!-- First Name -->
                        <div class="row mb-3">
                            <label for="first_name" class="col-md-4 col-form-label text-md-end">First Name</label>

                            <div class="col-md-6">
                                <input id="first_name" type="text" 
                                       class="form-control @error('first_name') is-invalid @enderror" 
                                       name="first_name" value="{{ old('first_name') }}" required autofocus>

                                @error('first_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <!-- Last Name -->
                        <div class="row mb-3">
                            <label for="last_name" class="col-md-4 col-form-label text-md-end">Last Name</label>

                            <div class="col-md-6">
                                <input id="last_name" type="text" 
                                       class="form-control @error('last_name') is-invalid @enderror" 
                                       name="last_name" value="{{ old('last_name') }}" required>

                                @error('last_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <!-- Contact Number -->
                        <div class="row mb-3">
                            <label for="contact_number" class="col-md-4 col-form-label text-md-end">Contact Number</label>

                            <div class="col-md-6">
                                <input id="contact_number" type="text" 
                                       class="form-control @error('contact_number') is-invalid @enderror" 
                                       name="contact_number" value="{{ old('contact_number') }}">

                                @error('contact_number')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="row mb-3">
                            <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" 
                                       class="form-control @error('email') is-invalid @enderror" 
                                       name="email" value="{{ old('email') }}" required>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <!-- Password -->
                        <div class="row mb-3">
                            <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" 
                                       class="form-control @error('password') is-invalid @enderror" 
                                       name="password" required>

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <!-- Confirm Password -->
                        <div class="row mb-3">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-end">{{ __('Confirm Password') }}</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" 
                                       class="form-control" name="password_confirmation" required>
                            </div>
                        </div>

                        <!-- Submit -->
                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Register') }}
                                </button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
