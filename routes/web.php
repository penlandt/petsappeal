<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\PublicController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'has.company'])->name('dashboard');

// Company creation (accessible before company exists)
Route::get('/companies/create', [CompanyController::class, 'create'])->name('companies.create');
Route::post('/companies', [CompanyController::class, 'store'])->name('companies.store');

// All other routes require login and a company
Route::middleware(['auth', 'has.company'])->group(function () {
    Route::resource('companies', CompanyController::class)->except(['create', 'store']);
    Route::resource('clients', \App\Http\Controllers\ClientController::class);

    Route::get('/pets/create', [\App\Http\Controllers\PetController::class, 'create'])->name('pets.create');
    Route::post('/pets', [\App\Http\Controllers\PetController::class, 'store'])->name('pets.store');
    Route::get('/pets/{pet}/edit', [\App\Http\Controllers\PetController::class, 'edit'])->name('pets.edit');
    Route::get('/pets', [\App\Http\Controllers\PetController::class, 'index'])->name('pets.index');
    Route::put('/pets/{pet}', [\App\Http\Controllers\PetController::class, 'update'])->name('pets.update');

    Route::get('/services/create', [\App\Http\Controllers\ServiceController::class, 'create'])->name('services.create');
    Route::get('/services', [\App\Http\Controllers\ServiceController::class, 'index'])->name('services.index');
    Route::get('/services/{service}/edit', [\App\Http\Controllers\ServiceController::class, 'edit'])->name('services.edit');
    Route::post('/services', [\App\Http\Controllers\ServiceController::class, 'store'])->name('services.store');
    Route::put('/services/{service}', [\App\Http\Controllers\ServiceController::class, 'update'])->name('services.update');

    Route::resource('locations', LocationController::class)->only(['index', 'create', 'store']);
    Route::get('/locations/{location}/edit', [LocationController::class, 'edit'])->name('locations.edit');
    Route::put('/locations/{location}', [LocationController::class, 'update'])->name('locations.update');

    // Staff Management
    Route::get('/staff', [\App\Http\Controllers\StaffController::class, 'index'])->name('staff.index');
    Route::get('/staff/create', [\App\Http\Controllers\StaffController::class, 'create'])->name('staff.create');
    Route::post('/staff', [\App\Http\Controllers\StaffController::class, 'store'])->name('staff.store');
    Route::get('/staff/{id}/edit', [\App\Http\Controllers\StaffController::class, 'edit'])->name('staff.edit');
    Route::put('/staff/{id}', [\App\Http\Controllers\StaffController::class, 'update'])->name('staff.update');
    Route::delete('/availability-exceptions/{id}', [\App\Http\Controllers\AvailabilityExceptionController::class, 'destroy'])->name('availability-exceptions.destroy');
    Route::post('/availability-exceptions', [\App\Http\Controllers\AvailabilityExceptionController::class, 'store'])->name('availability-exceptions.store');

    // Schedule Management
    Route::get('/schedule', [ScheduleController::class, 'index'])->name('schedule.index');
    Route::get('/api/appointment-form-data', [AppointmentController::class, 'formData'])->name('appointments.form-data');
    Route::get('/api/clients/search', [AppointmentController::class, 'searchClients']);
    Route::get('/api/clients/{client}/pets', [AppointmentController::class, 'getClientPets']);
    Route::get('/api/appointments/{appointment}', [AppointmentController::class, 'show']);
    Route::post('/appointments', [AppointmentController::class, 'store'])->name('appointments.store');
    Route::get('/api/appointments', [AppointmentController::class, 'allAppointments']);
    Route::put('/appointments/{id}', [AppointmentController::class, 'update']);
    Route::put('/appointments/{appointment}', [AppointmentController::class, 'update'])->name('appointments.update');
});

// Public front-end routes
Route::get('/', [PublicController::class, 'home'])->name('public.home');
Route::get('/about', [PublicController::class, 'about'])->name('public.about');
Route::get('/pricing', [PublicController::class, 'pricing'])->name('public.pricing');
Route::get('/contact', [PublicController::class, 'contact'])->name('public.contact');
Route::post('/contact', [PublicController::class, 'submitContact'])->name('public.contact.submit');

require __DIR__.'/auth.php';
