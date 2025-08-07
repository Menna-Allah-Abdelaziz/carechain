<!-- resources/views/appointments/create.blade.php -->

@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Create Appointment</h2>
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('appointments.store') }}">
        @csrf

        <div class="mb-3">
            <label for="doctor_name" class="form-label">Doctor Name</label>
            <input type="text" name="doctor_name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="family_code" class="form-label">Family ID</label>
            <input type="number" name="family_code" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="appointment_time" class="form-label">Appointment Time</label>
            <input type="datetime-local" name="appointment_time" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="location" class="form-label">Location</label>
            <input type="text" name="location" class="form-control">
        </div>

        <div class="mb-3">
            <label for="notes" class="form-label">Notes</label>
            <textarea name="notes" class="form-control"></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Save Appointment</button>
    </form>
</div>
@endsection
