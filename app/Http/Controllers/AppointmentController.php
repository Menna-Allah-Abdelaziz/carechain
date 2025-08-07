<?php

namespace App\Http\Controllers;

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
        'family_code' => 'required|exists:users,id',
        'appointment_time' => 'required|date',
        'location' => 'nullable|string|max:255',
        'notes' => 'nullable|string',
    ]);

    $appointment = Appointment::create($validated);

    $user = User::find($appointment->family_code);
    if ($user) {
        $user->notify(new AppointmentReminder($appointment->appointment_time));
    }

    return redirect()->back()->with('success', 'Appointment added and email sent successfully!');
}

}

