<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class RegisterController extends Controller
{
    public function showPatientRegistrationForm()
    {
        return view('auth.register_patient'); // نموذج فيه حقل family_code
    }

    public function showCaregiverRegistrationForm()
    {
        return view('auth.register_caregiver'); // نموذج بدون حقل family_code
    }

    public function storePatient(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => [
                'required',
                'confirmed',
                'min:8'
                //,                  // طول كلمة المرور على الأقل 8 حروف
              //  'regex:/[a-z]/',          // يحتوي على حرف صغير
             //   'regex:/[A-Z]/',          // يحتوي على حرف كبير
               // 'regex:/[0-9]/',          // يحتوي على رقم
               // 'regex:/[@$!%*?&]/',      // يحتوي على رمز خاص
            ],
            'family_code' => 'required|string|unique:users,family_code',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'patient',
            'family_code' => $request->family_code,
        ]);

        Auth::login($user);
        return redirect('/family/dashboard');
    }

    public function storeCaregiver(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
        'password' => [
        'required',
        'confirmed',
        'min:8'
        //,
       // 'regex:/[a-z]/',   // حرف صغير
       // 'regex:/[A-Z]/',   // حرف كبير
       // 'regex:/[0-9]/',   // رقم
       // 'regex:/[@$!%*?&]/', // رمز خاص
    ]
]/*
, [
    'password.required' => 'Password is required.',
    'password.confirmed' => 'Password confirmation does not match.',
    'password.min' => 'Password must be at least 8 characters.',
   'password.regex' => 'Password must contain at least one lowercase letter, one uppercase letter, one number, and one special character (@$!%*?&).',

]*/ 
);


        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'caregiver',
        ]);

        Auth::login($user);
        return redirect('/caregiver_patients'); // صفحة المتابع بيضيف فيها المرضى
    }
}

