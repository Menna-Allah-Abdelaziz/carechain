<?php

namespace App\Http\Controllers;

use App\Models\Medication;
use Illuminate\Http\Request;

class MedicationController extends Controller
{
    public function index()
    {
        $medications = Medication::where('family_code', auth()->user()->family_code)->get();
        return view('family.medications', compact('medications'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'dosage' => 'required|string',
            'quantity' => 'required|integer|min:1',
            'times_per_day' => 'required|integer|min:1',
            'first_dose_time' => 'required|date_format:H:i',
        ]);

        Medication::create([
            'family_code' => auth()->user()->family_code,
            'name' => $request->name,
            'dosage' => $request->dosage,
            'quantity' => $request->quantity,
            'times_per_day' => $request->times_per_day,
            'first_dose_time' => $request->first_dose_time,
        ]);

        return redirect()->back()->with('success', 'Medication added successfully.');
    }
}

