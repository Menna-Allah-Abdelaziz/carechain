
<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\MedicationController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\MedicalFileController;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');


Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'store']);

Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::middleware(['auth'])->group(function () {
    Route::get('/family/dashboard', [NoteController::class, 'index'])->name('family.dashboard');
    Route::post('/notes', [NoteController::class, 'store'])->name('notes.store');
Route::get('/medical-files', [MedicalFileController::class, 'index'])->name('medical-files.index');
    Route::get('/family/medications', [MedicationController::class, 'index'])->name('medications.index');
    Route::post('/family/medications', [MedicationController::class, 'store'])->name('medications.store');
    Route::post('/medical-files', [MedicalFileController::class, 'store'])->name('medical-files.store');
    Route::get('/appointments', [AppointmentController::class, 'index'])->name('appointments.index');
    Route::get('/appointments/create', [AppointmentController::class, 'create'])->name('appointments.create');
    Route::post('/appointments', [AppointmentController::class, 'store'])->name('appointments.store');
    Route::get('/upload', function () {
    return view('upload_file');
});
Route::resource('medical_files', App\Http\Controllers\MedicalFileController::class);
Route::get('/medical-files/create', [App\Http\Controllers\MedicalFileController::class, 'create'])->name('medical_files.create');
Route::get('/medical-files/create', [MedicalFileController::class, 'create'])->name('medical_files.create');
Route::post('/medical-files/store', [MedicalFileController::class, 'store'])->name('medical_files.store');
});