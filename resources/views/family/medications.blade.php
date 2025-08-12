@extends('layouts.app')

@section('content')
<div class="container py-5 px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">
            Medications for 
            @if(isset($patient))
                {{ $patient->name }}
            @else
                You
            @endif
        </h2>
        <a href="{{ route('family.dashboard.patient', $patient->id) }}" class="btn btn-primary">
    View Dashboard
</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- Form to Add Medication -->
    <form action="{{ route('medications.store') }}" method="POST">
        @csrf
        @if(isset($patient) && auth()->user()->role === 'caregiver')
            <input type="hidden" name="patient_id" value="{{ $patient->id }}">
        @endif

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
        <button type="submit" class="btn btn-custom-white mt-3">Add Medication</button>
    </form>

    <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover align-middle text-center" id="medications-table" style="border-radius: 8px; overflow: hidden;">
            <thead class="table-primary">
                <tr>
                    <th>Name</th>
                    <th>Dosage</th>
                    <th>Quantity</th>
                    <th>Times/Day</th>
                    <th>First Dose Time</th>
                    <th>Added By</th>
                    <th style="width: 140px; max-width: 140px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($medications as $med)
                    <tr data-id="{{ $med->id }}">
                        <td class="text">{{ $med->name }}</td>
                        <td class="text">{{ $med->dosage }}</td>
                        <td class="text">{{ $med->quantity }}</td>
                        <td class="text">{{ $med->times_per_day }}</td>
                        <td class="text">{{ \Carbon\Carbon::parse($med->first_dose_time)->format('h:i A') }}</td>
                        <td>{{ $med->user->name ?? 'Unknown' }}</td>
                        <td class="actions">
                            <div style="display: flex; justify-content: center; gap: 6px; flex-wrap: nowrap; width: 100%; box-sizing: border-box;">
                                <button class="btn btn-sm btn-warning btn-edit" style="flex: 1; min-width: 60px;">Edit</button>
                                <form action="{{ route('medications.destroy', $med->id) }}" method="POST" onsubmit="return confirm('Are you sure to delete this medicine?');" style="margin:0; flex: 1; min-width: 60px;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger w-100">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const table = document.getElementById('medications-table');

    table.addEventListener('click', function (e) {
        if (e.target.classList.contains('btn-edit')) {
            const btn = e.target;
            const tr = btn.closest('tr');

            if (btn.textContent.trim() === 'Edit') {
                // Convert cells to inputs
                tr.querySelectorAll('td.text').forEach(td => {
                    const value = td.textContent.trim();
                    let input;
                    if(td.cellIndex === 4) { // First Dose Time
                        const timeValue = convertTo24Hour(value);
                        input = document.createElement('input');
                        input.type = 'time';
                        input.value = timeValue;
                        input.className = 'form-control form-control-sm';
                    } else if(td.cellIndex === 2 || td.cellIndex === 3) { // Quantity and Times/Day
                        input = document.createElement('input');
                        input.type = 'number';
                        input.min = 1;
                        input.value = value;
                        input.className = 'form-control form-control-sm';
                    } else { // Text inputs
                        input = document.createElement('input');
                        input.type = 'text';
                        input.value = value;
                        input.className = 'form-control form-control-sm';
                    }
                    td.textContent = '';
                    td.appendChild(input);
                });

                btn.textContent = 'Save';

                // Add cancel button
                const cancelBtn = document.createElement('button');
                cancelBtn.textContent = 'Cancel';
                cancelBtn.className = 'btn btn-sm btn-secondary ms-2 btn-cancel';
                btn.insertAdjacentElement('afterend', cancelBtn);

            } else if (btn.textContent.trim() === 'Save') {
                // Submit edited data

                const medId = tr.getAttribute('data-id');
                const inputs = tr.querySelectorAll('td.text input');
                const data = {
                    _token: '{{ csrf_token() }}',
                    name: inputs[0].value,
                    dosage: inputs[1].value,
                    quantity: inputs[2].value,
                    times_per_day: inputs[3].value,
                    first_dose_time: inputs[4].value,
                    _method: 'PUT'
                };

                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/family/medications/${medId}`;
                form.style.display = 'none';

                for (const key in data) {
                    const input = document.createElement('input');
                    input.name = key;
                    input.value = data[key];
                    form.appendChild(input);
                }

                document.body.appendChild(form);
                form.submit();

            }
        } else if (e.target.classList.contains('btn-cancel')) {
            // Cancel edit - reload page
            location.reload();
        }
    });

    // Convert time from AM/PM to 24-hour format
    function convertTo24Hour(time12h) {
        const [time, modifier] = time12h.split(' ');
        let [hours, minutes] = time.split(':');

        if (hours === '12') {
            hours = '00';
        }

        if (modifier === 'PM') {
            hours = parseInt(hours, 10) + 12;
        }

        return `${hours.toString().padStart(2, '0')}:${minutes}`;
    }
});
</script>

@endsection
