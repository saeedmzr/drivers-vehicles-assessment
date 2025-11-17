<?php


use Illuminate\Support\Facades\Route;
use Presentation\Driver\Controllers\DriverController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your Application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::prefix('drivers')->group(function () {
    Route::get('/', [DriverController::class, 'index']);
    Route::post('/', [DriverController::class, 'store']);
    Route::get('/{id}', [DriverController::class, 'show']);
    Route::put('/{id}', [DriverController::class, 'update']);
    Route::delete('/{id}', [DriverController::class, 'destroy']);
    Route::post('/{id}/assign-vehicle', [DriverController::class, 'assignVehicle']);
});
