<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\ImportExportController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\POS\POSController;
use App\Http\Controllers\POS\ProductController;
use App\Http\Controllers\Modules\Boarding\BoardingUnitController;
use App\Http\Controllers\Modules\Boarding\BoardingReservationController;
use App\Http\Controllers\Modules\Boarding\BoardingLocationController;
use App\Models\Modules\Boarding\BoardingReservation;
use App\Http\Controllers\LocationSelectionController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\PetController;
use App\Http\Controllers\Modules\Invoices\InvoicePrintController;
use App\Http\Controllers\ClientHistoryController;
use App\Http\Controllers\POS\ReceiptController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\CompanyLoyaltyProgramController;
use App\Http\Controllers\POS\ReturnsController;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

use App\Http\Controllers\DashboardController;

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'has.company'])
    ->name('dashboard');


// Company creation (accessible before company exists)
Route::get('/companies/create', [CompanyController::class, 'create'])->name('companies.create');
Route::post('/companies', [CompanyController::class, 'store'])->name('companies.store');
Route::put('/companies/{company}', [App\Http\Controllers\CompanyController::class, 'update'])->name('companies.update');
Route::middleware(['auth'])->group(function () {
    Route::get('/companies/loyalty-program', [CompanyLoyaltyProgramController::class, 'edit'])
        ->name('companies.loyalty-program.edit');

    Route::post('/companies/loyalty-program', [CompanyLoyaltyProgramController::class, 'save'])
        ->name('companies.loyalty-program.save');
});

Route::get('/clients/json', [\App\Http\Controllers\ClientController::class, 'json'])->name('clients.json');

// All other routes require login and a company
Route::middleware(['auth', 'has.company'])->group(function () {
    Route::resource('companies', CompanyController::class)->except(['create', 'store']);

    


    Route::resource('clients', \App\Http\Controllers\ClientController::class);
    Route::post('/clients/ajax-store', [\App\Http\Controllers\ClientController::class, 'ajaxStore'])->name('clients.ajax-store');
    Route::get('/clients/{client}/history', [ClientHistoryController::class, 'show'])
    ->middleware(['auth'])
    ->name('clients.history');
    Route::post('/clients', [App\Http\Controllers\ClientController::class, 'store'])->name('clients.store');
    Route::get('/pos/client/{client}/loyalty-points', [POSController::class, 'getClientPoints'])->name('pos.client.loyalty-points');
    

    Route::get('/pets/create', [\App\Http\Controllers\PetController::class, 'create'])->name('pets.create');
    Route::post('/pets', [\App\Http\Controllers\PetController::class, 'store'])->name('pets.store');
    Route::post('/pets/ajax-store', [PetController::class, 'ajaxStore'])->name('pets.ajax-store');


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
    Route::get('/select-location', [LocationSelectionController::class, 'show'])->name('select-location');
    Route::post('/select-location', [LocationSelectionController::class, 'store'])->name('select-location.store');
    Route::get('/change-location', [LocationSelectionController::class, 'show'])->name('change-location');

    // Staff Management
    Route::get('/staff', [\App\Http\Controllers\StaffController::class, 'index'])->name('staff.index');
    Route::get('/staff/create', [\App\Http\Controllers\StaffController::class, 'create'])->name('staff.create');
    Route::post('/staff', [\App\Http\Controllers\StaffController::class, 'store'])->name('staff.store');
    Route::get('/staff/{id}/edit', [\App\Http\Controllers\StaffController::class, 'edit'])->name('staff.edit');
    Route::put('/staff/{id}', [\App\Http\Controllers\StaffController::class, 'update'])->name('staff.update');
    Route::delete('/availability-exceptions/{id}', [\App\Http\Controllers\AvailabilityExceptionController::class, 'destroy'])->name('availability-exceptions.destroy');
    Route::post('/availability-exceptions', [\App\Http\Controllers\AvailabilityExceptionController::class, 'store'])->name('availability-exceptions.store');

    // Schedule Management
    Route::middleware(['auth', 'has.company', 'ensure.location.selected', 'check.module.access:grooming'])->group(function () {

        Route::get('/modules/grooming/schedule', [ScheduleController::class, 'index'])->name('schedule.index');
        Route::get('/api/appointment-form-data', [AppointmentController::class, 'formData'])->name('appointments.form-data');
        Route::get('/api/clients/search', [AppointmentController::class, 'searchClients']);
        Route::get('/api/clients/{client}/pets', [AppointmentController::class, 'getClientPets']);
        Route::get('/api/appointments/{appointment}', [AppointmentController::class, 'show']);
        Route::post('/appointments', [AppointmentController::class, 'store'])->name('appointments.store');
        Route::get('/api/appointments', [AppointmentController::class, 'allAppointments']);
        Route::put('/appointments/{id}', [AppointmentController::class, 'update']);
        Route::put('/appointments/{appointment}', [AppointmentController::class, 'update'])->name('appointments.update');
    });
    

    // Tools Management
    Route::get('/import-export', [ImportExportController::class, 'index'])->name('import_export.index');

    Route::get('/export/clients', [ImportExportController::class, 'exportClients'])->name('export.clients');
    Route::get('/export/pets', [ImportExportController::class, 'exportPets'])->name('export.pets');
    Route::get('/export/services', [ImportExportController::class, 'exportServices'])->name('export.services');

    Route::post('/import/clients', [ImportExportController::class, 'importClients'])->name('import.clients');
    Route::post('/import/pets', [ImportExportController::class, 'importPets'])->name('import.pets');
    Route::post('/import/services', [ImportExportController::class, 'importServices'])->name('import.services');

    Route::get('/export/products', [\App\Http\Controllers\ImportExportController::class, 'exportProducts'])->name('export.products');
    Route::post('/import/products', [ImportExportController::class, 'importProducts'])->name('import.products');
    Route::get('/inventory/count-sheet/{location}', [InventoryController::class, 'showCountSheet'])->name('inventory.countSheet');
    Route::post('/inventory/reconcile', [InventoryController::class, 'reconcile'])->name('inventory.reconcile');


    // Admin Management
    Route::get('/admin/users', [UserManagementController::class, 'index'])->name('admin.users');
    Route::get('/admin/users/{user}/edit', [UserManagementController::class, 'edit'])->name('admin.users.edit');
    Route::put('/admin/users/{user}', [UserManagementController::class, 'update'])->name('admin.users.update');
    Route::delete('/admin/users/{user}', [UserManagementController::class, 'destroy'])->name('admin.users.destroy');
    Route::get('/admin/users/{user}/impersonate', [UserManagementController::class, 'impersonate'])->name('admin.users.impersonate');
    Route::get('/admin/stop-impersonating', function () {
        $impersonatorId = session()->pull('impersonator_id');

        if ($impersonatorId) {
            $originalUser = \App\Models\User::find($impersonatorId);
            auth()->login($originalUser);
        }

        return redirect()->route('admin.users')->with('success', 'Returned to your admin account.');
    })->name('admin.stop-impersonating');

    // Retail Management
    Route::get('/modules/retail', [ProductController::class, 'index'])->name('modules.retail');

    // 🆕 Point of Sale
    // 🆕 Point of Sale
    Route::middleware(['auth', 'has.company', 'ensure.location.selected', 'check.module.access:pos'])->group(function () {

    Route::get('/pos', [POSController::class, 'index'])->name('pos.index');

    Route::get('/pos/products', [ProductController::class, 'index'])->name('pos.products');
    Route::post('/pos/select-location', [POSController::class, 'setLocation'])->name('pos.set-location');
    Route::post('/pos/checkout', [POSController::class, 'checkout'])->name('pos.checkout');

    Route::get('/pos/products/create', [ProductController::class, 'create'])->name('pos.products.create');
    Route::post('/pos/products', [ProductController::class, 'store'])->name('pos.products.store');
    Route::get('/pos/products/{product}/edit', [ProductController::class, 'edit'])->name('pos.products.edit');
    Route::put('/pos/products/{product}', [ProductController::class, 'update'])->name('pos.products.update');

    Route::get('/api/pos/products', [ProductController::class, 'getProductsJson'])->name('pos.products.json');
    Route::get('/pos/api/products', [ProductController::class, 'apiProducts'])->name('pos.api.products');
    Route::get('/pos/api/products/search', [ProductController::class, 'search'])->name('pos.products.search');
    Route::post('/pos/api/products', [POSController::class, 'storeProduct']);
    Route::get('/pos/client/{client}/unpaid-invoices', [POSController::class, 'getUnpaidInvoices'])->name('pos.unpaid-invoices');
    Route::get('/invoices/{invoice}/print', [InvoicePrintController::class, 'show'])
    ->middleware(['auth'])
    ->name('invoices.print');
    Route::get('/pos/sales/{sale}/receipt', [ReceiptController::class, 'show'])
    ->middleware(['auth'])
    ->name('pos.receipt');
    Route::middleware(['auth'])->prefix('pos')->group(function () {
        Route::get('/returns', [ReturnsController::class, 'index'])->name('pos.returns.index');
    });
    Route::get('pos/returns/client/{clientId}/sales', [\App\Http\Controllers\POS\ReturnsController::class, 'getClientSales'])->name('pos.returns.client.sales');
    Route::post('/pos/returns/process', [\App\Http\Controllers\POS\ReturnsController::class, 'processReturn'])
    ->name('pos.returns.process')
    ->middleware(['auth']);
    Route::get('/pos/returns/{id}', [\App\Http\Controllers\POS\ReturnsController::class, 'show'])
    ->name('pos.returns.show')
    ->middleware(['auth']);
    Route::get('/pos/returns/{return}/receipt', [\App\Http\Controllers\POS\ReturnsReceiptController::class, 'show'])->name('pos.returns.receipt');
});

    // Profile Management
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->middleware(['auth', 'has.company'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->middleware(['auth', 'has.company'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->middleware(['auth', 'has.company'])
        ->name('profile.destroy');

    // Reports
    Route::get('/reports/recurring-conflicts', [ReportController::class, 'recurringConflicts'])
        ->middleware(['auth', 'has.company'])
        ->name('reports.recurring-conflicts');

    // Boarding Management
    Route::middleware(['auth', 'has.company', 'ensure.location.selected', 'check.module.access:boarding'])->group(function () {

        Route::get('/modules/boarding', function () {
            return view('modules.boarding');
        })->name('modules.boarding');
    
        Route::get('/boarding/units/create', [BoardingUnitController::class, 'create'])->name('boarding.units.create');
        Route::get('/boarding/units', [BoardingUnitController::class, 'index'])->name('boarding.units.index');
        Route::post('/boarding/units', [BoardingUnitController::class, 'store'])->name('boarding.units.store');
        Route::get('/boarding/units/{id}/edit', [BoardingUnitController::class, 'edit'])->name('boarding.units.edit');
        Route::put('/boarding/units/{id}', [BoardingUnitController::class, 'update'])->name('boarding.units.update');
        Route::delete('/boarding/units/{id}', [BoardingUnitController::class, 'destroy'])->name('boarding.units.destroy');
    
        Route::get('/boarding/reservations', [BoardingReservationController::class, 'index'])->name('boarding.reservations.index');
        Route::get('/boarding/reservations/create', [BoardingReservationController::class, 'create'])->name('boarding.reservations.create');
        Route::post('/boarding/reservations', [BoardingReservationController::class, 'store'])->name('boarding.reservations.store');
        Route::get('/boarding/reservations/{reservation}/edit', [BoardingReservationController::class, 'edit'])->name('boarding.reservations.edit');
        Route::put('/boarding/reservations/{reservation}', [BoardingReservationController::class, 'update'])->name('boarding.reservations.update');
        Route::delete('/boarding/reservations/{reservation}', [BoardingReservationController::class, 'destroy'])->name('boarding.reservations.destroy');
    
        Route::post('/boarding/fetch-pet-notes', [BoardingReservationController::class, 'getPetNotes'])->name('boarding.fetch-pet-notes');
        Route::get('/boarding/reservations/json', [BoardingReservationController::class, 'json'])->name('boarding.reservations.json');
    
        Route::get('/boarding/select-location', [BoardingLocationController::class, 'selectLocation'])->name('boarding.location.select');
        Route::post('/boarding/set-location', [BoardingLocationController::class, 'setLocation'])->name('boarding.location.set');
        Route::post('/boarding/reservations/{reservation}/cancel', [BoardingReservationController::class, 'cancel'])->name('boarding.reservations.cancel');
    });

    //  Settings Management
    Route::middleware(['auth'])->prefix('settings')->group(function () {
        Route::get('email', [\App\Http\Controllers\Admin\EmailSettingsController::class, 'edit'])->name('settings.email.edit');
        Route::post('email', [\App\Http\Controllers\Admin\EmailSettingsController::class, 'update'])->name('settings.email.update');
    });
    Route::get('/settings/email/test', [\App\Http\Controllers\EmailTestController::class, 'send'])->name('settings.email.test');    

// Daycare Management
Route::middleware(['auth', 'has.company', 'ensure.location.selected', 'check.module.access:daycare'])->group(function () {
    Route::get('/modules/daycare', function () {
        return view('modules.daycare');
    })->name('modules.daycare');
});

// House/Pet-Sitting Management
Route::middleware(['auth', 'has.company', 'ensure.location.selected', 'check.module.access:house'])->group(function () {
    Route::get('/modules/house-sitting', function () {
        return view('modules.house');
    })->name('modules.house');
});

});

// Public front-end routes
Route::get('/', [PublicController::class, 'home'])->name('public.home');
Route::get('/about', [PublicController::class, 'about'])->name('public.about');
Route::get('/pricing', [PublicController::class, 'pricing'])->name('public.pricing');
Route::get('/contact', [PublicController::class, 'contact'])->name('public.contact');
Route::post('/contact', [PublicController::class, 'submitContact'])->name('public.contact.submit');
Route::get('/company-assets/logo/{companyId}', function ($companyId) {
    $path = "company-assets/company_{$companyId}_logo.png";

    if (!Storage::disk('public')->exists($path)) {
        abort(404);
    }

    $file = Storage::disk('public')->get($path);
    $type = Storage::disk('public')->mimeType($path);

    return Response::make($file, 200)->header("Content-Type", $type);
});

require __DIR__.'/auth.php';
