@extends('layouts.app')

@section('content')
<div class="container col-md-6 mt-5">
  <div class="card shadow-sm">
    <div class="card-header bg-primary text-white">
      <h4 class="mb-0 text-center">Create Appointment</h4>
    </div>
    <div class="card-body">

      @if(session('success'))
          <div class="alert alert-success">{{ session('success') }}</div>
      @endif

      @if ($errors->any())
      <div class="alert alert-danger">
          <ul class="mb-0">
              @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
              @endforeach
          </ul>
      </div>
      @endif

      <form method="POST" action="{{ route('appointments.store') }}">
          @csrf

          @if(auth()->user()->role === 'caregiver' && isset($patient))
              <input type="hidden" name="patient_id" value="{{ $patient->id }}">
          @endif

          <div class="mb-4">
              <label for="doctor_name" class="form-label">Doctor Name</label>
              <input type="text" name="doctor_name" class="form-control" placeholder="Enter doctor's name" required>
          </div>
          
          <div class="mb-4">
              <label for="appointment_time" class="form-label">Appointment Time</label>
              <input type="datetime-local" name="appointment_time" class="form-control" required>
          </div>

          <div class="mb-4">
              <label for="location" class="form-label">Location</label>
              <input type="text" name="location" class="form-control" placeholder="Enter location">
          </div>

          <div class="mb-4">
              <label for="notes" class="form-label">Notes</label>
              <textarea name="notes" class="form-control" placeholder="Additional notes"></textarea>
          </div>

          <button type="submit" class="btn btn-primary w-100">
              Save Appointment
          </button>
      </form>

    </div>
  </div>
</div>
@endsection
