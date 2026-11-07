<?php

use App\Http\Controllers\Api\EcosystemCourseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

/*
|--------------------------------------------------------------------------
| JLM Ecosystem Integration API (v1)
|--------------------------------------------------------------------------
|
| Secure inter-service endpoints for ZAVORA marketplace and JLM Corporate.
| Protected by the ecosystem.auth middleware (Bearer token / X-Ecosystem-Key).
|
*/
Route::prefix('v1')->middleware('ecosystem.auth')->group(function () {
    // Service health & status for ZAVORA Academy Integration Monitor
    Route::get('/health', [EcosystemCourseController::class, 'health'])->name('api.v1.ecosystem.health');

    // Course catalogue & details for ZAVORA storefront
    Route::get('/courses/catalogue', [EcosystemCourseController::class, 'catalogue'])->name('api.v1.ecosystem.courses.catalogue');
    Route::get('/courses/{identifier}', [EcosystemCourseController::class, 'courseDetails'])->name('api.v1.ecosystem.courses.details');

    // Student enrolment provisioning (idempotent, server-verified)
    Route::post('/enrolments/provision', [EcosystemCourseController::class, 'provisionEnrolment'])->name('api.v1.ecosystem.enrolments.provision');

    // Enrolment transaction status check
    Route::get('/enrolments/status/{reference}', [EcosystemCourseController::class, 'enrolmentStatus'])->name('api.v1.ecosystem.enrolments.status');
});
