<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProjectReserveController;

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

Route::middleware('auth:sanctum')->name("api.")->group(function () {
    Route::post("projects/{project:url}/reserve", [ProjectReserveController::class, 'store'])->name("projects.reserve");
    Route::post("projects/{project:url}/checkouts", [ProjectReserveController::class, 'checkout'])->name("projects.checkouts2");
});
