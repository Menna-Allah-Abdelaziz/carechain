@extends('layouts.app')

@section('content')
<div class="container py-5 px-4">
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">Medications</h2>
    <a href="{{ route('family.dashboard') }}" class="btn btn-primary">
        ← Back to Dashboard
    </a>
</div>
<div>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif


    <!-- Form to Add Medication -->
    <form action="{{ route('medications.store') }}" method="POST" class="mb-5">
        @csrf
        <div class="row g-3">
            <div class="col-md-4">
                <input type="text" name="name" class="form-control" placeholder="Medication Name" required>
            </div>
            <div class="col-md-2">
                <input type="text" name="dosage" class="form-control" placeholder="Dosage (e.g. 1 pill)" required>
            </div>
            <div class="col-md-2">
                <input type="number" name="quantity" class="form-control" placeholder="Quantity" required min="1">
            </div>
            <div class="col-md-2">
                <input type="number" name="times_per_day" class="form-control" placeholder="Times/Day" required min="1">
            </div>
            <div class="col-md-2">
                <input type="time" name="first_dose_time" class="form-control" required>
            </div>
        </div>
        <button type="submit" class="btn btn-success mt-3">Add Medication</button>
    </form>

    <!-- Medication List -->
    <table class="table table-bordered">
        <thead class="table-light">
            <tr>
                <th>Name</th>
                <th>Dosage</th>
                <th>Quantity</th>
                <th>Times/Day</th>
                <th>First Dose Time</th>
            </tr>
        </thead>
        <tbody>
            @forelse($medications as $med)
                <tr>
                    <td>{{ $med->name }}</td>
                    <td>{{ $med->dosage }}</td>
                    <td>{{ $med->quantity }}</td>
                    <td>{{ $med->times_per_day }}</td>
                    <td>{{ \Carbon\Carbon::parse($med->first_dose_time)->format('h:i A') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">No medications found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
</div>
@endsection
