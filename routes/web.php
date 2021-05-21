<?php

use Stripe\Stripe;
use App\Http\Livewire\Projects;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{ProjectController,
    ProjectPayPalCheckoutController,
    ProjectSquareupCheckOutController,
    ProjectBraintreeCheckoutController,
    ProjectAuthorizeNetCheckoutController,
    ProjectStripeCheckoutController
};

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/test', function () {
    \App\Http\Services\Facebook::sendEvent(App\Models\Project::query()->first(),"obayda@launchboom.com", "Purchase");
});
Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::redirect("/", "/projects");
    Route::get("/projects", Projects\Index::class)->name("projects.index");
    Route::resource('projects', ProjectController::class)->except("index");
});
//define payment getaway routes

if (setting("payment_getaway", "squareup") == "squareup") {
    Route::get('/projects/{project:url}/checkouts', [ProjectSquareupCheckOutController::class, 'create'])->name("projects.checkouts.create");
    Route::post('/projects/{project:url}/checkouts', [ProjectSquareupCheckOutController::class, 'store'])->name("projects.checkouts.store");
} else if (setting("payment_getaway", "squareup") == "stripe") {
    Route::get('/projects/{project:url}/checkouts', [ProjectStripeCheckoutController::class, 'create'])->name("projects.checkouts.create");
    Route::post('/projects/{project:url}/checkouts', [ProjectStripeCheckoutController::class, 'store'])->name("projects.checkouts.store");
} else if (setting("payment_getaway", "squareup") == "braintree") {
    Route::get('/projects/{project:url}/checkouts', [ProjectBraintreeCheckoutController::class, 'create'])->name("projects.checkouts.create");
    Route::post('/projects/{project:url}/checkouts', [ProjectBraintreeCheckoutController::class, 'store'])->name("projects.checkouts.store");
} else {
    Route::get('/projects/{project:url}/checkouts', [ProjectAuthorizeNetCheckoutController::class, 'create'])->name("projects.checkouts.create");
    Route::post('/projects/{project:url}/checkouts', [ProjectAuthorizeNetCheckoutController::class, 'store'])->name("projects.checkouts.store");
    Route::post('/projects/{project:url}/paypal/checkouts', [ProjectPayPalCheckoutController::class, 'store'])->name("projects.paypal.checkouts.store");
}



