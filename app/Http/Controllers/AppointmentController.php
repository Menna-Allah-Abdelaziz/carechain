<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Appointment;

class AppointmentController extends Controller
{
   public function index(Request $request)
{
    $user = auth()->user();

    if ($user->role === 'caregiver' && $request->patient_id) {
        $patient = User::where('id', $request->patient_id)
                       ->where('role', 'patient')
                       ->firstOrFail();

        if (!$user->patients()->where('users.id', $patient->id)->exists()) {
            abort(403, 'Unauthorized');
        }

        $familyCode = $patient->family_code;
    } else {
        $patient = $user->role === 'patient' ? $user : null;
        $familyCode = $user->family_code;
    }

    $appointments = Appointment::where('family_code', $familyCode)->get();

    return view('appointments.index', compact('appointments', 'patient'));
}


   public function create(Request $request)
{
    $user = auth()->user();
    $patient = null;

    if ($user->role === 'caregiver' && $request->patient_id) {
        $patient = User::where('users.id', $request->patient_id)
                       ->where('role', 'patient')
                       ->firstOrFail();


        // تأكد أن المتابع مرتبط بالمريض
        if (!$user->patients()->where('users.id', $patient->id)->exists()) {
            abort(403, 'Unauthorized');
        }
    }

    return view('appointments.create', compact('patient'));
}



    public function store(Request $request)
    {
        $user = auth()->user();

        if ($user->role === 'caregiver') {
            $rules = [
                'doctor_name' => 'required|string|max:255',
                'appointment_time' => 'required|date',
                'location' => 'nullable|string|max:255',
                'notes' => 'nullable|string',
                'patient_id' => 'required|exists:users,id',
            ];
        } else {
            $rules = [
                'doctor_name' => 'required|string|max:255',
                'appointment_time' => 'required|date',
                'location' => 'nullable|string|max:255',
                'notes' => 'nullable|string',
            ];
        }

        $validated = $request->validate($rules);

        if ($user->role === 'caregiver') {
            $patient = User::findOrFail($request->patient_id);

            if (!$user->patients()->where('users.id', $patient->id)->exists()) {
                abort(403, 'Unauthorized');
            }

            $familyCode = $patient->family_code;
            $validated['patient_id'] = $patient->id;   // حفظ patient_id
        } else {
            $familyCode = $user->family_code;
            $validated['patient_id'] = $user->id;       // المريض نفسه
        }

        $validated['family_code'] = $familyCode;

        Appointment::create($validated);

      if ($user->role === 'caregiver') {
        // ارجع لصفحة مواعيد المريض مع تمرير patient_id عشان تعرض مواعيده
        return redirect()->route('appointments.index', ['patient_id' => $patient->id])
                         ->with('success', 'Appointment created successfully.');
    } else {
        // ارجع للصفحة العامة للمريض (مواعيده)
        return redirect()->route('appointments.index')
                         ->with('success', 'Appointment created successfully.');
    }
    }
public function showPatientAppointments(User $patient)
{
    $user = auth()->user();

    if ($user->role === 'caregiver') {
        // تحقق إذا المتابع مربوط بالمريض
        $isLinked = \DB::table('patient_caregiver')
            ->where('caregiver_id', $user->id)
            ->where('patient_id', $patient->id)
            ->exists();

        if (!$isLinked) {
            abort(403, 'Unauthorized access');
        }
    } else {
        // لو مريض، تأكد انه نفس المريض أو من نفس العائلة
        if ($patient->family_code !== $user->family_code) {
            abort(403, 'Unauthorized access');
        }
    }

    $appointments = Appointment::where('family_code', $patient->family_code)->get();

    return view('appointments.index', compact('appointments', 'patient'));
}


    public function update(Request $request, Appointment $appointment)
    {
        $user = auth()->user();

        $request->validate([
            'doctor_name' => 'required|string|max:255',
            'appointment_time' => 'required|date',
            'location' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        if ($user->role === 'caregiver') {
            if (!$user->patients()->where('users.id', $appointment->patient_id)->exists()) {
                abort(403, 'Unauthorized');
            }
        } else {
            if ($user->id !== $appointment->patient_id) {
                abort(403, 'Unauthorized');
            }
        }

        $appointment->update($request->only(['doctor_name', 'appointment_time', 'location', 'notes']));

        return redirect()->back()->with('success', 'Appointment updated successfully.');
    }


    public function destroy(Appointment $appointment)
    {
        $user = auth()->user();

        if ($user->role === 'caregiver') {
            if (!$user->patients()->where('users.id', $appointment->patient_id)->exists()) {
                abort(403, 'Unauthorized');
            }
        } else {
            if ($user->id !== $appointment->patient_id) {
                abort(403, 'Unauthorized');
            }
        }

        $appointment->delete();

        return redirect()->back()->with('success', 'Appointment deleted successfully.');
    }
}




