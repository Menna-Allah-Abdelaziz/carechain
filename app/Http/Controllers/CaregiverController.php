<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CaregiverController extends Controller
{
    public function index()
    {
        // جلب المرضى المرتبطين بالمتابع اللي مسجل دخول
        $patients = auth()->user()->patients;

        return view('caregiver_patients', compact('patients'));
    }
    public function addPatientByFamilyCode(Request $request)
{
    $request->validate([
        'family_code' => 'required|string|exists:users,family_code',
    ]);

    $caregiver = auth()->user();
    $patient = \App\Models\User::where('family_code', $request->family_code)->first();

    // تأكد أن المريض مش مرتبط أصلا
    if ($caregiver->patients()->where('patient_id', $patient->id)->exists()) {
        return redirect()->route('caregiver_patients')->with('error', 'Patient already linked.');
    }

    // اربط المريض بالمتابع
    $caregiver->patients()->attach($patient->id);

    return redirect()->route('caregiver_patients')->with('success', 'Patient added successfully.');
}

}
