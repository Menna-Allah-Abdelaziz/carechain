@extends('layouts.app')

@section('title', 'Register as Caregiver')

@section('content')
<div class="container mt-5">
    <h2 class="mb-4">Register as Caregiver</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('register.caregiver.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="name" class="form-label">Full Name</label>
            <input type="text" name="name" id="name" 
                class="form-control @error('name') is-invalid @enderror" 
                value="{{ old('name') }}" required autofocus>
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email address</label>
            <input type="email" name="email" id="email" 
                class="form-control @error('email') is-invalid @enderror" 
                value="{{ old('email') }}" required>
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" name="password" id="password" 
                class="form-control @error('password') is-invalid @enderror" required>
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="password_confirmation" class="form-label">Confirm Password</label>
            <input type="password" name="password_confirmation" id="password_confirmation" 
                class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary">Register as Caregiver</button>
    </form>
</div>
@endsection
