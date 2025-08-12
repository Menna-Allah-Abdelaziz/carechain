@extends('layouts.app')

@section('content')
<div class="container">

    <h2 class="mb-4">Upload Medical File</h2>

    <a href="{{ route('family.dashboard.patient', $patient->id) }}" class="btn btn-primary mb-3">
        View Dashboard
    </a>

    {{-- Success message --}}
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    {{-- Validation Errors --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Upload form --}}
    <form action="{{ route('medical_files.store', ['patient' => $patient->id]) }}" method="POST" enctype="multipart/form-data" class="mb-5">
        @csrf

        <div class="card shadow-sm mb-4">
            <div class="card-header bg-primary text-white text-center">
                <h4 class="mb-0">Upload Medical File</h4>
            </div>
            <div class="card-body">

                <div class="mb-3">
                    <label for="file" class="form-label">Select File</label>
                    <input type="file" name="file" id="file" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="file_type" class="form-label">File Type</label>
                    <input type="text" name="file_type" id="file_type" class="form-control" value="{{ old('file_type') }}" placeholder="e.g. Blood Test, X-Ray" required>
                </div>

                <div class="mb-3">
                    <label for="note" class="form-label">Note</label>
                    <textarea name="note" id="note" class="form-control" rows="2" placeholder="Any additional info...">{{ old('note') }}</textarea>
                </div>

                <input type="hidden" name="patient_id" value="{{ $patient->id }}">

                <button type="submit" class="btn btn-primary w-100">Upload</button>

            </div>
        </div>
    </form>

    {{-- Uploaded files list --}}
    @if($medicalFiles->count() > 0)
        <h4>Uploaded Files</h4>
        <ul class="list-group">
            @foreach($medicalFiles as $file)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <a href="{{ asset('storage/' . $file->file_path) }}" target="_blank">
                        {{ $file->file_type ?? 'Unknown file type' }}
                    </a>
                    <span class="badge bg-secondary">{{ $file->note ?? 'No notes' }}</span>
                    <form action="{{ route('medical_files.destroy', $file->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this file?');" class="ms-3 m-0">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                    </form>
                </li>
            @endforeach
        </ul>
    @else
        <p>No files uploaded yet.</p>
    @endif

</div>
@endsection
