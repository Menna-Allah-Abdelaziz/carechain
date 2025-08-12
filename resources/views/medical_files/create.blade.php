@extends('layouts.app')

@section('content')


<div class="container col-md-6 mt-5">

  <div class="card shadow-sm mb-4">
    <div class="card-header bg-primary text-white text-center">
      <h4 class="mb-0">Upload Medical File</h4>
    </div>
    <div class="card-body">

      {{-- رسالة النجاح --}}
      @if(session('success'))
          <div class="alert alert-success alert-dismissible fade show" role="alert">
              {{ session('success') }}
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
      @endif

      <form action="{{ route('medical_files.store') }}" method="POST" enctype="multipart/form-data">
          @csrf

          <div class="mb-3">
              <label for="file" class="form-label">Select File</label>
              <input
                type="file"
                name="file"
                id="file"
                class="form-control"
                required
              >
          </div>

          <div class="mb-3">
              <label for="file_type" class="form-label">File Type</label>
              <input
                type="text"
                name="file_type"
                id="file_type"
                class="form-control"
                value="{{ old('file_type') }}"
                required
              >
          </div>

          <div class="mb-3">
              <label for="note" class="form-label">Note</label>
              <textarea
                name="note"
                id="note"
                class="form-control"
                rows="2"
              >{{ old('note') }}</textarea>
          </div>

          <input type="hidden" name="family_code" value="{{ $familyCode }}">

          <button type="submit" class="btn btn-primary w-100">Upload</button>
      </form>

    </div>
  </div>

  {{-- قائمة الملفات المرفوعة --}}
  @if(count($medicalFiles))
      <h3 class="mb-3">Uploaded Files</h3>

      <div class="row row-cols-1 row-cols-md-2 g-4">
          @foreach($medicalFiles as $file)
              <div class="col">
                  <div class="card h-100 shadow-sm">
                      <div class="card-body d-flex flex-column">
                          <h5 class="card-title">File Type: {{ $file->file_type }}</h5>
                          <p class="card-text flex-grow-1">Note: {{ $file->note ?? '-' }}</p>

                          <div class="d-flex gap-2 mt-auto">
                              <a href="{{ asset('storage/' . $file->file_path) }}" target="_blank" class="btn btn-outline-primary btn-sm btn-same-size flex-fill">
                                  View File
                              </a>

                              <form action="{{ route('medical_files.destroy', $file->id) }}" method="POST" 
                                    onsubmit="return confirm('Are you sure you want to delete this file?');" class="flex-fill m-0">
                                  @csrf
                                  @method('DELETE')
                                  <button type="submit" class="btn btn-danger btn-sm btn-same-size w-100">
                                      Delete
                                  </button>
                              </form>
                          </div>

                      </div>
                  </div>
              </div>
          @endforeach
      </div>
  @else 
      <h3>No files</h3>
  @endif
</div>
@endsection
