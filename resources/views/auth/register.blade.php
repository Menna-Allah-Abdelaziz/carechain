@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow rounded-4">
                <div class="card-header bg-primary text-white text-center ">
                    <h4>Create New Account</h4>
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                       
                        <div class="form-group mb-3">
                            <label for="name">Full Name</label>
                            <input id="name" type="text" class="form-control  @error('name') is-invalid @enderror"
                                name="name" value="{{ old('name') }}" required autofocus>
                            @error('name')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                        </div>

                       
                        <div class="form-group mb-3">
                            <label for="email">Email Address</label>
                            <input id="email" type="email" class="form-control  @error('email') is-invalid @enderror"
                                name="email" value="{{ old('email') }}" required>
                            @error('email')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                        </div>

                       
                        <div class="form-group mb-3">
                            <label for="password">Password</label>
                            <input id="password" type="password" class="form-control  @error('password') is-invalid @enderror"
                                name="password" required>
                            @error('password')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                        </div>

                       
                        <div class="form-group mb-3">
                            <label for="password-confirm">Confirm Password</label>
                            <input id="password-confirm" type="password" class="form-control "
                                name="password_confirmation" required>
                        </div>

                        {{-- Role --}}
                        <div class="form-group mb-4">
                            <label for="role">Registering As:</label>
                            <select name="role" id="role" class="form-control " required>
                                <option value="patient">Patient</option>
                                <option value="caregiver">Caregiver</option>
                            </select>
                        </div>
<div class="form-group">
    <label for="family_code">Family Code</label>
    <input type="text" name="family_code" class="form-control" required>
</div>

                        {{-- Submit Button --}}
                        <div class="form-group text-center">
                            <button type="submit" class="btn btn-success m-2 p-2  ">
                                Register
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
