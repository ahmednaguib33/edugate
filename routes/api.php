<?php

use App\Http\Controllers\Api\Admin\ApplicationController as AdminApplicationController;
use App\Http\Controllers\Api\Admin\DashboardController;
use App\Http\Controllers\Api\Admin\FacultyController as AdminFacultyController;
use App\Http\Controllers\Api\Admin\ProgramController as AdminProgramController;
use App\Http\Controllers\Api\Admin\StudentController;
use App\Http\Controllers\Api\Admin\UniversityController as AdminUniversityController;
use App\Http\Controllers\Api\ApplicationController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\FacultyController;
use App\Http\Controllers\Api\LeadController;
use App\Http\Controllers\Api\ProgramController;
use App\Http\Controllers\Api\UniversityController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| EduGate API
|--------------------------------------------------------------------------
*/

Route::get('/', fn () => response()->json([
    'name' => 'EduGate API',
    'description' => 'Study at accredited Egyptian universities — programs catalog & applications.',
    'version' => '1.0.0',
    'status' => 'ok',
]));

/*
| Authentication
*/
Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);

    Route::middleware('auth:api')->group(function () {
        Route::get('me', [AuthController::class, 'me']);
        Route::post('logout', [AuthController::class, 'logout']);
        Route::post('refresh', [AuthController::class, 'refresh']);
    });
});

/*
| Public catalog
*/
Route::get('universities', [UniversityController::class, 'index']);
Route::get('universities/{university}', [UniversityController::class, 'show']);
Route::get('faculties', [FacultyController::class, 'index']);
Route::get('faculties/{faculty}', [FacultyController::class, 'show']);
Route::get('programs', [ProgramController::class, 'index']);
Route::get('programs/{program}', [ProgramController::class, 'show']);

// Public lead capture (no authentication required).
Route::post('leads', [LeadController::class, 'store']);

/*
| Student area (authenticated)
*/
Route::middleware('auth:api')->group(function () {
    Route::get('applications', [ApplicationController::class, 'index']);
    Route::post('applications', [ApplicationController::class, 'store']);
    Route::get('applications/{application}', [ApplicationController::class, 'show']);
});

/*
| Admin / back-office area
*/
Route::prefix('admin')->middleware(['auth:api', 'role:admin,agent'])->group(function () {
    Route::get('dashboard/stats', [DashboardController::class, 'stats']);

    // Lead / application management (admin + agent)
    Route::get('applications', [AdminApplicationController::class, 'index']);
    Route::get('applications/{application}', [AdminApplicationController::class, 'show']);
    Route::match(['put', 'patch'], 'applications/{application}', [AdminApplicationController::class, 'update']);

    Route::get('students', [StudentController::class, 'index']);
    Route::get('students/{user}', [StudentController::class, 'show']);

    // Catalog management & destructive actions (admin only)
    Route::middleware('role:admin')->group(function () {
        Route::apiResource('universities', AdminUniversityController::class)->names('admin.universities');
        Route::apiResource('faculties', AdminFacultyController::class)->names('admin.faculties');
        Route::apiResource('programs', AdminProgramController::class)->names('admin.programs');
        Route::delete('applications/{application}', [AdminApplicationController::class, 'destroy']);
    });
});

Route::fallback(fn () => response()->json(['message' => 'Resource not found.'], 404));
