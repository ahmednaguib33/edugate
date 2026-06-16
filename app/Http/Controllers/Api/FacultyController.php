<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\FacultyResource;
use App\Models\Faculty;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class FacultyController extends Controller
{
    /**
     * List active faculties (public catalog).
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $faculties = Faculty::query()
            ->active()
            ->withCount(['programs' => fn ($q) => $q->where('is_active', true)])
            ->when($request->filled('q'), function ($query) use ($request) {
                $term = '%'.$request->string('q').'%';
                $query->where(function ($q) use ($term) {
                    $q->where('name_en', 'like', $term)
                        ->orWhere('name_ar', 'like', $term);
                });
            })
            ->orderBy('name_en')
            ->get();

        return FacultyResource::collection($faculties);
    }

    /**
     * Show a single active faculty with its active programs.
     */
    public function show(Faculty $faculty): FacultyResource
    {
        abort_if(! $faculty->is_active, 404);

        $faculty->load([
            'programs' => fn ($q) => $q->where('is_active', true)
                ->withCount('universities'),
        ]);

        return new FacultyResource($faculty);
    }
}
