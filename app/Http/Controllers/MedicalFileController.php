<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MedicalFile;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class MedicalFileController extends Controller
{
    // Show the page to view and upload medical files for a patient
    public function create(User $patient)
    {
        $user = auth()->user();

        // Authorization check
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

    // Store a new medical file for the patient
    public function store(Request $request, User $patient)
    {
        $user = auth()->user();

        // Authorization check
        if ($user->role === 'caregiver') {
            if (!$user->patients()->where('users.id', $patient->id)->exists()) {
                abort(403, 'You are not authorized to add files for this patient');
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
                         ->with('success', 'Medical file uploaded successfully');
    }

    // Delete a medical file
    public function destroy($id)
    {
        $file = MedicalFile::findOrFail($id);

        $user = auth()->user();

        // Authorization check for deletion
        if ($user->role === 'caregiver') {
            if (!$user->patients()->where('users.id', $file->patient_id)->exists()) {
                abort(403, 'Unauthorized to delete this file');
            }
        } elseif ($user->role === 'patient') {
            if ($user->id !== $file->patient_id) {
                abort(403, 'Unauthorized to delete this file');
            }
        } else {
            abort(403, 'Unauthorized');
        }

        // Delete file from storage
        if (Storage::disk('public')->exists($file->file_path)) {
            Storage::disk('public')->delete($file->file_path);
        }

        // Delete the database record
        $file->delete();

        return redirect()->back()->with('success', 'Medical file deleted successfully.');
    }
}
