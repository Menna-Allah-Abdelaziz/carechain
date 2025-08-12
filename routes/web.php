<?php

use App\Http\Controllers\NotificationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\MedicationController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\MedicalFileController;
use App\Http\Controllers\CaregiverController;

Route::get('/', function () {
    return view('welcome');
});

// Login and Logout
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Registration choice page
Route::get('/register', function () {
    return view('auth.register_choice');
})->name('register_choice');

// Patient registration
Route::get('/register/patient', [RegisterController::class, 'showPatientRegistrationForm'])->name('register.patient');
Route::post('/register/patient', [RegisterController::class, 'storePatient'])->name('register.patient.store');

// Caregiver registration
Route::get('/register/caregiver', [RegisterController::class, 'showCaregiverRegistrationForm'])->name('register.caregiver');
Route::post('/register/caregiver', [RegisterController::class, 'storeCaregiver'])->name('register.caregiver.store');

// Protected routes - require authentication
Route::middleware(['auth'])->group(function () {

    // Family dashboard (notes)
    Route::get('/family/dashboard', [NoteController::class, 'index'])->name('family.dashboard');

    // Notes for a specific patient (for caregiver)
    Route::get('/family/dashboard/{patient}', [NoteController::class, 'showPatientNotes'])->name('family.dashboard.patient');

    // Notes CRUD
    Route::post('/notes/{patient}', [NoteController::class, 'store'])->name('notes.store');
    Route::delete('/notes/{note}', [NoteController::class, 'destroy'])->name('notes.destroy');

    // Caregiver patients management
    Route::get('/caregiver_patients', [CaregiverController::class, 'index'])->name('caregiver_patients');
    Route::post('/caregiver_patients/add', [CaregiverController::class, 'addPatientByFamilyCode'])->name('caregiver_patients.add');
    Route::get('/caregiver_patients/{patientId}/notes', [NoteController::class, 'index'])->name('caregiver_patient.notes');

    // Medications
    Route::get('/family/medications', [MedicationController::class, 'index'])->name('medications.index');
    Route::post('/family/medications', [MedicationController::class, 'store'])->name('medications.store');
    Route::put('/family/medications/{medication}', [MedicationController::class, 'update'])->name('medications.update');
    Route::delete('/family/medications/{medication}', [MedicationController::class, 'destroy'])->name('medications.destroy');

    // Medical Files
    Route::get('/patients/{patient}/medical_files', [MedicalFileController::class, 'index'])->name('medical_files.index');
    Route::get('/patients/{patient}/medical_files/create', [MedicalFileController::class, 'create'])->name('medical_files.create');
    Route::post('/patients/{patient}/medical_files', [MedicalFileController::class, 'store'])->name('medical_files.store');
    Route::get('/medical_files/{medical_file}', [MedicalFileController::class, 'show'])->name('medical_files.show');
    Route::get('/medical_files/{medical_file}/edit', [MedicalFileController::class, 'edit'])->name('medical_files.edit');
    Route::put('/medical_files/{medical_file}', [MedicalFileController::class, 'update'])->name('medical_files.update');
    Route::delete('/medical_files/{medical_file}', [MedicalFileController::class, 'destroy'])->name('medical_files.destroy');
    
    // Optional: show medical files by family code (if needed)
    Route::get('/medical_files/{familyCode}', [MedicalFileController::class, 'showMedicalFiles']);

    // Appointments
    Route::get('/appointments', [AppointmentController::class, 'index'])->name('appointments.index');
    Route::get('/appointments/create', [AppointmentController::class, 'create'])->name('appointments.create');
    Route::post('/appointments', [AppointmentController::class, 'store'])->name('appointments.store');
    Route::put('/appointments/{appointment}', [AppointmentController::class, 'update'])->name('appointments.update');
    Route::delete('/appointments/{appointment}', [AppointmentController::class, 'destroy'])->name('appointments.destroy');
    Route::get('/appointments/patient/{patient}', [AppointmentController::class, 'showPatientAppointments'])->name('appointments.patient.index');

});

// Save FCM token for authenticated user
Route::post('/save-fcm-token', function (Request $request) {
    $request->validate(['token' => 'required|string']);
    $user = Auth::user();
    if ($user) {
        $user->fcm_token = $request->token;
        $user->save();
        return response()->json(['message' => 'Token saved successfully']);
    }
    return response()->json(['message' => 'Unauthorized'], 401);
})->middleware('auth');

// Send notification
Route::post('/send-notification', [NotificationController::class, 'sendNotification']);
Route::get('/send-notification', function () {
    return "Please send a POST request to this URL with the device token to trigger notification.";
});
