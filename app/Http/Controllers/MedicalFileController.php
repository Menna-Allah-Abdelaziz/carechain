<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MedicalFile;
use App\Models\User;

class MedicalFileController extends Controller
{
/*public function index(Request $request)
{
    $user = auth()->user();
    $patient = null;

    if ($user->role === 'caregiver' && $request->patient_id) {
        $patient = User::where('id', $request->patient_id)
                       ->where('role', 'patient')
                       ->firstOrFail();

        if (!$user->patients()->where('users.id', $patient->id)->exists()) {
            abort(403, 'Unauthorized');
        }

        $familyCode = $patient->family_code;
    } elseif ($user->role === 'patient') {
        $patient = $user;
        $familyCode = $user->family_code;
    } else {
        abort(403, 'Unauthorized');
    }

    $medicalFiles = MedicalFile::where('family_code', $familyCode)->get();

    return view('medical_files.index', compact('medicalFiles', 'patient'));
}*/
public function index($patientId)
{
    $patient = Patient::findOrFail($patientId);

    // التحقق من أن المستخدم الحالي له صلاحية الوصول
    if (auth()->user()->id !== $patient->user_id && auth()->user()->family_code !== $patient->family_code) {
        abort(403, 'Unauthorized');
    }

    $files = $patient->medicalFiles; // جلب كل الملفات الطبية الخاصة بالمريض

    return view('medical_files.create', compact('patient', 'files'));
}

/*public function create(User $patient)
{
    $user = auth()->user();

    if ($user->role === 'caregiver') {
        if (!$user->patients()->where('users.id', $patient->id)->exists()) {
            abort(403, 'Unauthorized');
        }
    } elseif ($user->role === 'patient') {
        if ($user->id !== $patient->id) {
            abort(403, 'Unauthorized');
        }
    } else {
        abort(403, 'Unauthorized');
    }

    return view('medical_files.create', compact('patient'));
}*/
public function create(User $patient)
{
    $user = auth()->user();

    if ($user->role === 'caregiver') {
        if (!$user->patients()->where('users.id', $patient->id)->exists()) {
            abort(403, 'Unauthorized - caregiver not linked to this patient');
        }
    } elseif ($user->role === 'patient') {
        if ($user->id !== $patient->id) {
            abort(403, 'Unauthorized - patient can only access their own files');
        }
    } else {
        abort(403, 'Unauthorized - role not allowed');
    }

    $medicalFiles = MedicalFile::where('patient_id', $patient->id)->get();

    return view('medical_files.create', compact('patient', 'medicalFiles'));
}




public function store(Request $request, User $patient)
{
    $user = auth()->user();

    if ($user->role === 'caregiver') {
        if (!$user->patients()->where('users.id', $patient->id)->exists()) {
            abort(403, 'غير مصرح لك بإضافة ملفات لهذا المريض');
        }
    } elseif ($user->role === 'patient') {
        if ($user->id !== $patient->id) {
            abort(403, 'Unauthorized');
        }
    }

    $validated = $request->validate([
        'file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
        'note' => 'nullable|string|max:255',
        'file_type' => 'nullable|string|max:255',
    ]);

    $path = $request->file('file')->store('medical_files', 'public');

    MedicalFile::create([
        'patient_id'  => $patient->id,
        'file_path'   => $path,
        'note'        => $validated['note'] ?? null,
        'file_type'   => $validated['file_type'] ?? null,
        'family_code' => $patient->family_code,
    ]);

    return redirect()->route('medical_files.create', ['patient' => $patient->id])
                     ->with('success', 'تم إضافة الملف الطبي بنجاح');
}


public function showMedicalFiles(User $patient)
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
        // لو مريض، تأكد أنه نفس المريض أو من نفس العائلة
        if ($patient->family_code !== $user->family_code) {
            abort(403, 'Unauthorized access');
        }
    }

    // جلب الملفات الطبية الخاصة بالمريض
    $medicalFiles = MedicalFile::where('patient_id', $patient->id)->get();

    return view('medical_files.create', compact('medicalFiles', 'patient'));
}



}