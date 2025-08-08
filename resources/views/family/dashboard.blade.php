@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="text-center mb-4">
        <h1 class="display-5 text-primary fw-bold">Family Dashboard</h1>
        <p class="text-muted">Stay connected with your family members</p>
    </div>

    <!-- User Info -->
    <div class="card shadow-sm border-0 mb-5">
        <div class="card-body">
            <h4 class="mb-3">Welcome, <span class="text-success">{{ auth()->user()->name }}</span></h4>
            <p><strong>Role:</strong> {{ ucfirst(auth()->user()->role) }}</p>
            <p><strong>Family Code:</strong> 
                @if(auth()->user()->family_code)
                    <code>{{ auth()->user()->family_code }}</code>
                @else
                    <span class="text-danger">Not Assigned</span>
                @endif
            </p>
        </div>
    </div>
<div class="d-flex gap-3 my-4">
    <a href="{{ route('medications.index') }}" class="btn btn-outline-primary btn-lg">
        View Medications
    </a>

    <a href="{{ route('appointments.index') }}" class="btn btn-outline-secondary btn-lg">
        View Appointments
    </a>
     <!-- 76 -->
<!-- <a href="{{ route('medical_files.create') }}" class="btn btn-outline-primary btn-lg">
    Upload Medical File
</a> -->
<!-- <a href="{{ route('medical_files.create', ['family_code' => $familyCode]) }}">View Uploads</a> -->
<a href="{{ route('medical_files.create', ['family_code' => $patient->family_code]) }}">
    <button>Uploaded File</button>
</a>
    </div>



    <!-- Notes -->
    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">📝 Notes</h5>
        </div>

        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <!-- Add Note Form -->
            <form action="{{ route('notes.store') }}" method="POST" class="mb-4">
                @csrf
                <div class="mb-3">
                    <label for="note" class="form-label">Write a Note</label>
                    <textarea name="content" id="note" class="form-control" rows="3" required placeholder="Type your message here..."></textarea>
                </div>
                <button type="submit" class="btn btn-success">
                    <i class="bi bi-plus-circle"></i> Add Note
                </button>
            </form>

            <!-- Notes List -->
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
            @endif
        </div>
    </div>
</div>
@endsection
