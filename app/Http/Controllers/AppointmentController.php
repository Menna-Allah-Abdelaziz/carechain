<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Appointment;
use App\Models\User;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    // Display list of appointments
    public function index(Request $request)
    {
        $user = auth()->user();

        if ($user->role === 'caregiver' && $request->patient_id) {
            // If caregiver, get the patient by ID and role check
            $patient = User::where('id', $request->patient_id)
                           ->where('role', 'patient')
                           ->firstOrFail();

            // Check if caregiver is linked to this patient
            if (!$user->patients()->where('users.id', $patient->id)->exists()) {
                abort(403, 'Unauthorized');
            }

            $familyCode = $patient->family_code;
        } else {
            // If patient, set $patient as current user
            $patient = $user->role === 'patient' ? $user : null;
            $familyCode = $user->family_code;
        }

        // Get all appointments by family_code
        $appointments = Appointment::where('family_code', $familyCode)->get();

        return view('appointments.index', compact('appointments', 'patient'));
    }

    // Show the form to create a new appointment
    public function create(Request $request)
    {
        $user = auth()->user();
        $patient = null;

        if ($user->role === 'caregiver' && $request->patient_id) {
            // Get patient if caregiver and patient_id provided
            $patient = User::where('users.id', $request->patient_id)
                           ->where('role', 'patient')
                           ->firstOrFail();

            // Verify caregiver is linked to patient
            if (!$user->patients()->where('users.id', $patient->id)->exists()) {
                abort(403, 'Unauthorized');
            }
        }

        return view('appointments.create', compact('patient'));
    }

    // Store a new appointment
    public function store(Request $request)
    {
        $user = auth()->user();

        // Validation rules depend on user role
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

            // Confirm caregiver linked to patient
            if (!$user->patients()->where('users.id', $patient->id)->exists()) {
                abort(403, 'Unauthorized');
            }

            $familyCode = $patient->family_code;
            $validated['patient_id'] = $patient->id;   // assign patient id
        } else {
            $familyCode = $user->family_code;
            $validated['patient_id'] = $user->id;       // assign self as patient
        }

        $validated['family_code'] = $familyCode;

        Appointment::create($validated);

        // Redirect based on role
        if ($user->role === 'caregiver') {
            return redirect()->route('appointments.index', ['patient_id' => $patient->id])
                             ->with('success', 'Appointment created successfully.');
        } else {
            return redirect()->route('appointments.index')
                             ->with('success', 'Appointment created successfully.');
        }
    }

    // Show appointments for a specific patient (for caregivers or authorized users)
    public function showPatientAppointments(User $patient)
    {
        $user = auth()->user();

        if ($user->role === 'caregiver') {
            // Verify caregiver linked to this patient
            $isLinked = \DB::table('patient_caregiver')
                ->where('caregiver_id', $user->id)
                ->where('patient_id', $patient->id)
                ->exists();

            if (!$isLinked) {
                abort(403, 'Unauthorized access');
            }
        } else {
            // For patients, verify family code matches
            if ($patient->family_code !== $user->family_code) {
                abort(403, 'Unauthorized access');
            }
        }

        $appointments = Appointment::where('family_code', $patient->family_code)->get();

        return view('appointments.index', compact('appointments', 'patient'));
    }

    // Update an existing appointment
    public function update(Request $request, Appointment $appointment)
    {
        $user = auth()->user();

        $request->validate([
            'doctor_name' => 'required|string|max:255',
            'appointment_time' => 'required|date',
            'location' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        // Authorization check: caregiver linked to patient or patient owns appointment
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

    // Delete an appointment
    public function destroy(Appointment $appointment)
    {
        $user = auth()->user();

        // Authorization check before deletion
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
