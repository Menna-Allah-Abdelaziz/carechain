<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Appointment;
use App\Models\User;
use App\Notifications\AppointmentReminder;
use Illuminate\Http\Request;


class AppointmentController extends Controller
{
public function index()
{
    $user = auth()->user();

    $familyCode = $user->family_code;

    $appointments = Appointment::where('family_code', $familyCode)->get();

    return view('appointments.index', compact('appointments'));
}


    public function create()
    {
        return view('appointments.create');
    }

public function store(Request $request)
{
    $validated = $request->validate([
        'doctor_name' => 'required|string|max:255',
        'appointment_time' => 'required|date',
        'location' => 'nullable|string|max:255',
        'notes' => 'nullable|string',
    ]);

    $validated['family_code'] = auth()->user()->family_code;

    Appointment::create($validated);

    return redirect()->route('appointments.index')->with('success', 'Appointment created successfully.');
}


}

