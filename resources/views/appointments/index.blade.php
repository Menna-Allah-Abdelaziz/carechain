@extends('layouts.app')

@section('content')
<div class="container col-md-8 mt-5">
  <div class="card shadow-sm">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
      <h4 class="mb-0">Appointments</h4>

      <div>
        @if(isset($patient) && $patient)
          <a href="{{ route('appointments.create', ['patient_id' => $patient->id]) }}" class="btn btn-success me-2">
            Add Appointment For {{ $patient->name }}
          </a>
          <a href="{{ route('family.dashboard.patient', $patient->id) }}" class="btn btn-primary btn-custom-white">
            View Dashboard
          </a>
        @else
          <a href="{{ route('appointments.create') }}" class="btn btn-success">
            Add Appointment
          </a>
        @endif
      </div>
    </div>

    <div class="card-body p-0">
      @if($appointments->isEmpty())
        <p class="p-3">No appointments found.</p>
      @else
        <div class="table-responsive">
          <table class="table table-bordered table-hover mb-0" id="appointments-table">
            <thead class="table-light">
              <tr>
                <th>Doctor Name</th>
                <th>Date</th>
                <th>Time</th>
                <th>Location</th>
                <th>Notes</th>
                <th style="width: 140px;">Actions</th>
              </tr>
            </thead>
            <tbody>
              @foreach($appointments as $appointment)
              <tr data-id="{{ $appointment->id }}">
                <td class="text">{{ $appointment->doctor_name }}</td>
                <td class="text">{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('Y-m-d') }}</td>
                <td class="text">{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('H:i') }}</td>
                <td class="text">{{ $appointment->location ?? '-' }}</td>
                <td class="text text-truncate" style="max-width: 250px;" title="{{ $appointment->notes ?? 'No notes' }}">
                  {{ $appointment->notes ?? '-' }}
                </td>
                <td class="actions">
                  <div class="d-flex justify-content-center gap-2">
                    <button class="btn btn-sm btn-warning btn-edit" style="min-width: 60px;">Edit</button>

                    <form action="{{ route('appointments.destroy', $appointment->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذا الموعد؟');" style="margin:0;">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="btn btn-sm btn-danger" style="min-width: 60px;">Delete</button>
                    </form>
                  </div>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      @endif
    </div>
  </div>
</div>

<style>
  /* ستايل الزر الأبيض الخاص بالدashboard */
  .btn-custom-white {
    background-color: white;
    color: #0d6efd; /* أزرق Bootstrap */
    border: 2px solid #0d6efd;
    transition: background-color 0.3s, color 0.3s;
  }
  .btn-custom-white:hover {
    background-color: #0d6efd;
    color: white;
  }
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const table = document.getElementById('appointments-table');
    if (!table) return; // لو الجدول مش موجود ما نكملش

    table.addEventListener('click', function (e) {
        if (e.target.classList.contains('btn-edit')) {
            const btn = e.target;
            const tr = btn.closest('tr');
            
            if (btn.textContent.trim() === 'Edit') {
                // تحويل الخلايا إلى inputs
                tr.querySelectorAll('td.text').forEach((td, idx) => {
                    let input;
                    if(idx === 1) { // تاريخ
                        input = document.createElement('input');
                        input.type = 'date';
                        input.value = td.textContent.trim();
                        input.className = 'form-control form-control-sm';
                    } else if(idx === 2) { // وقت
                        input = document.createElement('input');
                        input.type = 'time';
                        input.value = td.textContent.trim();
                        input.className = 'form-control form-control-sm';
                    } else {
                        input = document.createElement('input');
                        input.type = 'text';
                        input.value = td.textContent.trim();
                        input.className = 'form-control form-control-sm';
                    }
                    td.textContent = '';
                    td.appendChild(input);
                });

                btn.textContent = 'Save';

                // زر إلغاء بجانب زر حفظ
                const cancelBtn = document.createElement('button');
                cancelBtn.textContent = 'Cancel';
                cancelBtn.className = 'btn btn-sm btn-secondary ms-2 btn-cancel';
                btn.insertAdjacentElement('afterend', cancelBtn);

            } else if (btn.textContent.trim() === 'Save') {
                const tr = btn.closest('tr');
                const appointmentId = tr.getAttribute('data-id');

                const inputs = tr.querySelectorAll('td.text input');
                const doctorName = inputs[0].value;
                const date = inputs[1].value;
                const time = inputs[2].value;
                const location = inputs[3].value;
                const notes = inputs[4].value;

                const appointmentTime = date + 'T' + time;

                const data = {
                    _token: '{{ csrf_token() }}',
                    _method: 'PUT',
                    doctor_name: doctorName,
                    appointment_time: appointmentTime,
                    location: location,
                    notes: notes,
                };

                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/appointments/${appointmentId}`;
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
            location.reload(); // إعادة تحميل لاسترجاع البيانات الأصلية
        }
    });
});
</script>
@endsection
