@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Appointments</h1>

@if(isset($patient) && $patient)
    <a href="{{ route('appointments.create', ['patient_id' => $patient->id]) }}" class="btn btn-success">
       Add Appointment For  {{ $patient->name }}
    </a>
@else
    <a href="{{ route('appointments.create') }}" class="btn btn-success">
        Add Appointment
    </a>
@endif

<style>

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

    @if($appointments->isEmpty())
        <p>No appointments found.</p>
    @else
    <table class="table table-bordered" id="appointments-table">
        <thead>
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
                    <td class="text">{{ $appointment->location }}</td>
                    <td class="text">{{ $appointment->notes }}</td>
                    <td class="actions">
                        <div style="display:flex; gap: 6px; justify-content: center;">
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
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const table = document.getElementById('appointments-table');

    table.addEventListener('click', function (e) {
        if (e.target.classList.contains('btn-edit')) {
            const btn = e.target;
            const tr = btn.closest('tr');
            
            if (btn.textContent === 'Edit') {
                // تحويل الخلايا إلى حقول إدخال (input)
                tr.querySelectorAll('td.text').forEach((td, idx) => {
                    let input;
                    if(idx === 1) { // التاريخ
                        input = document.createElement('input');
                        input.type = 'date';
                        input.value = td.textContent.trim();
                        input.className = 'form-control form-control-sm';
                    } else if(idx === 2) { // الوقت
                        input = document.createElement('input');
                        input.type = 'time';
                        input.value = td.textContent.trim();
                        input.className = 'form-control form-control-sm';
                    } else { // نصوص عادية
                        input = document.createElement('input');
                        input.type = 'text';
                        input.value = td.textContent.trim();
                        input.className = 'form-control form-control-sm';
                    }
                    td.textContent = '';
                    td.appendChild(input);
                });

                btn.textContent = 'Save';

                // أضف زر إلغاء بجانب حفظ
                const cancelBtn = document.createElement('button');
                cancelBtn.textContent = 'Cancel';
                cancelBtn.className = 'btn btn-sm btn-secondary ms-2 btn-cancel';
                btn.insertAdjacentElement('afterend', cancelBtn);

            } else if (btn.textContent === 'Save') {
                const tr = btn.closest('tr');
                const appointmentId = tr.getAttribute('data-id');

                // اجمع البيانات من الحقول
                const inputs = tr.querySelectorAll('td.text input');
                const doctorName = inputs[0].value;
                const date = inputs[1].value;
                const time = inputs[2].value;
                const location = inputs[3].value;
                const notes = inputs[4].value;

                // دمج التاريخ والوقت لـ appointment_time
                const appointmentTime = date + 'T' + time;

                // إنشاء فورم لإرسال البيانات (PUT)
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
            // إلغاء التعديل - إعادة تحميل الصفحة لاسترجاع البيانات الأصلية
            location.reload();
        }
    });
});
</script>

@endsection
