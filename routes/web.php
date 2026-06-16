<?php

use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => response()->json([
    'name' => 'EduGate API',
    'docs' => url('/docs'),
    'api' => url('/api'),
]));

// Serve the OpenAPI specification.
Route::get('/docs/openapi.yaml', function () {
    return Response::file(base_path('docs/openapi.yaml'), [
        'Content-Type' => 'application/yaml',
    ]);
});

// Swagger UI (loaded from CDN) pointing at the spec above.
Route::get('/docs', fn () => view('docs'));

// Named route required by the framework's auth middleware when a guest hits a
// protected endpoint without expecting JSON. The API itself always answers 401
// JSON (see bootstrap/app.php), so this is only a safety net.
Route::get('/login', fn () => response()->json(['message' => 'Unauthenticated.'], 401))->name('login');
