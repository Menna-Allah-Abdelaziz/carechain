@extends('layouts.app')

@section('content')
<div class="container mt-5">
    @if (session('success'))
        <div class="alert alert-success text-center">
            {{ session('success') }}
        </div>
    @endif

    <div class="card shadow rounded">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Upload Medical File</h4>
        </div>

        <div class="card-body">
            <form action="{{ route('medical-files.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- File -->
                <div class="mb-3">
                    <label for="file" class="form-label">Choose a file (PDF or image)</label>
                    <input type="file" name="file" class="form-control @error('file') is-invalid @enderror" required>
                    @error('file')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- File Type -->
                <div class="mb-3">
                    <label for="file_type" class="form-label">File Type (optional)</label>
                    <input type="text" name="file_type" class="form-control" placeholder="Example: X-ray, Blood Test...">
                </div>

                <!-- Note -->
                <div class="mb-3">
                    <label for="note" class="form-label">Note (optional)</label>
                    <textarea name="note" class="form-control" rows="3" placeholder="Add a note about the file..."></textarea>
                </div>

                <!-- Button -->
                <div class="text-center">
                    <button type="submit" class="btn btn-success px-5">Upload File</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

{{-- ✅ Toast notification after upload --}}
@if(session('success'))
    <script>
        window.onload = function () {
            const toast = document.createElement('div');
            toast.innerText = "{{ session('success') }}";
            toast.style.position = 'fixed';
            toast.style.top = '20px';
            toast.style.right = '20px';
            toast.style.backgroundColor = '#28a745';
            toast.style.color = 'white';
            toast.style.padding = '15px 25px';
            toast.style.borderRadius = '10px';
            toast.style.boxShadow = '0 0 10px rgba(0,0,0,0.2)';
            toast.style.zIndex = 9999;
            document.body.appendChild(toast);

            setTimeout(() => {
                toast.remove();
            }, 3000);
        }
    </script>
@endif