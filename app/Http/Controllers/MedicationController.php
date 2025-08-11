<?php

namespace App\Http\Controllers;

use App\Models\Medication;
use App\Models\User;  // تأكدي من إضافة هذا السطر
use Illuminate\Http\Request;

class MedicationController extends Controller
{
    public function index(Request $request)
{
    if (auth()->user()->role === 'caregiver' && $request->patient_id) {
        $patient = User::findOrFail($request->patient_id);
        $familyCode = $patient->family_code;
    } else {
        $patient = auth()->user();  // هذا لصفحة المريض نفسه
        $familyCode = $patient->family_code;
    }

    $medications = Medication::where('family_code', $familyCode)->get();

    return view('family.medications', compact('medications', 'patient'));
}


    // تخزين الدواء (يدعم إضافة دواء لمريض محدد أو لنفس المستخدم)
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'dosage' => 'required|string',
            'quantity' => 'required|integer|min:1',
            'times_per_day' => 'required|integer|min:1',
            'first_dose_time' => 'required|date_format:H:i',
            'patient_id' => 'nullable|exists:users,id',
        ]);

        $patientId = $request->input('patient_id');

        if ($patientId) {
            $patient = User::where('id', $patientId)->where('role', 'patient')->firstOrFail();
            $familyCode = $patient->family_code;
        } else {
            $familyCode = auth()->user()->family_code;
        }

        Medication::create([
            'family_code' => $familyCode,
            'name' => $request->name,
            'dosage' => $request->dosage,
            'quantity' => $request->quantity,
            'times_per_day' => $request->times_per_day,
            'first_dose_time' => $request->first_dose_time,
            'created_by' => auth()->id(),
        ]);

        return redirect()->back()->with('success', 'Medication added successfully.');
    }
    public function update(Request $request, Medication $medication)
{
    $request->validate([
        'name' => 'required|string',
        'dosage' => 'required|string',
        'quantity' => 'required|integer|min:1',
        'times_per_day' => 'required|integer|min:1',
        'first_dose_time' => 'required|date_format:H:i',
    ]);

    // تحقق من صلاحية المستخدم (المريض نفسه أو متابع لنفس العائلة)
    if (auth()->user()->family_code !== $medication->family_code) {
        abort(403, 'Unauthorized action.');
    }

    $medication->update([
        'name' => $request->name,
        'dosage' => $request->dosage,
        'quantity' => $request->quantity,
        'times_per_day' => $request->times_per_day,
        'first_dose_time' => $request->first_dose_time,
    ]);

    return redirect()->back()->with('success', 'Medication updated successfully.');
}

public function destroy(Medication $medication)
{
    // تحقق من صلاحية المستخدم
    if (auth()->user()->family_code !== $medication->family_code) {
        abort(403, 'Unauthorized action.');
    }

    $medication->delete();

    return redirect()->back()->with('success', 'Medication deleted successfully.');
}

}


