@extends('layouts.app')

@section('content')
<div class="container">
    <h2>My Patients</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @elseif(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @if($patients->isEmpty())
        <p>No patients found.</p>
    @else
       <ul class="list-group">
    @foreach($patients as $patient)
        <li class="list-group-item">


<a href="{{ route('family.dashboard.patient', $patient->id) }}" class="btn btn-primary">
    View Dashboard
</a>


                {{ $patient->name }}
             - {{ $patient->email }}
        </li>
    @endforeach
</ul>

    @endif

    <h3>Add Patient by Family Code</h3>
    <form action="{{ route('caregiver_patients.add') }}" method="POST" class="mb-4">
        @csrf
        <div class="mb-3">
            <label for="family_code" class="form-label">Family Code</label>
            <input type="text" id="family_code" name="family_code" class="form-control @error('family_code') is-invalid @enderror" required>
            @error('family_code')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <button type="submit" class="btn btn-primary">Add Patient</button>
    </form>
</div>
@endsection
