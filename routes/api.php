<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AppointmentController;
use App\Models\Modules\Boarding\BoardingUnit;
use App\Models\Location;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/appointments/{id}', [AppointmentController::class, 'show'])->withoutMiddleware(['auth:sanctum']);

Route::get('/boarding-units/{id}', function ($id) {
    try {
        $unit = BoardingUnit::findOrFail($id);
        return response()->json($unit);
    } catch (\Exception $e) {
        Log::error('Error fetching boarding unit: ' . $e->getMessage(), ['id' => $id]);
        return response()->json(['error' => 'Failed to fetch boarding unit.'], 500);
    }
})->withoutMiddleware(['auth:sanctum']);

Route::get('/locations/{id}', function ($id) {
    try {
        $location = Location::findOrFail($id);
        return response()->json($location);
    } catch (\Exception $e) {
        Log::error('Error fetching location: ' . $e->getMessage(), ['id' => $id]);
        return response()->json(['error' => 'Failed to fetch location.'], 500);
    }
})->withoutMiddleware(['auth:sanctum']);
