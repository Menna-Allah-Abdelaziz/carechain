<?php

namespace App\Http\Controllers;

use App\Models\Note;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class NoteController extends Controller
{
    public function index()
{
    $notes = Note::with('user')
        ->where('family_code', auth()->user()->family_code)
        ->latest()
        ->get();

    return view('family.dashboard', compact('notes'));
}


   public function store(Request $request)
{
    $request->validate([
        'content' => 'required|string',
    ]);

    Note::create([
        'user_id' => auth()->id(),
        'family_code' => auth()->user()->family_code, 
        'content' => $request->content,
    ]);

    return redirect()->back()->with('success', 'Note added successfully.');
}
public function medications()
{
    return view('family.medications');
}


}

?>
