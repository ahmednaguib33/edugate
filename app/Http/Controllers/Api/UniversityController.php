<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UniversityResource;
use App\Models\University;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class UniversityController extends Controller
{
    /**
     * List active universities (public catalog).
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $universities = University::query()
            ->active()
            ->withCount(['programs' => fn ($q) => $q->where('is_active', true)])
            ->when($request->filled('q'), function ($query) use ($request) {
                $term = '%'.$request->string('q').'%';
                $query->where(function ($q) use ($term) {
                    $q->where('name_en', 'like', $term)
                        ->orWhere('name_ar', 'like', $term)
                        ->orWhere('city', 'like', $term);
                });
            })
            ->when($request->filled('city'), fn ($q) => $q->where('city', $request->string('city')))
            ->orderBy('name_en')
            ->paginate($request->integer('per_page', 15));

        return UniversityResource::collection($universities);
    }

    /**
     * Show a single active university with its active programs.
     */
    public function show(University $university): UniversityResource
    {
        abort_if(! $university->is_active, 404);

        $university->load([
            'programs' => fn ($q) => $q->where('is_active', true)->with('faculty'),
        ])->loadCount(['programs' => fn ($q) => $q->where('is_active', true)]);

        return new UniversityResource($university);
    }
}
