<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    use AuthenticatesUsers;

protected function authenticated($request, $user)
{
    if ($user->role === 'patient') {
        return redirect('/patient/' . $user->id . '/dashboard');
    }

    return redirect('/home');
}




    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
}
