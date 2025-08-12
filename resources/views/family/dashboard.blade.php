@extends('layouts.app')

@section('content')
<div class="container py-5">
    <!-- Patient Info -->
    <div class=" border-0  mb-5">
        <div class="card-body d-flex align-items-center gap-5" style="font-size: 2rem;">
            <div>
                <span class="text-end">{{ $patient->name }}</span>
            </div>
           
            <div>
                @if($patient->family_code)
                    <code class="text-black  " style="margin-right: 400px">{{ $patient->family_code }}</code>
                @else
                    <span class="text-success">Not Assigned</span>
                @endif
            </div>
@if(auth()->user()->role === 'caregiver')
    <div class="d-flex  mt-4">
        <a href="{{ route('medications.index', ['patient_id' => $patient->id]) }}" class="btn btn-primary mx-3 px-3 py-2 fs-5">
           Medications
        </a>
        <!-- زرار المواعيد -->
<a href="{{ route('appointments.index', ['patient_id' => $patient->id]) }}" class="btn btn-secondary mx-3 px-3 py-2 fs-5">
    Appointments
  </a>
  <a href="{{ route('medical_files.create', ['patient' => $patient->id]) }}"class="btn btn-primary mx-3 px-3 py-2 fs-5">
Medical Files
</a>

    </div>
   
@endif
        </div>
    </div>

    <!-- Dashboard Title -->
    <div class="text-center mb-4">
        <h1 class="display-5 text-primary fw-bold">Family Dashboard</h1>
        <p class="text-muted">Stay connected with your family members</p>
    </div>

    <!-- Notes Section -->
    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">📝 Notes</h5>
        </div>

        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <!-- Add Note Form -->
<form action="{{ route('notes.store', $patient->id) }}" method="POST">

                @csrf
                <input type="hidden" name="patient_id" value="{{ $patient->id }}">
                <div class="mb-3">
                    <label for="note" class="form-label">Write a Note</label>
                    <textarea name="content" id="note" class="form-control" rows="3" required placeholder="Type your message here..."></textarea>
                </div>
                <button type="submit" class="btn btn-primary mx-3 px-4 py-2 fs-5" style="margin: 30px">
                    <i class="bi bi-plus-circle"></i> Add Note
                </button>
            </form>

@if($notes->count())
    <ul>
        @foreach($notes as $note)
            <li style="margin:10px">
                <strong>{{ $note->user->name }}</strong>: {{ $note->content }}
                <small>{{ $note->created_at->format('Y-m-d H:i') }}</small>
            </li>
        @endforeach
    </ul>
@else
    <p>No notes found.</p>
@endif


<!--
            
            @if(isset($notes) && $notes->count())
                <ul class="list-group">
                    @foreach($notes as $note)
                        <li class="list-group-item">
                            <strong>{{ $note->user->name }}</strong>: {{ $note->content }}
                            <div class="text-muted small">
                                Created: {{ $note->created_at->format('Y-m-d H:i') }}
                            </div>
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="text-muted">No notes yet.</p>
            @endif  -->
        </div>
    </div>

</div>
@endsection
