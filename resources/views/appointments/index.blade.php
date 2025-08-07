@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Appointments</h1>

    <a href="{{ route('appointments.create') }}" class="btn btn-success mb-3">Add Appointment</a>

 
    @if($appointments->isEmpty())
        <p>No appointments found.</p>
    @else
     <table class="table table-bordered">
    <thead>
        <tr>
            <th>Doctor Name</th>
            <th>Date</th>
            <th>Time</th>
            <th>Location</th>
            <th>Notes</th>
        </tr>
    </thead>
    <tbody>
        @foreach($appointments as $appointment)
            <tr>
                <td>{{ $appointment->doctor_name }}</td>
                <td>{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('Y-m-d') }}</td>
                <td>{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('H:i') }}</td>
                <td>{{ $appointment->location }}</td>
                <td>{{ $appointment->notes }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

    @endif
</div>
@endsection
