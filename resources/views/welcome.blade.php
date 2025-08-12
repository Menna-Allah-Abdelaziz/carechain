@extends('layouts.app')

@section('content')
<div class="vh-100 d-flex flex-column justify-content-center align-items-center bg-light text-center p-4" style="max-width: 480px; margin: auto; border-radius: 16px; box-shadow: 0 6px 15px rgba(44,122,123,0.2);">
    <h1 class="display-4 mb-3 text-primary" style="font-weight: 600;">Family Health Tracker</h1>
    <p class="lead mb-4 text-secondary" style="font-weight: 600;">
        Manage your family’s appointments, medications, and notes all in one place.
    </p>

    @guest
    <div>
        <a href="{{ route('login') }}" class="btn btn-primary btn-lg me-3 px-4" style="min-width: 140px; transition: background-color 0.3s;">
            Login
        </a>
        <a href="{{ route('register_choice') }}" class="btn btn-custom-white btn-lg px-4" style="min-width: 140px; transition: background-color 0.3s;">
            Register
        </a>
    </div>
    @else
    <a href="{{ route('family.dashboard') }}" class="btn btn-success btn-lg px-5" style="min-width: 160px; transition: background-color 0.3s;">
        Go to Dashboard
    </a>
    @endguest
</div>
@endsection
