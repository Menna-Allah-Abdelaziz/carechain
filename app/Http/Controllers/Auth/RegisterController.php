<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed',
            'role' => 'required|in:patient,caregiver',
             'family_code' => 'required|string',
        ]);
if ($request->role === 'patient') {
    if (User::where('family_code', $request->family_code)->exists()) {
        return back()->withErrors(['family_code' => 'This family code is already taken.']);
    }
} elseif ($request->role === 'caregiver') {
    if (!User::where('family_code', $request->family_code)->exists()) {
        return back()->withErrors(['family_code' => 'This family code does not exist.']);
    }
}

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'family_code' => $request->family_code,
        ]);

        Auth::login($user);

    return redirect('/family/dashboard');
    }
}
