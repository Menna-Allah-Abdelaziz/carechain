@extends('layouts.app')

@section('content')
<div class="vh-100 d-flex flex-column justify-content-center align-items-center bg-light text-center">
    <h1 class="display-4 mb-3 text-primary">Family Health Tracker</h1>
    <p class="lead mb-4 text-secondary">Manage your family’s appointments, medications, and notes all in one place.</p>

    @guest
    <div>
        <a href="{{ route('login') }}" class="btn btn-primary btn-lg me-3">Login</a>
        <a href="{{ route('register_choice') }}" class="btn btn-outline-primary btn-lg">Register</a>
    </div>
    @else
    <a href="{{ route('family.dashboard') }}" class="btn btn-success btn-lg">Go to Dashboard</a>
    @endguest
</div>
@endsection

