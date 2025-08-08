@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h3 class="mb-4 text-center">Uploaded Medical Files</h3>

    @if ($files->isEmpty())
        <div class="alert alert-info text-center">No files uploaded yet.</div>
    @else
        <div class="table-responsive">
            <table class="table table-bordered table-hover text-center">
                <thead class="table-primary">
                    <tr>
                        <th>#</th>
                        <th>File</th>
                        <th>Note</th>
                        <th>Type</th>
                        <th>Family Code</th>
                        <th>Uploaded At</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($files as $index => $file)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                <a href="{{ asset('storage/' . $file->file_path) }}" target="_blank">View File</a>
                            </td>
                            <td>{{ $file->note ?? '—' }}</td>
                            <td>{{ $file->file_type ?? '—' }}</td>
                            <td>{{ $file->family_code }}</td>
                            <td>{{ $file->created_at->format('Y-m-d H:i') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection