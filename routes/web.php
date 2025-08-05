<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NoteController;

// الصفحة الرئيسية
Route::get('/', function () {
    return view('welcome');
});

// تسجيل الدخول والتسجيل
Auth::routes();

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/home', [HomeController::class, 'index'])->name('home');


// Route::get('/patient/{id}/dashboard', [PatientController::class, 'dashboard'])
//     ->middleware('auth')
//     ->name('patient.dashboard');
Route::get('/patient/dashboard', function () {
    return view('patient.dashboard');
})->middleware('auth'); // علشان تكون محمية للمستخدمين اللي سجلوا دخول
Route::get('/patient/dashboard', function () {
    $notes = auth()->user()->notes; // Assuming relation exists
    return view('patient.dashboard', compact('notes'));
})->middleware('auth');
Route::get('/patient/dashboard', [NoteController::class, 'index'])->middleware('auth');
Route::post('/notes', [NoteController::class, 'store'])->middleware('auth')->name('notes.store');