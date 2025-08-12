@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Upload Medical File</h2>

    {{-- رسالة النجاح --}}
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    {{-- فورم رفع الملف --}}
    <form action="{{ route('medical_files.store') }}" method="POST" enctype="multipart/form-data" class="mb-5">
        @csrf

        <div class="mb-3">
            <label for="file" class="form-label">Select File</label>
            <input type="file" name="file" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="file_type" class="form-label">File Type</label>
            <input type="text" name="file_type" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="note" class="form-label">Note</label>
            <textarea name="note" class="form-control" rows="2"></textarea>
        </div>

        <input type="hidden" name="family_code" value="{{ $familyCode }}">

        <button type="submit" class="btn btn-primary">Upload</button>
    </form>

    {{-- قائمة الملفات المرفوعة --}}
    @if(count($medicalFiles))
        <h3 class="mb-3">Uploaded Files</h3>

        <div class="row row-cols-1 row-cols-md-2 g-4">
            @foreach($medicalFiles as $file)
                <div class="col">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title">File Type: {{ $file->file_type }}</h5>
                            <p class="card-text">Note: {{ $file->note }}</p>
                            <a href="{{ asset('storage/' . $file->file_path) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                View File
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else 
    <h3>no files</h3>
    @endif
</div>
@endsection