@extends('layouts.app')

@section('content')
<div class="container" style="max-width: 500px; margin: 50px auto;">
    <h2 class="mb-4 text-center">Register as Patient</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('register.patient.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input 
                type="text" 
                name="name" 
                id="name" 
                class="form-control" 
                value="{{ old('name') }}" 
                required 
                autofocus>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input 
                type="email" 
                name="email" 
                id="email" 
                class="form-control" 
                value="{{ old('email') }}" 
                required>
        </div>

        <div class="mb-3">
            <label for="family_code" class="form-label">Family Code</label>
            <input 
                type="text" 
                name="family_code" 
                id="family_code" 
                class="form-control" 
                value="{{ old('family_code') }}" 
                required>
            <small class="form-text text-muted">Create a unique family code to share with your caregivers.</small>
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input 
                type="password" 
                name="password" 
                id="password" 
                class="form-control" 
                required>
        </div>

        <div class="mb-3">
            <label for="password_confirmation" class="form-label">Confirm Password</label>
            <input 
                type="password" 
                name="password_confirmation" 
                id="password_confirmation" 
                class="form-control" 
                required>
        </div>

        <button type="submit" class="btn btn-primary w-100">Register as Patient</button>
    </form>
</div>
@endsection
