<?php


   namespace App\Http\Controllers;

use App\Models\Note;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NoteController extends Controller
{
    public function index($patientId = null)
{
    $patient = null;

    if (Auth::user()->role === 'patient') {
        $patient = Auth::user();
        $familyCode = $patient->family_code;
    } elseif ($patientId) {
        $patient = User::where('id', $patientId)
            ->where('role', 'patient')
            ->firstOrFail();
        $familyCode = $patient->family_code;
    } else {
        $familyCode = Auth::user()->family_code;
    }

    $notes = Note::where('family_code', $familyCode)
        ->latest()
        ->get();

    return view('family.dashboard', compact('notes', 'patient', 'patientId'));
}


    public function store(Request $request, $patientId = null)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        if ($patientId) {
            $patient = User::where('id', $patientId)
                ->where('role', 'patient')
                ->firstOrFail();

            $familyCode = $patient->family_code;
        } else {
            $familyCode = Auth::user()->family_code;
        }

        Note::create([
            'user_id'     => Auth::id(),
            'content'     => $request->content,
            'family_code' => $familyCode
        ]);

        return back()->with('success', 'Note added successfully.');
    }




public function showPatientNotes($patientId)
{
    $patient = \App\Models\User::where('id', $patientId)
        ->where('role', 'patient')
        ->firstOrFail();

    $notes = Note::where('family_code', $patient->family_code)
             ->latest()
             ->get();


    return view('family.dashboard', compact('patient', 'notes'));
}


public function medications()
{
    return view('family.medications');
}


}

?>
