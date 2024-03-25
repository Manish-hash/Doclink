<?php

use App\Appointment;
use App\Http\Controllers\PatientListController;
use App\Http\Controllers\FrontEndController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\DashBoardController;
use App\Http\Controllers\PrescriptionController;
use App\Http\Middleware\Patient;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


// Home Page Routes
Route::get('/', [FrontEndController::class, 'index']);
Route::get('/recomanded', [FrontEndController::class, 'recommendDoctor']);
Route::get('/new_appointment/{doctorId}/{date}', [FrontEndController::class, 'recommended_show'])->name('recommend.appointment');//change this
Route::get('/new-appointment/{doctorId}/{date}', [FrontEndController::class, 'show'])->name('create.appointment');


Route::get('/dashboard', [DashBoardController::class, 'index']);

Route::get('/home', [HomeController::class, 'index']);

Auth::routes();

// Patient Routes
Route::group(['middleware' => ['auth', 'patient']], function () {
    // Profile Routes
    Route::get('/user-profile', [ProfileController::class, 'index'])->name('profile');
    Route::post('/user-profile', [ProfileController::class, 'store'])->name('profile.store');
    Route::post('/profile-pic', [ProfileController::class, 'profilePic'])->name('profile.pic');
    Route::get('/search', [AppointmentController::class, 'search'])->name('search');
    Route::post('/book/appointment', [FrontEndController::class, 'store'])->name('book.appointment');
    Route::get('/my-booking', [FrontEndController::class, 'myBookings'])->name('my.booking');
    Route::delete('/my-booking/{id}', [FrontEndController::class, 'destroy'])->name('appointments.delete');
    Route::get('/my-prescription', [FrontEndController::class, 'myPrescription'])->name('my.prescription');
    Route::get('Prescriptions', [FrontEndController::class, 'showSimilarPrescriptions'])->name('showSimilarPrescriptions');

    Route::get('/doctors', 'DoctorController@getdoctor')->name('doctors.getdoctor');


});
// Doctor Routes
Route::group(['middleware' => ['auth', 'doctor']], function () {
    Route::resource('appointment', 'AppointmentController');
    Route::post('/appointment/check', [AppointmentController::class, 'check'])->name('appointment.check');
    Route::post('/appointment/update', [AppointmentController::class, 'updateTime'])->name('update');
    Route::get('patient-today', [PrescriptionController::class, 'index'])->name('patient.today');
    Route::post('prescription', [PrescriptionController::class, 'store'])->name('prescription');
    Route::get('/prescription/{userId}/{date}', [PrescriptionController::class, 'show'])->name('prescription.show');
    Route::get('/all-prescriptions', [PrescriptionController::class, 'showAllPrescriptions'])->name('all.prescriptions');
});

// Admin Routes
Route::group(['middleware' => ['auth', 'admin']], function () {
    Route::resource('doctor', 'DoctorController');
    Route::get('/patients', [PatientListController::class, 'index'])->name('patients');
    Route::get('/status/update/{id}', [PatientListController::class, 'toggleStatus'])->name('update.status');
    Route::get('/all-patients', [PatientListController::class, 'allTimeAppointment'])->name('all.appointments');
    Route::delete('/appointments/{id}', [PatientListController::class, 'destroy'])->name('appointments.delete');
    Route::resource('/department', 'DepartmentController');
});
