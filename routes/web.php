<?php

use App\Http\Controllers\Web\CatalogController;
use App\Http\Controllers\Web\LeadController;
use App\Http\Controllers\Web\LocaleController;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Route;

// Public website (server-rendered, Arabic RTL first).
Route::get('/', [CatalogController::class, 'home'])->name('home');
Route::get('/programs', [CatalogController::class, 'programs'])->name('programs.index');
Route::get('/programs/{program:slug}', [CatalogController::class, 'program'])->name('programs.show');
Route::get('/faculties', [CatalogController::class, 'faculties'])->name('faculties.index');
Route::get('/universities', [CatalogController::class, 'universities'])->name('universities.index');
Route::post('/apply', [LeadController::class, 'store'])->name('lead.store');

// Language switch (ar / en).
Route::get('/lang/{locale}', [LocaleController::class, 'switch'])->name('locale.switch');

// API documentation — OpenAPI spec + Swagger UI.
Route::get('/docs/openapi.yaml', fn () => Response::file(base_path('docs/openapi.yaml'), [
    'Content-Type' => 'application/yaml',
]));
Route::get('/docs', fn () => view('docs'));

// Named route the framework's auth middleware expects for guests; the API
// itself always answers with JSON 401 (see bootstrap/app.php).
Route::get('/login', fn () => response()->json(['message' => 'Unauthenticated.'], 401))->name('login');
