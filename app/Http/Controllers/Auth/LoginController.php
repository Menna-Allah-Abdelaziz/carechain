<?php

namespace App\Http\Controllers\Auth;

use App\Models\Note;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    use AuthenticatesUsers;




protected function authenticated($request, $user)
{
    if ($user->role === 'caregiver') {
        // المتابع → صفحة قائمة المرضى
        return redirect()->route('caregiver_patients');
    }

    if ($user->role === 'patient') {
        // المريض → صفحة الـ dashboard الخاصة به
        return redirect()->route('family.dashboard');
    }

    // أي دور تاني → الصفحة الرئيسية
    return redirect('/');
}

 public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
}
