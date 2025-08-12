<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MedicalFile;
use Illuminate\Support\Facades\Storage; // <-- لازم تضيفها

class MedicalFileController extends Controller
{
    // عرض صفحة رفع الملفات
    public function index()
    {
        return redirect()->route('medical_files.create');
    }

    public function create(Request $request)
    {
        $familyCode = $request->family_code ?? ''; // لو فيه كود متبعت في الرابط

        $medicalFiles = $familyCode
            ? MedicalFile::where('family_code', $familyCode)->get()
            : [];

        return view('medical_files.create', compact('medicalFiles', 'familyCode'));
    }

    // تخزين الملف في قاعدة البيانات والمجلد
    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:jpeg,png,jpg,pdf,docx',
            'file_type' => 'required|string',
            'family_code' => 'required|string',
            'note' => 'nullable|string',
        ]);

        $path = $request->file('file')->store('medical_files', 'public');

        MedicalFile::create([
            'file_path' => $path,
            'file_type' => $request->file_type,
            'note' => $request->note,
            'family_code' => $request->family_code,
        ]);

        // رجّعني تاني لنفس صفحة الرفع بس مع الكود في الرابط
        return redirect()->route('medical_files.create', ['family_code' => $request->family_code])
                         ->with('success', 'File uploaded successfully!');
    }

    // حذف ملف طبي
    public function destroy($id)
    {
        $file = MedicalFile::findOrFail($id);
       
        if (Storage::exists($file->file_path)) {
            Storage::delete($file->file_path);
        }

        $file->delete();

        return redirect()->back()->with('success', 'File deleted successfully.');
    }
}
