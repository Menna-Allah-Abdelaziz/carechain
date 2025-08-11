<?php

use Illuminate\Support\Facades\Route;
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

// تسجيل الدخول والخروج
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// صفحة اختيار نوع التسجيل
Route::get('/register', function () {
    return view('auth.register_choice');
})->name('register_choice');

// تسجيل مريض
Route::get('/register/patient', [RegisterController::class, 'showPatientRegistrationForm'])->name('register.patient');
Route::post('/register/patient', [RegisterController::class, 'storePatient'])->name('register.patient.store');

// تسجيل متابع
Route::get('/register/caregiver', [RegisterController::class, 'showCaregiverRegistrationForm'])->name('register.caregiver');
Route::post('/register/caregiver', [RegisterController::class, 'storeCaregiver'])->name('register.caregiver.store');

// روتات المتابع (Caregiver)
Route::middleware(['auth'])->group(function () {
    Route::get('/caregiver_patients', [CaregiverController::class, 'index'])->name('caregiver_patients');
    Route::post('/caregiver_patients/add', [CaregiverController::class, 'addPatientByFamilyCode'])->name('caregiver_patients.add');
    Route::get('/caregiver_patients/{patientId}/notes', [NoteController::class, 'index'])->name('caregiver_patient.notes');
});

// روتات المريض (Patient)
Route::middleware(['auth'])->group(function () {
    // الداشبورد الخاص بالمريض الحالي
    Route::get('/family/dashboard', [NoteController::class, 'index'])->name('family.dashboard');

    // ملاحظات مريض معين (للمتابع)
    Route::get('/family/dashboard/{patient}', [NoteController::class, 'showPatientNotes'])->name('family.dashboard.patient');
  
    // الملاحظات
    Route::post('/notes/{patient}', [NoteController::class, 'store'])->name('notes.store');


    // الأدوية
    Route::get('/family/medications', [MedicationController::class, 'index'])->name('medications.index');
    Route::post('/family/medications', [MedicationController::class, 'store'])->name('medications.store');
  // routes/web.php
Route::post('/medications/store', [MedicationController::class, 'store'])
    ->name('medications.store');

    Route::middleware(['auth'])->group(function () {
    // تعديل دواء - عادة PATCH أو PUT
    Route::put('/family/medications/{medication}', [MedicationController::class, 'update'])->name('medications.update');

    // حذف دواء
    Route::delete('/family/medications/{medication}', [MedicationController::class, 'destroy'])->name('medications.destroy');
});

// المتابع يضيف دواء لمريض محدد
//Route::post('/medications/store/{patientId}', [MedicationController::class, 'store'])
//    ->name('medications.store.forPatient');

//Route::post('/family/medications/{patient}', [MedicationController::class, 'store'])->name('medications.store');

    // الملفات الطبية
    Route::get('/medical-files', [MedicalFileController::class, 'index'])->name('medical-files.index');
    Route::get('/medical-files/create', [MedicalFileController::class, 'create'])->name('medical_files.create');
    Route::post('/medical-files/store', [MedicalFileController::class, 'store'])->name('medical_files.store');

    // المواعيد
// routes/web.php


    Route::get('/appointments', [AppointmentController::class, 'index'])->name('appointments.index');
    Route::get('/appointments/create', [AppointmentController::class, 'create'])->name('appointments.create');
    Route::post('/appointments', [AppointmentController::class, 'store'])->name('appointments.store');
    Route::put('/appointments/{appointment}', [AppointmentController::class, 'update'])->name('appointments.update');
    Route::delete('/appointments/{appointment}', [AppointmentController::class, 'destroy'])->name('appointments.destroy');
 Route::get('/appointments/patient/{patient}', [AppointmentController::class, 'showPatientAppointments'])->name('appointments.patient.index');

});
