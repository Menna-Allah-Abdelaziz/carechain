public function store(Request $request)
{
    $request->validate([
        'name' => 'required',
        'email' => 'required|email|unique:users',
        'password' => 'required|confirmed',
        'role' => 'required|in:patient,doctor',
    ]);

    $user = \App\Models\User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => \Illuminate\Support\Facades\Hash::make($request->password),
        'role' => $request->role,
    ]);

    \Illuminate\Support\Facades\Auth::login($user);

    if ($user->role === 'patient') {
        return redirect('/patient/dashboard');
    } elseif ($user->role === 'doctor') {
        return redirect('/doctor/dashboard');
    }

    return redirect('/');
}